<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use Illuminate\Support\Str;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            ['name' => 'Color', 'description' => 'Available colors for the product.'],
            ['name' => 'Size', 'description' => 'Available sizes (e.g., S, M, L, XL, 30, 32).'],
            ['name' => 'Material', 'description' => 'Primary material composition.'],
            ['name' => 'Style', 'description' => 'Specific style or cut (e.g., Slim Fit, Regular Fit).'],
            ['name' => 'Neckline', 'description' => 'Type of neckline (e.g., Crew Neck, V-Neck).'],
            ['name' => 'Sleeve Length', 'description' => 'Length of the sleeves.'],
            ['name' => 'Pattern', 'description' => 'Design pattern (e.g., Solid, Striped, Floral).'],
            ['name' => 'Occasion', 'description' => 'Suitable occasion (e.g., Casual, Formal, Party).'],
            ['name' => 'Fit', 'description' => 'Overall fit of the garment.'],
            ['name' => 'Closure Type', 'description' => 'How the garment closes (e.g., Zipper, Button).'],
        ];

        foreach ($attributes as $index => $attributeData) {
            ProductAttribute::create([
                'name' => $attributeData['name'],
                'slug' => Str::slug($attributeData['name']),
                'description' => $attributeData['description'],
                'position' => $index + 1,
            ]);
        }
    }
}
