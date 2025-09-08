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
            $order->short_id = self::generateOrderNumber();
        });
    }

    public static function generateOrderNumber()
    {
        $timestamp = now()->utc()->format('ymdHisu');
        $randomPart = mt_rand(100, 999);
        $orderNumber = $timestamp . $randomPart;

        // Ensure the order number is unique
        while (self::where('short_id', $orderNumber)->exists()) {
            $randomPart = mt_rand(100, 999);
            $orderNumber = $timestamp . $randomPart;
        }

        return $orderNumber;
    }

    protected $fillable = ['user_id', 'short_id', 'total_price', 'status', 'attribution_data', 'address_id', 'delivery_charge', 'coupon_discount', 'reward_discount', 'shipping_method_id', 'coupon_id'];

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

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
