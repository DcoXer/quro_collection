<?php

namespace App\Observers;

use App\Models\FlashSaleItem;
use Illuminate\Support\Facades\Cache;

class FlashSaleItemObserver
{
    public function saved(FlashSaleItem $item): void
    {
        $this->bustCache();
    }

    public function deleted(FlashSaleItem $item): void
    {
        $this->bustCache();
    }

    private function bustCache(): void
    {
        Cache::forget('active_flash_sale_items');
        Cache::forget('active_flash_sale_with_items');
    }
}
