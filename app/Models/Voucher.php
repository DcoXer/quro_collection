<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\VoucherUsage;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function calculateDiscount(int $total): int
    {
        if ($this->type === 'fixed') {
            return min($this->value, $total);
        }

        $discount = (int) ($total * $this->value / 100);

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return $discount;
    }

    public function isValid(int $total, int $userId = null): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Voucher tidak aktif.'];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'Voucher sudah kadaluarsa.'];
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Voucher sudah habis digunakan.'];
        }

        if ($userId && $this->isUsedByUser($userId)) {
            return ['valid' => false, 'message' => 'Kamu sudah pernah menggunakan voucher ini.'];
        }

        if ($total < $this->min_purchase) {
            return ['valid' => false, 'message' => 'Minimum pembelian Rp ' . number_format($this->min_purchase, 0, ',', '.')];
        }

        return ['valid' => true, 'message' => 'Voucher valid.'];
    }
    
    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function isUsedByUser(int $userId): bool
    {
        return $this->usages()->where('user_id', $userId)->exists();
    }
}
