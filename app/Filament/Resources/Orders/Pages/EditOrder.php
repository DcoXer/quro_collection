<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Mail\OrderStatusMail;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\BiteshipService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
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
        return [

            // processing → shipped
            Action::make('kirim')
                ->label('Tandai Dikirim')
                ->icon('heroicon-o-truck')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Tandai pesanan sebagai dikirim?')
                ->modalDescription('Resi akan di-generate otomatis via Biteship. Jika gagal, Anda bisa input resi manual di form.')
                ->modalSubmitActionLabel('Tandai Dikirim')
                ->visible(fn () => $this->record->status === 'processing')
                ->action(fn () => $this->changeStatus('shipped')),

            // shipped → delivered
            Action::make('selesai')
                ->label('Tandai Selesai')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Tandai pesanan selesai?')
                ->modalDescription('Status akan berubah ke "Delivered". Pastikan customer sudah menerima paket.')
                ->modalSubmitActionLabel('Ya, Selesai')
                ->visible(fn () => $this->record->status === 'shipped')
                ->action(fn () => $this->changeStatus('delivered')),

            // cancel
            Action::make('batalkan')
                ->label('Batalkan Pesanan')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    Textarea::make('cancel_reason')
                        ->label('Alasan pembatalan')
                        ->placeholder('Opsional...')
                        ->rows(2),
                ])
                ->modalHeading('Batalkan pesanan ini?')
                ->modalSubmitActionLabel('Ya, Batalkan')
                ->visible(fn () => in_array($this->record->status, ['pending', 'paid', 'processing']))
                ->action(fn () => $this->changeStatus('cancelled')),

        ];
    }

    private function changeStatus(string $status): void
    {
        $previousStatus = $this->record->status;

        $this->record->update([
            'status'     => $status,
            'shipped_at' => $status === 'shipped' ? now() : $this->record->shipped_at,
        ]);
        $this->record->refresh();

        // Restore stock on cancel — hanya jika stok sudah dikurangi.
        // Stok dikurangi saat webhook mengonfirmasi pembayaran (status → processing).
        // Jika cancel dari 'pending', stok belum dikurangi — jangan di-increment.
        $stockWasDeducted = in_array($previousStatus, ['processing', 'shipped', 'delivered']);
        if ($status === 'cancelled' && $stockWasDeducted) {
            DB::transaction(function () {
                foreach ($this->record->items as $item) {
                    $this->restoreStock($item->product_id, $item->size, $item->quantity);
                }
            });
        }

        // Auto-resi via Biteship saat shipped
        if ($previousStatus !== 'shipped' && $status === 'shipped') {
            try {
                // Eager load product agar buildItems() tidak N+1 query
                $this->record->load('items.product');
                $result = app(BiteshipService::class)->createOrder($this->record);
                $this->record->update([
                    'tracking_number' => $result['tracking_number'],
                    'courier'         => $result['courier'],
                ]);
            } catch (\Exception $e) {
                Log::error('Biteship auto-resi failed', [
                    'invoice' => $this->record->invoice_number,
                    'error'   => $e->getMessage(),
                ]);
                FilamentNotification::make()
                    ->title('Resi belum ter-generate otomatis')
                    ->body('Silakan input nomor resi manual di form di bawah.')
                    ->warning()
                    ->persistent()
                    ->send();
            }
        }

        // Notifikasi in-app + email
        $messages = [
            'processing' => 'Pesanan ' . $this->record->invoice_number . ' sedang diproses.',
            'shipped'    => 'Pesanan ' . $this->record->invoice_number . ' sudah dikirim. Lacak paketmu sekarang.',
            'delivered'  => 'Pesanan ' . $this->record->invoice_number . ' telah diterima. Jangan lupa beri ulasan!',
            'cancelled'  => 'Pesanan ' . $this->record->invoice_number . ' telah dibatalkan.',
        ];

        if (isset($messages[$status])) {
            Notification::send(
                $this->record->user_id,
                'order_status',
                'Update Pesanan',
                $messages[$status],
                route('orders.show', $this->record->invoice_number)
            );

            Mail::to($this->record->user->email)
                ->queue(new OrderStatusMail($this->record, $previousStatus));
        }

        FilamentNotification::make()
            ->title('Status diperbarui')
            ->body('Pesanan ' . $this->record->invoice_number . ' → ' . ucfirst($status))
            ->success()
            ->send();

        $this->refreshFormData(['status']);
    }

    protected function beforeSave(): void
    {
        $this->statusBeforeSave = $this->record->getOriginal('status');
    }

    protected function afterSave(): void
    {
        // Status hanya boleh diubah via action buttons, bukan form save
        // Kembalikan status ke nilai sebelumnya jika ada perubahan via form
        if ($this->record->status !== $this->statusBeforeSave) {
            $this->record->update(['status' => $this->statusBeforeSave]);
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
