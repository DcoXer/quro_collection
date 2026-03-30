<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'image',
        'type',
        'placement',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    /** Returns array of file paths regardless of storage format (plain string or JSON). */
    public function getPaths(): array
    {
        $raw = $this->attributes['image'] ?? null;
        if (!$raw) return [];
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [$raw];
    }

    /** First path — used by ImageColumn via ->state() */
    public function getFirstPath(): ?string
    {
        return $this->getPaths()[0] ?? null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForPlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }
}
