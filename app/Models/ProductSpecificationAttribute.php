<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpecificationAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_specification_group_id', 'name', 'unit'];

    public function group()
    {
        return $this->belongsTo(ProductSpecificationGroup::class, 'product_specification_group_id');
    }
}
