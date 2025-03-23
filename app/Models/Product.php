<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    // Define fillable fields
    protected $fillable = [
        'sku', 'name', 'slug', 'description', 'short_description', 'price', 'sale_price',
        'cost_price', 'stock_quantity', 'stock_status', 'barcode', 'tags',  'discount',  'related_products', 'is_featured',  'inventory_tracking',
        'length', 'width', 'height', 'weight', 'brand_id', 'category_id', 'attributes', 'images', 'thumbnail',
        'status', 'deleted_at',
    ];

    // Cast attributes to appropriate types
    protected $casts = [
        'attributes' => 'array', // Cast attributes to array
        'images' => 'array',     // Cast images to array
        'tags' => 'array',       // Cast tags to array
        'related_products' => 'array', // Cast related_products to array
    ];


    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'entity');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    // Relationship with tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')->useDisk('public');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(150)
            ->height(150)
            ->sharpen(10);

        $this->addMediaConversion('medium')
            ->width(300)
            ->height(300);

        $this->addMediaConversion('medium_large')
            ->width(768)
            ->height(null); // Keeps aspect ratio

        $this->addMediaConversion('large')
            ->width(1024)
            ->height(1024);
    }
}
