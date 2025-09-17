<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        // Only truncate in non-production environments
        if (app()->environment('local', 'testing')) {
            DB::table('category_product')->truncate();
        }

        $data = [
            ['category_id' => 1, 'product_id' => 1], // Kids T-Shirt in Kids
            ['category_id' => 2, 'product_id' => 2], // Men’s Jacket in Men
        ];

        foreach ($data as $entry) {
            // Check if category_id exists
            if (!Category::where('id', $entry['category_id'])->exists()) {
                $this->command->warn("Category ID {$entry['category_id']} does not exist. Skipping entry.");
                continue;
            }

            // Check if product_id exists
            if (!Product::where('id', $entry['product_id'])->exists()) {
                $this->command->warn("Product ID {$entry['product_id']} does not exist. Skipping entry.");
                continue;
            }

            // Insert the record if both category and product exist
            DB::table('category_product')->insertOrIgnore($entry);
        }

        $this->command->info('CategoryProductSeeder completed successfully.');
    }
}
