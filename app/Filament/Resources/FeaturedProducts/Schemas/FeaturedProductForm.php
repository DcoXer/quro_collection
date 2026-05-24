<?php

namespace App\Filament\Resources\FeaturedProducts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FeaturedProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Produk')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->helperText('Pilih produk yang akan ditampilkan di hero halaman belanja.'),

            Toggle::make('is_active')
                ->label('Tampilkan di Hero')
                ->default(true)
                ->helperText('Hanya satu produk yang aktif yang akan ditampilkan.'),
        ]);
    }
}
