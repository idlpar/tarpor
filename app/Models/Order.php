<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $user_id
 * @property string $status
 */
class Order extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->short_id = bin2hex(random_bytes(8));
        });
    }

    protected $fillable = ['user_id', 'short_id', 'total_price', 'status', 'attribution_data', 'address_id', 'delivery_charge', 'coupon_discount', 'reward_discount'];

    protected $casts = [
        'attribution_data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
