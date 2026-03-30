<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    public function createSnapToken(Order $order): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $order->invoice_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $order->shipping_name,
                'phone'      => $order->shipping_phone,
                'address'    => $order->shipping_address,
                'email'      => $order->user->email,
            ],
            'item_details' => $this->buildItemDetails($order),
        ];

        return Snap::getSnapToken($params);
    }

    private function buildItemDetails(Order $order): array
    {
        // Line items produk
        $items = $order->items->map(fn($item) => [
            'id'       => (string) $item->product_id,
            'price'    => (int) $item->price,
            'quantity' => (int) $item->quantity,
            'name'     => mb_substr(
                ($item->product->name ?? 'Produk') . ' (' . ($item->size ?? '-') . ')',
                0, 50  // Midtrans max 50 chars untuk name
            ),
        ])->toArray();

        // Tambah ongkir sebagai line item tersendiri
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim (' . ($order->courier_service ?? 'Kurir') . ')',
            ];
        }

        // Hitung diskon: subtotal_produk + ongkir - total_amount
        $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
        $discount = $subtotal + $order->shipping_cost - $order->total_amount;

        if ($discount > 0) {
            $items[] = [
                'id'       => 'DISCOUNT',
                'price'    => -(int) $discount,  // nilai negatif
                'quantity' => 1,
                'name'     => 'Diskon Voucher',
            ];
        }

        return $items;
    }

    /**
     * Recheck transaction status directly from Midtrans API.
     * Useful when a webhook was never delivered and the order is stuck in pending.
     *
     * @throws \Exception
     */
    public function checkTransactionStatus(string $invoiceNumber): array
    {
        $serverKey  = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production');
        $baseUrl    = $isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';

        $response = Http::withBasicAuth($serverKey, '')
            ->get("{$baseUrl}/{$invoiceNumber}/status");

        if ($response->failed()) {
            throw new \Exception("Midtrans status check failed: {$response->status()}");
        }

        return $response->json();
    }

    /**
     * Validate and parse a Midtrans webhook notification.
     * Returns the Notification object, or throws \Exception if signature is invalid.
     *
     * @throws \Exception
     */
    public function parseWebhook(): Notification
    {
        $notification = new Notification();
        $serverKey    = config('services.midtrans.server_key');

        $expected = hash('sha512',
            $notification->order_id .
            $notification->status_code .
            $notification->gross_amount .
            $serverKey
        );

        if ($expected !== $notification->signature_key) {
            throw new \Exception('Invalid signature');
        }

        return $notification;
    }
}
