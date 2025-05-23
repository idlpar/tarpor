<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'sku' => 'KID001',
                'name' => 'Kids T-Shirt',
                'slug' => 'kids-t-shirt',
                'description' => 'Comfortable cotton t-shirt for kids, perfect for daily wear.',
                'short_description' => 'Soft and durable kids’ t-shirt.',
                'price' => 15.99,
                'sale_price' => 12.99,
                'stock_quantity' => 100,
                'stock_status' => 'in_stock',
                'brand_id' => 1,
                'thumbnail' => 'products/kids-t-shirt.jpg',
                'images' => json_encode(['products/kids-t-shirt-1.jpg', 'products/kids-t-shirt-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'MEN001',
                'name' => 'Men’s Jacket',
                'slug' => 'mens-jacket',
                'description' => 'Stylish and warm jacket for men, ideal for winter.',
                'short_description' => 'Warm and trendy men’s jacket.',
                'price' => 49.99,
                'sale_price' => 39.99,
                'stock_quantity' => 50,
                'stock_status' => 'in_stock',
                'brand_id' => 2,
                'thumbnail' => 'products/mens-jacket.jpg',
                'images' => json_encode(['products/mens-jacket-1.jpg', 'products/mens-jacket-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'WOM001',
                'name' => 'Women’s Dress',
                'slug' => 'womens-dress',
                'description' => 'Elegant and comfortable dress for women, perfect for any occasion.',
                'short_description' => 'Chic and comfortable dress.',
                'price' => 59.99,
                'sale_price' => 49.99,
                'stock_quantity' => 80,
                'stock_status' => 'in_stock',
                'brand_id' => 1,
                'thumbnail' => 'products/womens-dress.jpg',
                'images' => json_encode(['products/womens-dress-1.jpg', 'products/womens-dress-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'KID002',
                'name' => 'Kids Sneakers',
                'slug' => 'kids-sneakers',
                'description' => 'Durable and stylish sneakers for kids, great for active play.',
                'short_description' => 'Stylish and sturdy kids’ sneakers.',
                'price' => 29.99,
                'sale_price' => 24.99,
                'stock_quantity' => 120,
                'stock_status' => 'in_stock',
                'brand_id' => 3,
                'thumbnail' => 'products/kids-sneakers.jpg',
                'images' => json_encode(['products/kids-sneakers-1.jpg', 'products/kids-sneakers-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'MEN002',
                'name' => 'Men’s Casual Shirt',
                'slug' => 'mens-casual-shirt',
                'description' => 'Breathable cotton shirt for men, ideal for casual outings.',
                'short_description' => 'Casual and breathable shirt.',
                'price' => 34.99,
                'sale_price' => 29.99,
                'stock_quantity' => 70,
                'stock_status' => 'in_stock',
                'brand_id' => 2,
                'thumbnail' => 'products/mens-casual-shirt.jpg',
                'images' => json_encode(['products/mens-casual-shirt-1.jpg', 'products/mens-casual-shirt-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'WOM002',
                'name' => 'Women’s Handbag',
                'slug' => 'womens-handbag',
                'description' => 'Stylish leather handbag with ample space for essentials.',
                'short_description' => 'Elegant and spacious handbag.',
                'price' => 79.99,
                'sale_price' => 69.99,
                'stock_quantity' => 40,
                'stock_status' => 'in_stock',
                'brand_id' => 1,
                'thumbnail' => 'products/womens-handbag.jpg',
                'images' => json_encode(['products/womens-handbag-1.jpg', 'products/womens-handbag-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'ACC001',
                'name' => 'Sunglasses',
                'slug' => 'sunglasses',
                'description' => 'UV-protective sunglasses with a modern design.',
                'short_description' => 'Stylish sunglasses for all.',
                'price' => 19.99,
                'sale_price' => 15.99,
                'stock_quantity' => 150,
                'stock_status' => 'in_stock',
                'brand_id' => 3,
                'thumbnail' => 'products/sunglasses.jpg',
                'images' => json_encode(['products/sunglasses-1.jpg', 'products/sunglasses-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'KID003',
                'name' => 'Kids Backpack',
                'slug' => 'kids-backpack',
                'description' => 'Lightweight and colorful backpack for kids, perfect for school.',
                'short_description' => 'Durable and colorful backpack.',
                'price' => 24.99,
                'sale_price' => 19.99,
                'stock_quantity' => 90,
                'stock_status' => 'in_stock',
                'brand_id' => 1,
                'thumbnail' => 'products/kids-backpack.jpg',
                'images' => json_encode(['products/kids-backpack-1.jpg', 'products/kids-backpack-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'MEN003',
                'name' => 'Men’s Watch',
                'slug' => 'mens-watch',
                'description' => 'Elegant wristwatch for men with a sleek design.',
                'short_description' => 'Sleek and modern watch.',
                'price' => 99.99,
                'sale_price' => 89.99,
                'stock_quantity' => 30,
                'stock_status' => 'in_stock',
                'brand_id' => 2,
                'thumbnail' => 'products/mens-watch.jpg',
                'images' => json_encode(['products/mens-watch-1.jpg', 'products/mens-watch-2.jpg']),
                'status' => 'published',
            ],
            [
                'sku' => 'WOM003',
                'name' => 'Women’s Scarf',
                'slug' => 'womens-scarf',
                'description' => 'Soft silk scarf, perfect for adding elegance to any outfit.',
                'short_description' => 'Elegant and versatile scarf.',
                'price' => 14.99,
                'sale_price' => 12.99,
                'stock_quantity' => 200,
                'stock_status' => 'in_stock',
                'brand_id' => 1,
                'thumbnail' => 'products/womens-scarf.jpg',
                'images' => json_encode(['products/womens-scarf-1.jpg', 'products/womens-scarf-2.jpg']),
                'status' => 'published',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
