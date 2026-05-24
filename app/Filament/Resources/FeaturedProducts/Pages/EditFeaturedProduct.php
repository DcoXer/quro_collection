<?php

namespace App\Filament\Resources\FeaturedProducts\Pages;

use App\Filament\Resources\FeaturedProducts\FeaturedProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeaturedProduct extends EditRecord
{
    protected static string $resource = FeaturedProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
