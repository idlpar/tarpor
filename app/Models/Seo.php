<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $table = 'seo_metas';

    protected $fillable = [
        'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
        'og_title', 'og_description', 'og_image', 'twitter_title', 'twitter_description',
        'twitter_image', 'schema_markup', 'robots',
    ];

    public function seoable()
    {
        return $this->morphTo('seoable', 'entity_type', 'entity_id');
    }
}
