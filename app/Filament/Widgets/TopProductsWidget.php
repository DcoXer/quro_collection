<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Produk Terlaris';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->select('products.*')
                    ->selectSub(
                        DB::table('order_items')->selectRaw('SUM(quantity)')->whereColumn('product_id', 'products.id'),
                        'total_sold'
                    )
                    ->selectSub(
                        DB::table('order_items')->selectRaw('SUM(price * quantity)')->whereColumn('product_id', 'products.id'),
                        'total_revenue'
                    )
                    ->selectSub(
                        DB::table('order_items')->selectRaw('COUNT(DISTINCT order_id)')->whereColumn('product_id', 'products.id'),
                        'total_orders'
                    )
                    ->whereExists(function ($q) {
                        $q->from('order_items')->whereColumn('product_id', 'products.id');
                    })
                    ->orderByDesc('total_sold')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable(),

                TextColumn::make('total_sold')
                    ->label('Terjual')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_orders')
                    ->label('Pesanan')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_revenue')
                    ->label('Revenue')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
