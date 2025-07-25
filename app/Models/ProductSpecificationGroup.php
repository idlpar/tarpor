<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpecificationGroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function attributes()
    {
        return $this->hasMany(ProductSpecificationAttribute::class);
    }

    public function tables()
    {
        return $this->belongsToMany(ProductSpecificationTable::class, 'product_specification_table_group')->withPivot('order');
    }
}
