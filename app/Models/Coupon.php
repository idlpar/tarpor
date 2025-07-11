<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_amount', 'expires_at', 'usage_limit', 'times_used', 'max_discount_amount',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'max_discount_amount' => 'decimal:2',
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isUsedUp()
    {
        return $this->usage_limit && $this->times_used >= $this->usage_limit;
    }

    public function getDiscount($total)
    {
        if ($this->type === 'fixed') {
            return $this->value;
        } elseif ($this->type === 'percentage') {
            $discount = ($this->value / 100) * $total;
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                return $this->max_discount_amount;
            }
            return $discount;
        }
        return 0;
    }
}