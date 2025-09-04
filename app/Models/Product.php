<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\OrderItem;

class Product extends Model
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    protected $appends = ['thumbnail_url', 'total_stock'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'type',
        'price', 'sale_price', 'cost_price', 'sku', 'barcode',
        'stock_quantity', 'stock_status', 'inventory_tracking', 'low_stock_threshold',
        'weight', 'length', 'width', 'height', 'brand_id',
        'views', 'status', 'is_featured', 'is_hot', 'is_sale',
        'min_order_quantity', 'max_order_quantity', 'low_stock_threshold'
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
        'is_hot' => 'boolean',
        'is_sale' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function productAttributes()
    {
        return $this->belongsToMany(ProductAttribute::class, 'product_product_attribute')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'product_media')->withPivot('type', 'order')->withTimestamps()->orderBy('product_media.order');
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

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_product_id')
            ->withPivot('relation_type', 'position')
            ->withTimestamps();
    }

    public function crossSellingProducts()
    {
        return $this->belongsToMany(Product::class, 'product_cross_selling', 'product_id', 'cross_selling_product_id')
            ->withTimestamps();
    }

    public function faqs()
    {
        return $this->belongsToMany(Faq::class, 'product_faqs')->withTimestamps();
    }

    public function customFaqs()
    {
        return $this->hasMany(ProductFaq::class)->whereNull('faq_id');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class)->withTimestamps();
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable', 'entity_type', 'entity_id');
    }

    public function getFinalPriceAttribute()
    {
        if ($this->sale_price && $this->sale_price > 0) {
            return $this->sale_price;
        }
        return $this->price;
    }

    public function getThumbnailUrlAttribute()
    {
        $featuredMedia = $this->media->where('pivot.type', 'featured')->first();
        if ($featuredMedia) {
            return $featuredMedia->url;
        }
        return asset('images/default-product.jpg');
    }

    public function getGalleryMediaAttribute()
    {
        return $this->media->where('pivot.type', 'gallery')->sortBy('pivot.order');
    }

    public function getGalleryImagesAttribute()
    {
        return $this->gallery_media->map(fn($media) => $media->url);
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

    public function getTotalStockAttribute()
    {
        if ($this->type === 'variable') {
            return $this->variants->sum('stock_quantity');
        }
        return $this->stock_quantity;
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeFilter($query, $filters)
    {
        if (!is_array($filters)) return $query;

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['stock_status'])) {
            $query->where('stock_status', $filters['stock_status']);
        }

        if (!empty($filters['sort_by'])) {
            $sort = explode('_', $filters['sort_by']);
            $column = $sort[0];
            $direction = $sort[1] ?? 'asc';

            if ($column === 'name') {
                $query->orderBy('name', $direction);
            } elseif ($column === 'price') {
                $query->orderBy('price', $direction);
            } elseif ($column === 'stock') {
                $query->orderBy('stock_quantity', $direction);
            } elseif ($column === 'date') {
                $query->orderBy('created_at', $direction);
            }
        } else {
            $query->latest(); // Default sort
        }

        return $query;
    }
}
