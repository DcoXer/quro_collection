<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Pesanan')
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('_overview')
                            ->label('')
                            ->columnSpanFull()
                            ->content(function ($record) {
                                if (!$record) return '-';

                                $statusMap = [
                                    'pending'    => ['label' => 'Pending',    'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.1)'],
                                    'paid'       => ['label' => 'Paid',       'color' => '#3b82f6', 'bg' => 'rgba(59,130,246,0.1)'],
                                    'processing' => ['label' => 'Processing', 'color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.1)'],
                                    'shipped'    => ['label' => 'Shipped',    'color' => '#06b6d4', 'bg' => 'rgba(6,182,212,0.1)'],
                                    'delivered'  => ['label' => 'Delivered',  'color' => '#10b981', 'bg' => 'rgba(16,185,129,0.1)'],
                                    'cancelled'  => ['label' => 'Cancelled',  'color' => '#ef4444', 'bg' => 'rgba(239,68,68,0.1)'],
                                ];
                                $s       = $statusMap[$record->status] ?? ['label' => $record->status, 'color' => '#6b7280', 'bg' => 'rgba(107,114,128,0.1)'];
                                $badge   = "<span style='display:inline-flex;align-items:center;padding:3px 10px;border-radius:9999px;font-size:0.7rem;font-weight:700;letter-spacing:0.04em;color:{$s['color']};background:{$s['bg']};'>{$s['label']}</span>";
                                $invoice = e($record->invoice_number);
                                $date    = $record->created_at->format('d M Y, H:i');
                                $total   = 'Rp ' . number_format($record->total_amount, 0, ',', '.');
                                $ongkir  = 'Rp ' . number_format($record->shipping_cost ?? 0, 0, ',', '.');
                                $kurir   = strtoupper($record->courier ?? '-') . ($record->courier_service ? ' · ' . $record->courier_service : '');

                                return new \Illuminate\Support\HtmlString("
                                    <div style='display:flex;flex-wrap:wrap;gap:16px;align-items:stretch;'>

                                        <div style='flex:1;min-width:180px;'>
                                            <div style='font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:6px;'>No. Invoice</div>
                                            <div style='font-size:1.05rem;font-weight:800;letter-spacing:-0.02em;color:inherit;'>{$invoice}</div>
                                            <div style='font-size:0.75rem;color:#9ca3af;margin-top:3px;'>{$date}</div>
                                        </div>

                                        <div style='width:1px;background:rgba(0,0,0,0.07);flex-shrink:0;'></div>

                                        <div style='flex:1;min-width:120px;'>
                                            <div style='font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:6px;'>Status</div>
                                            {$badge}
                                        </div>

                                        <div style='width:1px;background:rgba(0,0,0,0.07);flex-shrink:0;'></div>

                                        <div style='flex:1;min-width:140px;'>
                                            <div style='font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:6px;'>Total Pembayaran</div>
                                            <div style='font-size:1.05rem;font-weight:800;color:inherit;'>{$total}</div>
                                            <div style='font-size:0.75rem;color:#9ca3af;margin-top:3px;'>Ongkir: {$ongkir}</div>
                                        </div>

                                        <div style='width:1px;background:rgba(0,0,0,0.07);flex-shrink:0;'></div>

                                        <div style='flex:1;min-width:120px;'>
                                            <div style='font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:6px;'>Kurir</div>
                                            <div style='font-size:0.875rem;font-weight:600;color:inherit;'>{$kurir}</div>
                                        </div>

                                    </div>
                                ");
                            }),
                    ]),

                Section::make('Item Pesanan')
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('items')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record) return '-';

                                $items = $record->items()->with('product')->get();

                                if ($items->isEmpty()) return 'Tidak ada item.';

                                $rows = $items->map(function ($item) {
                                    $name     = e($item->product?->name ?? '(produk dihapus)');
                                    $size     = $item->size ? ' · ' . e($item->size) : '';
                                    $qty      = $item->quantity;
                                    $price    = 'Rp ' . number_format($item->price, 0, ',', '.');
                                    $subtotal = 'Rp ' . number_format($item->price * $item->quantity, 0, ',', '.');
                                    $imgUrl   = $item->product?->image
                                        ? \Illuminate\Support\Facades\Storage::url($item->product->image)
                                        : null;
                                    $img      = $imgUrl
                                        ? "<img src=\"{$imgUrl}\" style=\"width:48px;height:48px;object-fit:cover;border-radius:8px;flex-shrink:0;\">"
                                        : "<div style=\"width:48px;height:48px;border-radius:8px;background:rgba(0,0,0,0.08);flex-shrink:0;\"></div>";

                                    return <<<HTML
                                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid rgba(0,0,0,0.06);gap:12px;">
                                            <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                                                {$img}
                                                <div style="min-width:0;">
                                                    <div style="font-weight:600;font-size:0.875rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{$name}</div>
                                                    <div style="font-size:0.75rem;color:#9ca3af;margin-top:2px;">Size{$size} &nbsp;·&nbsp; {$qty} pcs &nbsp;·&nbsp; {$price}</div>
                                                </div>
                                            </div>
                                            <div style="font-weight:700;font-size:0.875rem;white-space:nowrap;">{$subtotal}</div>
                                        </div>
                                    HTML;
                                })->implode('');

                                $total = 'Rp ' . number_format($record->total_amount, 0, ',', '.');

                                return new \Illuminate\Support\HtmlString(
                                    '<div style="margin:-4px 0;">' . $rows . '</div>'
                                    . '<div style="display:flex;justify-content:space-between;align-items:center;padding-top:10px;margin-top:4px;border-top:2px solid rgba(0,0,0,0.12);">'
                                    . '<span style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b7280;">Total</span>'
                                    . '<span style="font-weight:700;font-size:1rem;">' . $total . '</span>'
                                    . '</div>'
                                );
                            }),
                    ]),

                Section::make('Data Pengiriman')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('shipping_name')
                            ->label('Nama Penerima')
                            ->required(),

                        TextInput::make('shipping_phone')
                            ->label('No. HP')
                            ->tel()
                            ->required(),

                        Textarea::make('shipping_address')
                            ->label('Alamat')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('courier')
                            ->label('Kurir')
                            ->options([
                                'jne' => 'JNE',
                                'jnt' => 'J&T Express',
                            ])
                            ->native(false)
                            ->nullable(),

                        TextInput::make('courier_service')
                            ->label('Layanan')
                            ->placeholder('cth: REG, YES, Express')
                            ->nullable(),
                    ]),

                Section::make('Nomor Resi')
                    ->columnSpanFull()
                    ->description(fn ($record) => $record?->tracking_number
                        ? 'Resi sudah tersedia — pesanan siap dicetak.'
                        : 'Belum ada resi. Input manual setelah mendapatkan nomor dari ekspedisi.')
                    ->icon(Heroicon::OutlinedTruck)
                    ->columns(2)
                    ->schema([
                        TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->placeholder('Masukkan nomor resi dari ekspedisi...')
                            ->prefixIcon(Heroicon::OutlinedQrCode)
                            ->suffixActions([
                                Action::make('copy_resi')
                                    ->icon(Heroicon::OutlinedClipboard)
                                    ->tooltip('Salin nomor resi')
                                    ->action(function ($state, $livewire) {
                                        $livewire->dispatch('copy-to-clipboard', text: $state);
                                    })
                                    ->visible(fn ($state) => filled($state)),
                            ])
                            ->helperText(fn ($record) => $record?->tracking_number
                                ? 'Resi aktif. Ubah jika ada koreksi dari ekspedisi.'
                                : 'Setelah diisi dan disimpan, pesanan otomatis muncul di halaman Cetak Resi.')
                            ->nullable()
                            ->columnSpanFull(),

                        Placeholder::make('resi_status')
                            ->label('Status Resi')
                            ->content(fn ($record) => $record?->tracking_number
                                ? '✓ Resi tersedia: ' . $record->tracking_number
                                : '— Belum ada resi')
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}
