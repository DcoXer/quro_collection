<?php

namespace App\Filament\Resources\PageContent\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('slug')
                    ->label('Slug / URL')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()->label('Edit'),
            ])
            ->defaultSort('slug')
            ->paginated(false);
    }
}
