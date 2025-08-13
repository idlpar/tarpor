<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property string $status
 */
class Order extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity', 'total_price', 'status', 'attribution_data', 'address_id'];

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
}
