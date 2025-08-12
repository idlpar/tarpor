<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'slug', 'parent_id', 'description', 'image', 'status'
    ];

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->with('children');
    }

    /**
     * Get the products in this category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }

    public function siblings()
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'parent_id')
            ->where('id', '!=', $this->id);
    }

    /**
     * Get the SEO metadata for this category.
     */
    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'entity');
    }

    public function totalProducts()
    {
        $productsCount = $this->products->count();
        foreach ($this->children as $child) {
            $productsCount += $child->totalProducts();
        }
        return $productsCount;
    }

    /**
     * Get all ancestors of the category (new method)
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors->reverse()->values();
    }

    /**
     * Get the full ancestor path including self (new method)
     */
    public function getFullPath()
    {
        $path = $this->ancestors()->pluck('id')->toArray();
        $path[] = $this->id;
        return $path;
    }

    /**
     * Check if category is a leaf node (no children) (new method)
     */
    public function isLeaf()
    {
        return $this->children->isEmpty();
    }
}
