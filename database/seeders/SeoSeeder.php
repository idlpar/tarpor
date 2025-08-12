<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('seo')->insert([
            'seoable_type' => 'App\Models\Category',
            'seoable_id' => 1,
            'meta_title' => 'Kids Clothing',
            'meta_description' => 'Explore kids’ clothing at Tarpor.',
            'meta_keywords' => 'kids, clothing, tarpor',
        ]);
    }
}
