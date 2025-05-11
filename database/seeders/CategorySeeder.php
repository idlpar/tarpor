<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Kids',
            'slug' => 'kids',
            'description' => 'Clothing and accessories for kids.',
            'image' => 'categories/kids.jpg',
            'status' => 'active',
        ]);

        Category::create([
            'name' => 'Men',
            'slug' => 'men',
            'description' => 'Men’s fashion and accessories.',
            'image' => 'categories/men.jpg',
            'status' => 'active',
        ]);

        Category::create([
            'name' => 'Women',
            'slug' => 'women',
            'description' => 'Women’s fashion and accessories.',
            'image' => 'categories/women.jpg',
            'status' => 'active',
        ]);
    }
}
