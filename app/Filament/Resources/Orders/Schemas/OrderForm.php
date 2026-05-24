<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Pesanan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('Invoice')
                            ->disabled()
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending'    => 'Pending',
                                'paid'       => 'Paid',
                                'processing' => 'Processing',
                                'shipped'    => 'Shipped',
                                'delivered'  => 'Delivered',
                                'cancelled'  => 'Cancelled',
                            ])
                            ->required()
                            ->native(false),

                        Placeholder::make('total_amount')
                            ->label('Total')
                            ->content(fn ($record) => $record
                                ? 'Rp ' . number_format($record->total_amount, 0, ',', '.')
                                : '-'),

                        Placeholder::make('shipping_cost')
                            ->label('Ongkos Kirim')
                            ->content(fn ($record) => $record
                                ? 'Rp ' . number_format($record->shipping_cost ?? 0, 0, ',', '.')
                                : '-'),

                        Placeholder::make('courier_service')
                            ->label('Layanan Kurir')
                            ->content(fn ($record) => $record?->courier_service ?? '-'),

                        TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->disabled(),
                    ]),

                Section::make('Item Pesanan')
                    ->schema([
                        Placeholder::make('items')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record) return '-';

                                $items = $record->items()->with('product')->get();

                                if ($items->isEmpty()) return 'Tidak ada item.';

                                $rows = $items->map(function ($item) {
                                    $productName = $item->product?->name ?? '(produk dihapus)';
                                    $subtotal    = 'Rp ' . number_format($item->price * $item->quantity, 0, ',', '.');
                                    return "{$productName} — Size: {$item->size} × {$item->quantity} @ Rp " .
                                           number_format($item->price, 0, ',', '.') . " = {$subtotal}";
                                })->implode("\n");

                                return $rows;
                            }),
                    ]),

                Section::make('Data Pengiriman')
                    ->columns(2)
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
                                'jne'      => 'JNE',
                                'jnt'      => 'J&T Express',
                                'sicepat'  => 'SiCepat',
                                'anteraja' => 'AnterAja',
                                'ninja'    => 'Ninja Express',
                                'pos'      => 'POS Indonesia',
                                'tiki'     => 'TIKI',
                                'lion'     => 'Lion Parcel',
                                'sap'      => 'SAP Express',
                                'idl'      => 'IDL Cargo',
                            ])
                            ->native(false)
                            ->nullable(),

                        TextInput::make('tracking_number')
                            ->label('Nomor Resi')
                            ->helperText('Diisi otomatis oleh Biteship saat status diubah ke Shipped.')
                            ->nullable(),
                    ]),

            ]);
    }
}
