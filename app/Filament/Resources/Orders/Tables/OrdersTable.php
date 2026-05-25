<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Invoice disalin!'),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->shipping_phone ?? ''),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'    => 'Pending',
                        'paid'       => 'Paid',
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                        default      => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending'    => 'warning',
                        'paid'       => 'info',
                        'processing' => 'primary',
                        'shipped'    => 'indigo',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                        default      => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('courier')
                    ->label('Kurir')
                    ->formatStateUsing(fn ($state, $record) =>
                        strtoupper($state ?? '-') . ($record->tracking_number ? '' : '')
                    )
                    ->description(fn ($record) => $record->tracking_number ?? 'Belum ada resi')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->weight('semibold'),

TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->description(fn ($record) => $record->created_at->format('H:i'))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'    => 'Pending',
                        'paid'       => 'Paid',
                        'processing' => 'Processing',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                    ])
                    ->label('Status'),
            ])
            ->recordActions([
                EditAction::make()->label('Kelola'),
            ])
            ->striped()
            ->emptyStateHeading('Belum ada pesanan')
            ->emptyStateDescription('Pesanan dari customer akan muncul di sini.');
    }
}
