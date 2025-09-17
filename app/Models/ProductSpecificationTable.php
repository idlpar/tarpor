<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpecificationTable extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'type'];

    public function groups()
    {
        return $this->belongsToMany(ProductSpecificationGroup::class, 'product_specification_table_group')->withPivot('order')->orderBy('pivot_order');
    }
}
