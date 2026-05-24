<?php

namespace App\Filament\Resources\FeaturedProducts\Pages;

use App\Filament\Resources\FeaturedProducts\FeaturedProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeaturedProducts extends ListRecords
{
    protected static string $resource = FeaturedProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
