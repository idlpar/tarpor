<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSpecificationGroup;

class ProductSpecificationGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            ['name' => 'General Features', 'description' => 'Basic characteristics of the product.'],
            ['name' => 'Material & Care', 'description' => 'Information about fabric, composition, and washing instructions.'],
            ['name' => 'Dimensions & Fit', 'description' => 'Measurements and how the garment fits.'],
            ['name' => 'Performance & Technology', 'description' => 'Special features or technologies used.'],
            ['name' => 'Origin & Manufacturing', 'description' => 'Details about where and how the product was made.'],
            ['name' => 'Sustainability', 'description' => 'Environmental and ethical considerations.'],
            ['name' => 'Footwear Specifics', 'description' => 'Details unique to shoes and boots.'],
            ['name' => 'Accessory Specifics', 'description' => 'Details unique to accessories like bags or jewelry.'],
        ];

        foreach ($groups as $groupData) {
            ProductSpecificationGroup::create($groupData);
        }
    }
}
