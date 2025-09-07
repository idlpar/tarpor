<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sku', 'barcode', 'price', 'sale_price', 'cost_price',
        'stock_quantity', 'stock_status', 'weight', 'length', 'width', 'height',
        'is_default', 'image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    protected $appends = ['attributes_list', 'final_price', 'formatted_price', 'formatted_sale_price'];

    public function getFormattedPriceAttribute()
    {
        return format_taka($this->price);
    }

    public function getFormattedSalePriceAttribute()
    {
        return $this->sale_price ? format_taka($this->sale_price) : null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(
            \App\Models\ProductAttributeValue::class,
            'product_variant_attribute_values',
            'variant_id', // Foreign key for ProductVariant
            'attribute_value_id' // Foreign key for ProductAttributeValue
        );
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

    public function getAttributesListAttribute()
    {
        return $this->attributeValues->map(function($value) {
            return $value->attribute->name . ': ' . $value->value;
        })->implode(', ');
    }

    public function getFinalPriceAttribute()
    {
        if ($this->sale_price && $this->sale_price > 0) {
            return $this->sale_price;
        }
        return $this->price;
    }
}