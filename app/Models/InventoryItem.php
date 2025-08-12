<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'variant_id', 'quantity', 'low_stock_threshold',
        'location', 'batch_number', 'expiry_date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function getLowStockAttribute()
    {
        if (is_null($this->low_stock_threshold)) {
            return false;
        }
        return $this->quantity <= $this->low_stock_threshold;
    }
}

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id', 'quantity', 'movement_type', 'notes', 'user_id'
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getQuantityWithSignAttribute()
    {
        if (in_array($this->movement_type, ['sale', 'loss'])) {
            return -$this->quantity;
        }
        return $this->quantity;
    }
}
