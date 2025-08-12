<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Nike', 'description' => 'Global leader in athletic footwear, apparel, equipment, accessories, and services.', 'status' => 'active'],
            ['name' => 'Adidas', 'description' => 'German multinational corporation, designs and manufactures shoes, clothing and accessories.', 'status' => 'active'],
            ['name' => 'Zara', 'description' => 'Spanish apparel retailer based in Arteixo, Galicia, Spain.', 'status' => 'active'],
            ['name' => 'H&M', 'description' => 'Swedish multinational clothing-retail company known for its fast-fashion clothing for men, women, teenagers and children.', 'status' => 'active'],
            ['name' => 'Gucci', 'description' => 'Italian high-end luxury fashion house based in Florence, Italy.', 'status' => 'active'],
            ['name' => 'Louis Vuitton', 'description' => 'French fashion house and luxury goods company.', 'status' => 'active'],
            ['name' => 'Puma', 'description' => 'German multinational corporation that designs and manufactures athletic and casual footwear, apparel and accessories.', 'status' => 'active'],
            ['name' => "Levi's", 'description' => "American clothing company known worldwide for its Levi's brand of denim jeans.", 'status' => 'active'],
            ['name' => 'Calvin Klein', 'description' => 'American fashion house established by Calvin Klein and Barry Schwartz.', 'status' => 'active'],
            ['name' => 'Ralph Lauren', 'description' => 'American fashion company producing products ranging from the mid-range to the luxury segments.', 'status' => 'active'],
        ];

        foreach ($brands as $brandData) {
            Brand::create(array_merge($brandData, [
                'slug' => Str::slug($brandData['name']),
                'logo_id' => null, // No logo for now
            ]));
        }
    }
}