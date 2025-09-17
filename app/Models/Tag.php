<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'product_count',
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'product_count' => 'integer',
    ];

    // Relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }

    // Automatically convert tag name to lowercase before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->name = strtolower($tag->name);
        });

        static::updating(function ($tag) {
            $tag->name = strtolower($tag->name);
        });
    }
}
