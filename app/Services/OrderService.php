<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    /**
     * Create an order inside a DB transaction.
     * Handles: order creation, order items, stock decrement, voucher usage.
     *
     * @throws \Exception
     */
    public function create(array $shippingData, array $items, int $total, int $discount, ?string $voucherCode): Order
    {
        return DB::transaction(function () use ($shippingData, $items, $total, $discount, $voucherCode) {
            $finalTotal = $total - $discount + $shippingData['shipping_cost'];

            // Generate unique invoice number — loop handles the astronomically rare collision
            do {
                $invoiceNumber = 'INV-' . strtoupper(Str::random(8));
            } while (Order::where('invoice_number', $invoiceNumber)->lockForUpdate()->exists());

            $order = Order::create([
                'user_id'         => auth()->id(),
                'invoice_number'  => $invoiceNumber,
                'status'          => 'pending',
                'total_amount'    => $finalTotal,
                'shipping_name'   => $shippingData['shipping_name'],
                'shipping_phone'  => $shippingData['shipping_phone'],
                'shipping_address' => $shippingData['shipping_address'],
                'payment_method'  => 'midtrans',
                'province_id'     => $shippingData['province_id'],
                'city_id'         => $shippingData['city_id'],
                'district_id'     => $shippingData['district_id'],
                'village_id'      => $shippingData['village_id'],
                'courier_service' => $shippingData['courier_service'],
                'shipping_cost'   => $shippingData['shipping_cost'],
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'size'       => $item['size'] ?? null,
                ]);

                $updated = $this->cartService->decrementStock(
                    $item['product_id'],
                    $item['size'],
                    $item['quantity']
                );

                if (!$updated) {
                    throw new \Exception("Stok {$item['product']->name} size {$item['size']} habis.");
                }
            }

            if ($voucherCode) {
                Voucher::where('code', $voucherCode)->increment('used_count');
            }

            return $order;
        });
    }
}
