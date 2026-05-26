<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    /**
     * Create an order inside a DB transaction.
     * Handles: order creation, order items, voucher usage.
     * Stock is NOT decremented here — only after payment confirmed via Midtrans webhook.
     *
     * @throws \Exception
     */
    public function create(array $shippingData, array $items, int $total, int $discount, ?string $voucherCode): Order
    {
        return DB::transaction(function () use ($shippingData, $items, $total, $discount, $voucherCode) {
            $finalTotal = max(1000, $total - $discount + $shippingData['shipping_cost']);

            // Generate unique invoice number — loop handles the astronomically rare collision
            do {
                $invoiceNumber = 'INV-' . strtoupper(Str::random(8));
            } while (Order::where('invoice_number', $invoiceNumber)->lockForUpdate()->exists());

            $order = Order::create([
                'user_id'         => auth()->id(),
                'invoice_number'  => $invoiceNumber,
                'status'          => 'pending',
                'total_amount'    => $finalTotal,
                'discount_amount' => $discount,
                'voucher_code'    => $voucherCode,
                'shipping_name'   => $shippingData['shipping_name'],
                'shipping_phone'  => $shippingData['shipping_phone'],
                'shipping_address' => $shippingData['shipping_address'],
                'payment_method'  => 'midtrans',
                'province_id'   => $shippingData['province_id'],
                'city_id'       => $shippingData['city_id'],
                'city_name'     => $shippingData['city_name'] ?? null,
                'district_id'   => $shippingData['district_id'],
                'district_name' => $shippingData['district_name'] ?? null,
                'village_id'    => $shippingData['village_id'],
                'courier'       => $shippingData['courier'] ?? null,
                'courier_service' => $shippingData['courier_service'],
                'shipping_cost' => $shippingData['shipping_cost'],
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'size'       => $item['size'] ?? null,
                ]);
            }

            if ($voucherCode) {
                $voucher = Voucher::where('code', $voucherCode)->lockForUpdate()->first();
                if ($voucher) {
                    $voucher->increment('used_count');
                    VoucherUsage::create([
                        'voucher_id' => $voucher->id,
                        'user_id'    => auth()->id(),
                    ]);
                }
            }

            return $order;
        });
    }
}
