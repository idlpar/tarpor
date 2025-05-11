<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'division',
        'district',
        'upazila',
        'union',
        'street_address',
        'postal_code',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
