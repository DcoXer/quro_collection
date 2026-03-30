<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'invoice_number', 'status',
        'total_amount', 'shipping_name', 'shipping_phone',
        'shipping_address', 'payment_method', 'payment_token',
        'courier', 'tracking_number',
        'province_id', 'city_id', 'district_id', 'village_id',
        'courier_service', 'shipping_cost',
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