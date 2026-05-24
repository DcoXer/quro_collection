<?php

namespace App\Filament\Resources\FeaturedProducts\Pages;

use App\Filament\Resources\FeaturedProducts\FeaturedProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeaturedProduct extends CreateRecord
{
    protected static string $resource = FeaturedProductResource::class;
}
