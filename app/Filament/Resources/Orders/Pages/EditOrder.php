<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Mail\OrderStatusMail;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\BiteshipService;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected ?string $statusBeforeSave = null;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function beforeSave(): void
    {
        $this->statusBeforeSave = $this->record->getOriginal('status');
    }

    protected function afterSave(): void
    {
        $order          = $this->record;
        $previousStatus = $this->statusBeforeSave;

        // Auto-generate resi via Biteship saat status berubah ke shipped
        if ($previousStatus !== 'shipped' && $order->status === 'shipped') {
            try {
                $result = app(BiteshipService::class)->createOrder($order);
                $order->update([
                    'tracking_number' => $result['tracking_number'],
                    'courier'         => $result['courier'],
                ]);
            } catch (\Exception $e) {
                Log::error('Biteship auto-resi failed', [
                    'invoice' => $order->invoice_number,
                    'error'   => $e->getMessage(),
                ]);
                FilamentNotification::make()
                    ->title('Gagal generate resi otomatis')
                    ->body('Biteship error: ' . $e->getMessage() . ' — Silakan input resi manual.')
                    ->danger()
                    ->persistent()
                    ->send();
            }
        }

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

                // Kirim email ke customer
                Mail::to($order->user->email)
                    ->queue(new OrderStatusMail($order, $previousStatus));
            }
        }
    }

    private function restoreStock(int $productId, ?string $size, int $quantity): void
    {
        $hasVariant = $size && ProductVariant::where('product_id', $productId)->where('size', $size)->exists();

        if ($hasVariant) {
            ProductVariant::where('product_id', $productId)
                ->where('size', $size)
                ->increment('stock', $quantity);
        } else {
            Product::where('id', $productId)->increment('stock', $quantity);
        }
    }
}
