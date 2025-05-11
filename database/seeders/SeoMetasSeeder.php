<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoMeta;

class SeoMetasSeeder extends Seeder
{
    public function run(): void
    {
        SeoMeta::create([
            'entity_type' => 'App\Models\Product',
            'entity_id' => 1,
            'meta_title' => 'Sample Product SEO',
            'meta_description' => 'SEO description for sample product.',
            'meta_keywords' => 'product, sample, tarpor',
        ]);
    }
}
