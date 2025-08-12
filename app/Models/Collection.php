<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}