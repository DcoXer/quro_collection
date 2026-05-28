<?php

namespace App\Filament\Resources\PageContent\Pages;

use App\Filament\Resources\PageContent\PageResource;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return []; // No delete — pages are permanent
    }
}
