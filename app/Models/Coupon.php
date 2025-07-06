<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_amount', 'expires_at', 'usage_limit', 'used',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isUsedUp()
    {
        return $this->usage_limit && $this->used >= $this->usage_limit;
    }

    public function getDiscount($total)
    {
        if ($this->type === 'fixed') {
            return $this->value;
        } elseif ($this->type === 'percent') {
            return ($this->value / 100) * $total;
        }
        return 0;
    }
}