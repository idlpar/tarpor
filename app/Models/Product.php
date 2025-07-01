<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'type',
        'price', 'sale_price', 'cost_price', 'sku', 'barcode',
        'stock_quantity', 'stock_status', 'inventory_tracking', 'low_stock_threshold',
        'weight', 'length', 'width', 'height', 'brand_id', 'thumbnail',
        'views', 'status', 'is_featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'inventory_tracking' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function pricingTiers()
    {
        return $this->hasMany(ProductPricingTier::class);
    }

    public function specialOffers()
    {
        return $this->hasMany(ProductSpecialOffer::class);
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_relations', 'product_id', 'related_product_id')
            ->withPivot('relation_type', 'position')
            ->withTimestamps();
    }

    public function getFinalPriceAttribute()
    {
        if ($this->sale_price && $this->sale_price > 0) {
            return $this->sale_price;
        }
        return $this->price;
    }

    public function getStockStatusLabelAttribute()
    {
        return [
            'in_stock' => 'In Stock',
            'out_of_stock' => 'Out of Stock',
            'backorder' => 'Backorder',
        ][$this->stock_status] ?? $this->stock_status;
    }

    public function getStatusLabelAttribute()
    {
        return [
            'draft' => 'Draft',
            'published' => 'Published',
            'archived' => 'Archived',
        ][$this->status] ?? $this->status;
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (!is_array($filters)) return $query;

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('id', $filters['category_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}
