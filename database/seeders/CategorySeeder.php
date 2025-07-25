<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Main Categories
        $mainCategories = [
            ['name' => 'Kids', 'description' => 'Clothing and accessories for kids.', 'image' => 'categories/kids.jpg'],
            ['name' => 'Men', 'description' => 'Men’s fashion and accessories.', 'image' => 'categories/men.jpg'],
            ['name' => 'Women', 'description' => 'Women’s fashion and accessories.', 'image' => 'categories/women.jpg'],
            ['name' => 'Footwear', 'description' => 'Shoes, sneakers, and more.', 'image' => 'categories/footwear.jpg'],
            ['name' => 'Accessories', 'description' => 'Bags, belts, hats, and more.', 'image' => 'categories/accessories.jpg'],
        ];

        $createdMainCategories = [];
        foreach ($mainCategories as $catData) {
            $createdMainCategories[$catData['name']] = Category::create(array_merge($catData, [
                'slug' => Str::slug($catData['name']),
                'status' => 'active'
            ]));
        }

        // Subcategories
        $subCategories = [
            // Kids Subcategories
            ['name' => 'Boys Apparel', 'parent' => 'Kids', 'description' => 'Clothing for boys.', 'image' => 'categories/boys.jpg'],
            ['name' => 'Girls Apparel', 'parent' => 'Kids', 'description' => 'Clothing for girls.', 'image' => 'categories/girls.jpg'],

            // Men Subcategories
            ['name' => "Men's Tops", 'parent' => 'Men', 'description' => 'Shirts, T-shirts, Polos for men.', 'image' => 'categories/men-tops.jpg'],
            ['name' => "Men's Bottoms", 'parent' => 'Men', 'description' => 'Pants, Jeans, Shorts for men.', 'image' => 'categories/men-bottoms.jpg'],

            // Women Subcategories
            ['name' => 'Dresses', 'parent' => 'Women', 'description' => 'Elegant and casual dresses for women.', 'image' => 'categories/dresses.jpg'],
            ['name' => 'Skirts', 'parent' => 'Women', 'description' => 'Various styles of skirts.', 'image' => 'categories/skirts.jpg'],

            // Footwear Subcategories
            ['name' => 'Sneakers', 'parent' => 'Footwear', 'description' => 'Casual and athletic sneakers.', 'image' => 'categories/sneakers.jpg'],
            ['name' => 'Boots', 'parent' => 'Footwear', 'description' => 'Stylish and durable boots.', 'image' => 'categories/boots.jpg'],

            // Accessories Subcategories
            ['name' => 'Bags & Wallets', 'parent' => 'Accessories', 'description' => 'Handbags, wallets, and clutches.', 'image' => 'categories/bags-wallets.jpg'],
            ['name' => 'Jewelry', 'parent' => 'Accessories', 'description' => 'Necklaces, earrings, bracelets.', 'image' => 'categories/jewelry.jpg'],
        ];

        foreach ($subCategories as $catData) {
            $parentCategory = $createdMainCategories[$catData['parent']];
            unset($catData['parent']); // Remove the 'parent' key before creating
            Category::create(array_merge($catData, [
                'slug' => Str::slug($catData['name']),
                'parent_id' => $parentCategory->id,
                'status' => 'active'
            ]));
        }
    }
}