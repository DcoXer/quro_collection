<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'size', 'price', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Harga efektif: pakai harga variant, fallback ke harga base produk
    public function getEffectivePriceAttribute(): int
    {
        return $this->price ?? $this->product->price;
    }
}