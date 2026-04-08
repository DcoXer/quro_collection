<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderNotification;
use App\Mail\OrderConfirmation;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Voucher;
use App\Services\CartService;
use App\Services\MidtransService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private MidtransService $midtransService,
    ) {}

    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        try {
            ['items' => $resolved, 'total' => $total] = $this->cartService->resolveItems($cart);
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Rebuild cart display with resolved (flash-discounted) prices
        $cart = collect($resolved)->map(fn($item) => [
            'product_id' => $item['product_id'],
            'name'       => $item['product']->name,
            'image'      => $item['product']->image,
            'size'       => $item['size'],
            'quantity'   => $item['quantity'],
            'price'      => $item['price'],
        ])->values()->all();

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'province_id'      => 'required|string',
            'city_id'          => 'required|string',
            'district_id'      => 'required|string',
            'village_id'       => 'required|string',
            'courier'          => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        try {
            ['items' => $items, 'total' => $total] = $this->cartService->resolveItems($cart);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        [$discount, $voucherCode] = $this->resolveVoucher($total);

        try {
            $shippingCost = $this->resolveShippingCost($request->village_id);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        $shippingData = array_merge($request->only([
            'shipping_name', 'shipping_phone',
            'province_id', 'city_id', 'district_id', 'village_id',
            'courier_service',
        ]), [
            'shipping_cost'    => $shippingCost,
            'shipping_address' => implode(', ', array_filter([
                $request->province_name,
                $request->city_name,
                $request->district_name,
                $request->village_name,
            ])) . '. ' . $request->shipping_address,
        ]);

        try {
            $order = $this->orderService->create($shippingData, $items, $total, $discount, $voucherCode);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        session()->forget(['cart', 'voucher', 'ongkir_verified']);

        $this->sendOrderEmails($order);

        Notification::send(
            $order->user_id,
            'order',
            'Pesanan Berhasil Dibuat',
            'Pesanan ' . $order->invoice_number . ' menunggu pembayaran. Selesaikan sebelum kehabisan stok!',
            route('checkout.payment', $order->invoice_number)
        );

        return $this->redirectToPayment($order);
    }

    public function payment($invoice)
    {
        $order = Order::where('invoice_number', $invoice)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $clientKey = config('services.midtrans.client_key');

        return view('checkout.payment', compact('order', 'clientKey'));
    }

    public function success($invoice)
    {
        $order = Order::where('invoice_number', $invoice)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    // -------------------------------------------------------------------------

    /**
     * Resolve shipping cost from server-side session — never trust $request->shipping_cost.
     * Throws \Exception if no verified ongkir data found for the submitted village.
     *
     * @throws \Exception
     */
    private function resolveShippingCost(string $villageId): int
    {
        $ongkir = session('ongkir_verified');

        if (!$ongkir || $ongkir['destination'] !== $villageId) {
            Log::warning('payment.suspicious', [
                'event'      => 'shipping_cost_tamper_attempt',
                'user_id'    => auth()->id(),
                'village_id' => $villageId,
                'session'    => $ongkir ? $ongkir['destination'] : 'none',
                'ip'         => request()->ip(),
            ]);
            throw new \Exception('Data ongkos kirim tidak valid. Silakan pilih ulang alamat pengiriman.');
        }

        $jne = collect($ongkir['couriers'])->first(
            fn($c) => str_contains(strtolower($c['courier_name'] ?? ''), 'jne')
                   || str_contains(strtolower($c['courier_code'] ?? ''), 'jne')
        );

        if (!$jne) {
            throw new \Exception('Layanan JNE tidak tersedia untuk wilayah ini.');
        }

        return (int) $jne['price'];
    }

    private function resolveVoucher(int $total): array
    {
        if (!session('voucher')) {
            return [0, null];
        }

        $voucher    = Voucher::where('code', session('voucher.code'))->first();
        $validation = $voucher?->isValid($total);

        if ($voucher && $validation['valid']) {
            return [$voucher->calculateDiscount($total), $voucher->code];
        }

        Log::warning('payment.suspicious', [
            'event'        => 'voucher_revalidation_failed',
            'user_id'      => auth()->id(),
            'voucher_code' => session('voucher.code'),
            'reason'       => $validation['reason'] ?? 'voucher not found',
            'ip'           => request()->ip(),
        ]);

        session()->forget('voucher');

        return [0, null];
    }

    private function sendOrderEmails(Order $order): void
    {
        try {
            Mail::to($order->user->email)->send(new OrderConfirmation($order));
            Mail::to(config('mail.admin_email'))->send(new NewOrderNotification($order));
        } catch (\Exception $e) {
            Log::warning('Failed to send order emails: ' . $e->getMessage());
        }
    }

    private function redirectToPayment(Order $order)
    {
        try {
            $snapToken = $this->midtransService->createSnapToken($order);
            $order->update(['payment_token' => $snapToken]);

            return redirect()->route('checkout.payment', $order->invoice_number);
        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());

            return redirect()->route('orders.index')
                ->with('info', 'Pesanan berhasil dibuat. Pembayaran akan diaktifkan segera.');
        }
    }
}
