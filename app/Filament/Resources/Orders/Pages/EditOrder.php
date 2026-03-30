<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        $order          = $this->record;
        $previousStatus = $order->getOriginal('status');

        // Restore stock only when transitioning INTO cancelled for the first time
        if ($previousStatus !== 'cancelled' && $order->status === 'cancelled') {
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    $this->restoreStock($item->product_id, $item->size, $item->quantity);
                }
            });
        }

        // Kirim notifikasi in-app ke user jika status berubah
        if ($previousStatus !== $order->status) {
            $messages = [
                'paid'       => 'Pembayaran pesanan ' . $order->invoice_number . ' telah dikonfirmasi.',
                'processing' => 'Pesanan ' . $order->invoice_number . ' sedang diproses.',
                'shipped'    => 'Pesanan ' . $order->invoice_number . ' sudah dikirim. Lacak paketmu sekarang.',
                'delivered'  => 'Pesanan ' . $order->invoice_number . ' telah diterima. Jangan lupa beri ulasan!',
                'cancelled'  => 'Pesanan ' . $order->invoice_number . ' telah dibatalkan.',
            ];

            if (isset($messages[$order->status])) {
                Notification::send(
                    $order->user_id,
                    'order_status',
                    'Update Pesanan',
                    $messages[$order->status],
                    route('orders.show', $order->invoice_number)
                );
            }
        }
    }

    private function restoreStock(int $productId, ?string $size, int $quantity): void
    {
        if (!$size || in_array($size, CartService::BASE_SIZES)) {
            Product::where('id', $productId)->increment('stock', $quantity);
        } else {
            ProductVariant::where('product_id', $productId)
                ->where('size', $size)
                ->increment('stock', $quantity);
        }
    }
}
