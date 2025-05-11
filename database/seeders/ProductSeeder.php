<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'sku' => 'KID001',
            'name' => 'Kids T-Shirt',
            'slug' => 'kids-t-shirt',
            'description' => 'Comfortable cotton t-shirt for kids.',
            'short_description' => 'Soft and durable kids’ t-shirt.',
            'price' => 15.99,
            'sale_price' => 12.99,
            'stock_quantity' => 100,
            'stock_status' => 'in_stock',
            'brand_id' => 1,
            'thumbnail' => 'products/kids-t-shirt.jpg',
            'images' => json_encode(['products/kids-t-shirt-1.jpg', 'products/kids-t-shirt-2.jpg']),
            'status' => 'published',
        ]);

        Product::create([
            'sku' => 'MEN001',
            'name' => 'Men’s Jacket',
            'slug' => 'mens-jacket',
            'description' => 'Stylish jacket for men.',
            'short_description' => 'Warm and trendy men’s jacket.',
            'price' => 49.99,
            'sale_price' => 39.99,
            'stock_quantity' => 50,
            'stock_status' => 'in_stock',
            'brand_id' => 2,
            'thumbnail' => 'products/mens-jacket.jpg',
            'images' => json_encode(['products/mens-jacket-1.jpg', 'products/mens-jacket-2.jpg']),
            'status' => 'published',
        ]);
    }
}
