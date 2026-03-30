<?php

namespace App\Filament\Resources\ProductReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('user.name')
                    ->label('Pembeli')
                    ->searchable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->sortable(),

                TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(60)
                    ->placeholder('—'),

                IconColumn::make('is_approved')
                    ->label('Disetujui')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        '5' => '★★★★★',
                        '4' => '★★★★',
                        '3' => '★★★',
                        '2' => '★★',
                        '1' => '★',
                    ]),
                SelectFilter::make('is_approved')
                    ->label('Status')
                    ->options([
                        '1' => 'Disetujui',
                        '0' => 'Pending',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
