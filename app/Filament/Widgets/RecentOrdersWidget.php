<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with('user')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->formatStateUsing(function ($state, $record) {
                        $name    = $state ?? $record->shipping_name ?? '?';
                        $words   = explode(' ', trim($name));
                        $initials = strtoupper(
                            substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : '')
                        );
                        return $initials . ' · ' . $name;
                    })
                    ->searchable(),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(function ($state) {
                        if ($state >= 1000000) {
                            return number_format($state / 1000000, 1, ',', '.') . 'jt';
                        }
                        return number_format($state / 1000, 0, ',', '.') . 'rb';
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'    => 'Pending',
                        'paid'       => 'Dibayar',
                        'processing' => 'Diproses',
                        'shipped'    => 'Dikirim',
                        'delivered'  => 'Selesai',
                        'cancelled'  => 'Batal',
                        default      => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending'    => 'warning',
                        'paid'       => 'info',
                        'processing' => 'primary',
                        'shipped'    => 'info',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    }),
            ])
            ->headerActions([
                Action::make('lihat_semua')
                    ->label('Lihat semua →')
                    ->url(fn () => OrderResource::getUrl('index'))
                    ->color('gray'),
            ])
            ->paginated(false);
    }
}
