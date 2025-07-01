<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kids', 'slug' => 'kids', 'description' => 'Clothing and accessories for kids.', 'image' => 'categories/kids.jpg'],
            ['name' => 'Men', 'slug' => 'men', 'description' => 'Men’s fashion and accessories.', 'image' => 'categories/men.jpg'],
            ['name' => 'Women', 'slug' => 'women', 'description' => 'Women’s fashion and accessories.', 'image' => 'categories/women.jpg'],
            ['name' => 'Footwear', 'slug' => 'footwear', 'description' => 'Shoes, sneakers, and more.', 'image' => 'categories/footwear.jpg'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Bags, belts, hats, and more.', 'image' => 'categories/accessories.jpg'],
            ['name' => 'Watches', 'slug' => 'watches', 'description' => 'Luxury and casual watches.', 'image' => 'categories/watches.jpg'],
            ['name' => 'Bags', 'slug' => 'bags', 'description' => 'Backpacks, handbags, and more.', 'image' => 'categories/bags.jpg'],
            ['name' => 'Winter Wear', 'slug' => 'winter-wear', 'description' => 'Jackets, sweaters, coats.', 'image' => 'categories/winter.jpg'],
            ['name' => 'Summer Wear', 'slug' => 'summer-wear', 'description' => 'Lightweight clothing for summer.', 'image' => 'categories/summer.jpg'],
            ['name' => 'New Arrivals', 'slug' => 'new-arrivals', 'description' => 'Latest products in stock.', 'image' => 'categories/new.jpg'],
        ];

        foreach ($categories as $category) {
            Category::create(array_merge($category, ['status' => 'active']));
        }
    }
}
