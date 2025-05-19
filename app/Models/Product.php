<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'price', 'sale_price', 'cost_price', 'sku', 'short_description',
        'description', 'stock_quantity', 'stock_status', 'brand_id', 'status',
        'attributes', 'images', 'thumbnail', 'weight', 'length', 'width', 'height',
        'product_collections', 'labels', 'related_products', 'is_featured', 'barcode',
        'discount', 'inventory_tracking', 'views',
    ];

    protected $casts = [
        'attributes' => 'array',
        'images' => 'array',
        'product_collections' => 'array',
        'labels' => 'array',
        'related_products' => 'array',
        'is_featured' => 'boolean',
        'inventory_tracking' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
//    public function registerMediaCollections(): void
//    {
//        $this->addMediaCollection('product_images')->useDisk('public');
//    }
//
//    public function registerMediaConversions(Media $media = null): void
//    {
//        $this->addMediaConversion('thumbnail')
//            ->width(150)
//            ->height(150)
//            ->sharpen(10);
//
//        $this->addMediaConversion('medium')
//            ->width(300)
//            ->height(300);
//
//        $this->addMediaConversion('medium_large')
//            ->width(768)
//            ->height(null); // Keeps aspect ratio
//
//        $this->addMediaConversion('large')
//            ->width(1024)
//            ->height(1024);
//    }
//}
