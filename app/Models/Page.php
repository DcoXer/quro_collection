<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['slug', 'title', 'content', 'meta_title', 'meta_description'];

    public static function findBySlug(string $slug): ?static
    {
        return static::where('slug', $slug)->first();
    }
}
