<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductSpecification;

class ProductSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing products to attach specifications to
        $products = Product::take(5)->get(); // Get first 5 products

        foreach ($products as $product) {
            // Example specifications for a clothing item
            if ($product->id % 2 == 0) { // For even product IDs
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'General Features',
                    'attribute_name' => 'Season',
                    'attribute_value' => 'Spring/Summer',
                ]);
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'General Features',
                    'attribute_name' => 'Gender',
                    'attribute_value' => 'Unisex',
                ]);
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'Material & Care',
                    'attribute_name' => 'Composition',
                    'attribute_value' => '100% Organic Cotton',
                ]);
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'Material & Care',
                    'attribute_name' => 'Washing Instructions',
                    'attribute_value' => 'Machine wash cold, tumble dry low',
                ]);
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'Dimensions & Fit',
                    'attribute_name' => 'Fit Type',
                    'attribute_value' => 'Regular Fit',
                ]);
            } else { // For odd product IDs
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'General Features',
                    'attribute_name' => 'Style',
                    'attribute_value' => 'Casual',
                ]);
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'Material & Care',
                    'attribute_name' => 'Composition',
                    'attribute_value' => '60% Polyester, 40% Viscose',
                ]);
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'Dimensions & Fit',
                    'attribute_name' => 'Sleeve Length',
                    'attribute_value' => 'Short Sleeve',
                ]);
                ProductSpecification::create([
                    'product_id' => $product->id,
                    'group_name' => 'Performance & Technology',
                    'attribute_name' => 'Feature',
                    'attribute_value' => 'Moisture-wicking',
                ]);
            }
        }
    }
}
