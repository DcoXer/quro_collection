<?php

namespace App\Filament\Resources\PageContent\Pages;

use App\Filament\Resources\PageContent\PageResource;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Pages are seeded — no create button needed
    }
}
