<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'status', 'logo_id'
    ];

    /**
     * Get the brand's logo.
     */
    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the products for this brand.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the SEO metadata for this brand.
     */
    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'entity');
    }
}
