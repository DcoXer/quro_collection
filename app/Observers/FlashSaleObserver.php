<?php

namespace App\Observers;

use App\Models\FlashSale;
use Illuminate\Support\Facades\Cache;

class FlashSaleObserver
{
    public function saved(FlashSale $flashSale): void
    {
        $this->bustCache();
    }

    public function deleted(FlashSale $flashSale): void
    {
        $this->bustCache();
    }

    private function bustCache(): void
    {
        Cache::forget('active_flash_sale');
        Cache::forget('active_flash_sale_items');
        Cache::forget('active_flash_sale_with_items');
    }
}
