<?php

namespace App\Filament\Resources\Vouchers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VouchersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('min_purchase')
                    ->label('Min. Pembelian')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('max_discount')
                    ->label('Maks. Diskon')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('usage')
                    ->label('Penggunaan')
                    ->state(fn ($record) => $record->usage_limit
                        ? "{$record->used_count} / {$record->usage_limit}"
                        : "{$record->used_count} / ∞")
                    ->sortable(false),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('expires_at')
                    ->label('Kedaluwarsa')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at && $record->expires_at->isPast()
                        ? 'danger'
                        : null),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'percent' => 'Persentase',
                        'fixed'   => 'Nominal',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
