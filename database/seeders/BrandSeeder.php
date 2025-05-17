<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'id' => 1,
                'name' => 'KidsTrend',
                'slug' => 'kids-trend',
                'logo' => 'brands/kids-trend-logo.png',
                'description' => 'A leading brand for children’s clothing and accessories.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'MensStyle',
                'slug' => 'mens-style',
                'logo' => 'brands/mens-style-logo.png',
                'description' => 'Premium men’s fashion and accessories.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'ActiveWear',
                'slug' => 'active-wear',
                'logo' => 'brands/active-wear-logo.png',
                'description' => 'High-performance sportswear and accessories.',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
