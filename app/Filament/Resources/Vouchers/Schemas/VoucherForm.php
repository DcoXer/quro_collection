<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Voucher')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash(),

                Select::make('type')
                    ->label('Tipe Diskon')
                    ->options([
                        'fixed'   => 'Nominal (Rp)',
                        'percent' => 'Persentase (%)',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('value')
                    ->label('Nilai Diskon')
                    ->required()
                    ->numeric(),

                TextInput::make('min_purchase')
                    ->label('Minimum Pembelian')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),

                TextInput::make('max_discount')
                    ->label('Maksimal Diskon (opsional)')
                    ->numeric()
                    ->prefix('Rp')
                    ->nullable(),

                TextInput::make('usage_limit')
                    ->label('Batas Penggunaan (opsional)')
                    ->numeric()
                    ->nullable(),

                DatePicker::make('expires_at')
                    ->label('Kadaluarsa (opsional)')
                    ->nullable(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }
}