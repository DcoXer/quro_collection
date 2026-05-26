<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'invoice_number', 'status',
        'total_amount', 'discount_amount', 'voucher_code',
        'shipping_name', 'shipping_phone',
        'shipping_address', 'payment_method', 'payment_token',
        'courier', 'tracking_number', 'shipped_at',
        'province_id', 'city_id', 'city_name',
        'district_id', 'district_name', 'village_id',
        'courier_service', 'shipping_cost',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}