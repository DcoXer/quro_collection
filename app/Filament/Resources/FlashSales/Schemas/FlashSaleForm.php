<?php

namespace App\Filament\Resources\FlashSales\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
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
                    ->required()
                    ->live(),

                DateTimePicker::make('ends_at')
                    ->label('Berakhir')
                    ->required()
                    ->after('starts_at')
                    ->validationMessages(['after' => 'Waktu berakhir harus setelah waktu mulai.']),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),

                Repeater::make('items')
                    ->relationship('items')
                    ->label('Produk Flash Sale')
                    ->distinct()
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->options(
                                Product::where('is_active', true)->pluck('name', 'id')
                            )
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(1),

                        Select::make('discount_type')
                            ->label('Tipe Diskon')
                            ->options([
                                'percent' => 'Persentase (%)',
                                'fixed'   => 'Nominal (Rp)',
                            ])
                            ->required()
                            ->native(false)
                            ->columnSpan(1),

                        TextInput::make('discount_value')
                            ->label('Nilai Diskon')
                            ->numeric()
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns([
                        'sm' => 1,
                        'md' => 3,
                    ]),
            ]);
    }
}
