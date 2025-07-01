<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTagSeeder extends Seeder
{
    public function run(): void
    {
        // Optional: clear pivot to avoid duplication (for product_tag, not category_product)
        DB::table('product_tag')->truncate();

        DB::table('product_tag')->insert([
            ['product_id' => 1, 'tag_id' => 1], // Kids T-Shirt: casual
            ['product_id' => 2, 'tag_id' => 1], // Men’s Jacket: casual
        ]);
    }
}
