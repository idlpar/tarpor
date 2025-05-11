<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('category_product')->insert([
            ['category_id' => 1, 'product_id' => 1], // Kids T-Shirt in Kids
            ['category_id' => 2, 'product_id' => 2], // Men’s Jacket in Men
        ]);
    }
}
