<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        Brand::create([
            'name' => 'Nike',
            'slug' => 'nike',
            'logo' => 'brands/nike.png',
            'description' => 'Leading sportswear brand.',
            'status' => 'active',
        ]);

        Brand::create([
            'name' => 'Adidas',
            'slug' => 'adidas',
            'logo' => 'brands/adidas.png',
            'description' => 'Global sportswear and lifestyle brand.',
            'status' => 'active',
        ]);
    }
}
