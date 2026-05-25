<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\Widget;

class LowStockWidget extends Widget
{
    protected static ?string $heading = 'Stok Menipis & Habis';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected string $view = 'filament.widgets.low-stock-widget';

    public function getViewData(): array
    {
        // Jika produk punya variants, stok asli ada di product_variants.
        // Kalau tidak ada variants, pakai products.stock.
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->selectRaw("
                products.*,
                (CASE
                    WHEN EXISTS (SELECT 1 FROM product_variants WHERE product_id = products.id)
                    THEN (SELECT COALESCE(SUM(stock), 0) FROM product_variants WHERE product_id = products.id)
                    ELSE products.stock
                END) as effective_stock
            ")
            ->havingRaw('effective_stock <= 10')
            ->orderByRaw('effective_stock ASC')
            ->limit(12)
            ->get();

        return ['products' => $products];
    }
}
