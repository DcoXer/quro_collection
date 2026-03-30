<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockWidget extends BaseWidget
{
    protected static ?string $heading = 'Produk Stok Menipis / Habis';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('is_active', true)
                    ->where('stock', '<=', 10)
                    ->orderBy('stock')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Kategori'),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state === 0 => 'danger',
                        $state <= 5  => 'warning',
                        default      => 'gray',
                    })
                    ->sortable(),
            ])
            ->emptyStateHeading('Semua stok aman')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated(false);
    }
}
