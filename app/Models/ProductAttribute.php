<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'position'];

    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class, 'attribute_id');
    }
}
