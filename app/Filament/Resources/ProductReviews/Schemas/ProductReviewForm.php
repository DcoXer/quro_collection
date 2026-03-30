<?php

namespace App\Filament\Resources\ProductReviews\Schemas;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pembeli')
                    ->options(fn () => User::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('product_id')
                    ->label('Produk')
                    ->options(fn () => Product::where('is_active', true)->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('order_id')
                    ->label('Pesanan')
                    ->options(fn () => Order::orderBy('id', 'desc')->pluck('invoice_number', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '★ (1)',
                        2 => '★★ (2)',
                        3 => '★★★ (3)',
                        4 => '★★★★ (4)',
                        5 => '★★★★★ (5)',
                    ])
                    ->required(),

                Textarea::make('comment')
                    ->label('Komentar')
                    ->rows(3),

                Toggle::make('is_approved')
                    ->label('Disetujui')
                    ->default(true),
            ]);
    }
}
