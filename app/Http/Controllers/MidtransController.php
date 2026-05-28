<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusMail;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Services\FonnteService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransController extends Controller
{
    // Valid status transitions — order can only move forward, never back
    const TRANSITIONS = [
        'pending'    => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped'    => ['delivered'],
        'delivered'  => [],
        'cancelled'  => [],
    ];

    public function __construct(
        private MidtransService $midtransService,
        private FonnteService $fonnteService,
    ) {}

    public function webhook(Request $request)
    {
        try {
            $notification = $this->midtransService->parseWebhook();
        } catch (\Exception $e) {
            Log::warning('Midtrans webhook invalid signature', [
                'ip'      => $request->ip(),
                'payload' => $request->all(),
            ]);
            return response()->json(['message' => $e->getMessage()], 403);
        }

        $order = Order::where('invoice_number', $notification->order_id)->first();

        if (!$order) {
            Log::warning('Midtrans webhook: order not found', ['order_id' => $notification->order_id]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Validate gross_amount from Midtrans matches DB — reject if tampered
        $midtransAmount = (int) round((float) $notification->gross_amount);
        if ($midtransAmount !== (int) $order->total_amount) {
            Log::critical('Midtrans webhook: amount mismatch — possible fraud', [
                'invoice'         => $order->invoice_number,
                'db_amount'       => $order->total_amount,
                'midtrans_amount' => $midtransAmount,
                'ip'              => $request->ip(),
            ]);
            $this->fonnteService->notifyAdminFraud(
                $order->invoice_number,
                (int) $order->total_amount,
                $midtransAmount,
                $request->ip(),
            );
            return response()->json(['message' => 'Amount mismatch'], 422);
        }

        $transactionStatus = $notification->transaction_status;
        $newStatus         = $this->mapTransactionStatus($transactionStatus);

        Log::info('Midtrans webhook received', [
            'invoice'            => $order->invoice_number,
            'current_status'     => $order->status,
            'transaction_status' => $transactionStatus,
            'mapped_to'          => $newStatus,
        ]);

        if ($newStatus === null) {
            // Unhandled transaction status (e.g. 'pending', 'challenge') — no-op
            return response()->json(['message' => 'OK']);
        }

        $allowed = self::TRANSITIONS[$order->status] ?? [];

        if (!in_array($newStatus, $allowed)) {
            Log::warning('Midtrans webhook: status transition blocked', [
                'invoice'    => $order->invoice_number,
                'from'       => $order->status,
                'attempted'  => $newStatus,
            ]);
            return response()->json(['message' => 'OK']); // return 200 to stop Midtrans retrying
        }

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'processing') {
            $updateData['payment_method'] = $notification->payment_type;
        }

        DB::transaction(function () use ($order, $updateData, $newStatus) {
            $order->update($updateData);

            // Kembalikan voucher jika order dibatalkan
            if ($newStatus === 'cancelled' && $order->voucher_code) {
                $voucher = Voucher::where('code', $order->voucher_code)->first();
                if ($voucher) {
                    $voucher->decrement('used_count');
                    VoucherUsage::where('voucher_id', $voucher->id)
                        ->where('user_id', $order->user_id)
                        ->latest()
                        ->limit(1)
                        ->delete();
                }
            }

            // Kurangi stok atomik setelah pembayaran dikonfirmasi
            if ($newStatus === 'processing') {
                $order->load('items');
                foreach ($order->items as $item) {
                    $updated = $this->deductStock($item->product_id, $item->size, $item->quantity);

                    if (!$updated) {
                        Log::warning('payment.suspicious', [
                            'event'      => 'stock_insufficient_on_paid',
                            'invoice'    => $order->invoice_number,
                            'product_id' => $item->product_id,
                            'size'       => $item->size,
                            'quantity'   => $item->quantity,
                        ]);
                        // Lanjutkan proses — order tetap paid, stok bisa negatif ditangani ops
                    }
                }
            }

            // Kirim notifikasi in-app ke user
            $messages = [
                'processing' => 'Pembayaran pesanan ' . $order->invoice_number . ' dikonfirmasi. Pesanan sedang diproses.',
                'cancelled'  => 'Pesanan ' . $order->invoice_number . ' dibatalkan.',
            ];
            if (isset($messages[$newStatus])) {
                Notification::send(
                    $order->user_id,
                    'order_status',
                    'Update Pesanan',
                    $messages[$newStatus],
                    route('orders.show', $order->invoice_number)
                );

                // Kirim email ke customer
                Mail::to($order->user->email)
                    ->queue(new OrderStatusMail($order, $order->getOriginal('status')));
            }

            // Notifikasi WhatsApp ke admin jika pembayaran dikonfirmasi
            if ($newStatus === 'processing') {
                $this->fonnteService->notifyAdminNewOrder(
                    $order->invoice_number,
                    $order->user->name,
                    $order->total_amount,
                );
            }
        });

        Log::info('Midtrans webhook: order status updated', [
            'invoice' => $order->invoice_number,
            'to'      => $newStatus,
        ]);

        return response()->json(['message' => 'OK']);
    }

    /**
     * Atomic stock decrement with WHERE stock >= quantity guard.
     * Returns number of affected rows (0 = insufficient stock).
     */
    private function deductStock(int $productId, ?string $size, int $quantity): int
    {
        $hasVariant = $size && ProductVariant::where('product_id', $productId)->where('size', $size)->exists();

        if ($hasVariant) {
            return ProductVariant::where('product_id', $productId)
                ->where('size', $size)
                ->where('stock', '>=', $quantity)
                ->decrement('stock', $quantity);
        }

        return Product::where('id', $productId)
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);
    }

    /**
     * Manual payment status check — user-triggered fallback for when webhook wasn't delivered.
     * Polls Midtrans directly, then applies the same status-transition logic as the webhook.
     */
    public function checkPayment(Request $request, string $invoice)
    {
        // Load awal tanpa lock — hanya untuk validasi awal sebelum HTTP call ke Midtrans
        $order = Order::where('invoice_number', $invoice)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json(['status' => $order->status, 'message' => 'Order already updated']);
        }

        try {
            $data = $this->midtransService->checkTransactionStatus($invoice);
        } catch (\Exception $e) {
            Log::warning('checkPayment: Midtrans status check failed', [
                'invoice' => $invoice,
                'error'   => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Failed to check payment status'], 502);
        }

        $transactionStatus = $data['transaction_status'] ?? null;

        if (!$transactionStatus) {
            return response()->json(['message' => 'No transaction status returned'], 422);
        }

        $newStatus = $this->mapTransactionStatus($transactionStatus);

        if ($newStatus === null) {
            return response()->json(['status' => $order->status, 'message' => 'Payment not yet completed']);
        }

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'processing') {
            $updateData['payment_method'] = $data['payment_type'] ?? null;
        }

        DB::transaction(function () use ($order, $updateData, $newStatus) {
            // Re-load dengan lockForUpdate di dalam transaction — cegah race condition dengan webhook
            $order = Order::where('id', $order->id)
                ->lockForUpdate()
                ->first();

            // Re-check status setelah dapat lock — mungkin webhook udah update duluan
            if ($order->status !== 'pending') {
                return;
            }

            $allowed = self::TRANSITIONS[$order->status] ?? [];
            if (!in_array($newStatus, $allowed)) {
                return;
            }

            $order->update($updateData);

            if ($newStatus === 'processing') {
                $order->load('items');
                foreach ($order->items as $item) {
                    $updated = $this->deductStock($item->product_id, $item->size, $item->quantity);
                    if (!$updated) {
                        Log::warning('checkPayment: stock insufficient', [
                            'invoice'    => $order->invoice_number,
                            'product_id' => $item->product_id,
                            'size'       => $item->size,
                        ]);
                    }
                }
            }

            $messages = [
                'processing' => 'Pembayaran pesanan ' . $order->invoice_number . ' dikonfirmasi. Pesanan sedang diproses.',
                'cancelled'  => 'Pesanan ' . $order->invoice_number . ' dibatalkan.',
            ];
            if (isset($messages[$newStatus])) {
                Notification::send(
                    $order->user_id,
                    'order_status',
                    'Update Pesanan',
                    $messages[$newStatus],
                    route('orders.show', $order->invoice_number)
                );

                Mail::to($order->user->email)
                    ->queue(new OrderStatusMail($order, $order->getOriginal('status')));
            }

            // Notifikasi WhatsApp ke admin jika pembayaran dikonfirmasi
            if ($newStatus === 'processing') {
                $this->fonnteService->notifyAdminNewOrder(
                    $order->invoice_number,
                    $order->user->name,
                    $order->total_amount,
                );
            }
        });

        Log::info('checkPayment: order status updated', [
            'invoice' => $order->invoice_number,
            'to'      => $newStatus,
        ]);

        return response()->json(['status' => $newStatus, 'message' => 'OK']);
    }

    private function mapTransactionStatus(string $transactionStatus): ?string
    {
        return match (true) {
            in_array($transactionStatus, ['capture', 'settlement']) => 'processing',
            in_array($transactionStatus, ['cancel', 'deny', 'expire']) => 'cancelled',
            default => null,
        };
    }
}
