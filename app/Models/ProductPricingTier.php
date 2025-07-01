<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPricingTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'variant_id', 'min_quantity', 'max_quantity', 'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}

class ProductSpecialOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'variant_id', 'name', 'discount_amount', 'discount_type',
        'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getIsActiveAttribute($value)
    {
        return $value && now()->between($this->start_date, $this->end_date);
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->product->price * (1 - ($this->discount_amount / 100));
        }
        return max(0, $this->product->price - $this->discount_amount);
    }
}
