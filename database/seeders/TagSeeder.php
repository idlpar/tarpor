<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'casual', 'slug' => 'casual', 'description' => 'Casual clothing for daily use.'],
            ['name' => 'sportswear', 'slug' => 'sportswear', 'description' => 'Athletic and performance wear.'],
            ['name' => 'summer', 'slug' => 'summer', 'description' => 'Summer clothing collection.'],
            ['name' => 'winter', 'slug' => 'winter', 'description' => 'Winter collection and warm wear.'],
            ['name' => 'new', 'slug' => 'new', 'description' => 'Newly arrived products.'],
            ['name' => 'best-seller', 'slug' => 'best-seller', 'description' => 'Top-selling items.'],
            ['name' => 'kids-fashion', 'slug' => 'kids-fashion', 'description' => 'Trendy clothing for kids.'],
            ['name' => 'formal', 'slug' => 'formal', 'description' => 'Office and formal wear.'],
            ['name' => 'eco-friendly', 'slug' => 'eco-friendly', 'description' => 'Sustainable fashion items.'],
            ['name' => 'limited-edition', 'slug' => 'limited-edition', 'description' => 'Exclusive limited stock items.'],
        ];

        foreach ($tags as $tag) {
            Tag::create(array_merge($tag, ['product_count' => 0]));
        }
    }
}
