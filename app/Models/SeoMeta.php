<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'entity_type', 'entity_id', 'meta_title', 'meta_description', 'meta_keywords',
        'canonical_url', 'og_title', 'og_description', 'og_image', 'twitter_title',
        'twitter_description', 'twitter_image', 'schema_markup', 'robots'
    ];

    /**
     * Get the owning entity (e.g., product, category, brand).
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
