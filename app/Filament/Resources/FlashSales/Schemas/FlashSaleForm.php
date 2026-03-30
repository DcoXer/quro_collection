<?php

namespace App\Filament\Resources\FlashSales\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FlashSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),

                DateTimePicker::make('starts_at')
                    ->label('Mulai')
                    ->required(),

                DateTimePicker::make('ends_at')
                    ->label('Berakhir')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),

                Repeater::make('items')
                    ->relationship('items')
                    ->label('Produk Flash Sale')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->required()
                            ->searchable()
                            ->options(
                                Product::where('is_active', true)->pluck('name', 'id')
                            ),

                        Select::make('discount_type')
                            ->label('Tipe Diskon')
                            ->options([
                                'percent' => 'Persentase (%)',
                                'fixed'   => 'Nominal (Rp)',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('discount_value')
                            ->label('Nilai Diskon')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
