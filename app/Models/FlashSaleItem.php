<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSaleItem extends Model
{
    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'discount_type',
        'discount_value',
    ];

    public function flashSale(): BelongsTo
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFlashPriceAttribute(): float|int
    {
        $price = $this->product?->price ?? 0;

        if ($this->discount_type === 'percent') {
            return $price - ($price * $this->discount_value / 100);
        }

        return max(0, $price - $this->discount_value);
    }

    public function getSavingsAttribute(): float|int
    {
        return ($this->product?->price ?? 0) - $this->flash_price;
    }
}
