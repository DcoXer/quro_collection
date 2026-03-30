<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusMail;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransController extends Controller
{
    // Valid status transitions — order can only move forward, never back
    const TRANSITIONS = [
        'pending'   => ['paid', 'cancelled'],
        'paid'      => [],
        'cancelled' => [],
        'shipped'   => [],
        'completed' => [],
    ];

    public function __construct(private MidtransService $midtransService) {}

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

        if ($newStatus === 'paid') {
            $updateData['payment_method'] = $notification->payment_type;
        }

        DB::transaction(function () use ($order, $updateData, $newStatus) {
            $order->update($updateData);

            // Kurangi stok saat pembayaran berhasil
            if ($newStatus === 'paid') {
                $order->load('items');
                foreach ($order->items as $item) {
                    $this->deductStock($item->product_id, $item->size, $item->quantity);
                }
            }

            // Kirim notifikasi in-app ke user
            $messages = [
                'paid'      => 'Pembayaran pesanan ' . $order->invoice_number . ' dikonfirmasi.',
                'cancelled' => 'Pesanan ' . $order->invoice_number . ' dibatalkan.',
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
        });

        Log::info('Midtrans webhook: order status updated', [
            'invoice' => $order->invoice_number,
            'to'      => $newStatus,
        ]);

        return response()->json(['message' => 'OK']);
    }

    private function deductStock(int $productId, ?string $size, int $quantity): void
    {
        if (!$size || in_array($size, CartService::BASE_SIZES)) {
            Product::where('id', $productId)->decrement('stock', $quantity);
        } else {
            ProductVariant::where('product_id', $productId)
                ->where('size', $size)
                ->decrement('stock', $quantity);
        }
    }

    private function mapTransactionStatus(string $transactionStatus): ?string
    {
        return match (true) {
            in_array($transactionStatus, ['capture', 'settlement']) => 'paid',
            in_array($transactionStatus, ['cancel', 'deny', 'expire']) => 'cancelled',
            default => null,
        };
    }
}
