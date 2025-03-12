<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define an array of sample tags
        $tags = [
            'Electronics',
            'Home Appliances',
            'Clothing',
            'Accessories',
            'Furniture',
            'Sports',
            'Books',
            'Toys',
            'Beauty',
            'Health',
        ];

        // Insert tags into the database
        foreach ($tags as $tagName) {
            Tag::create([
                'name' => strtolower($tagName), // Store in lowercase
                'slug' => Str::slug($tagName), // Generate a URL-friendly slug
                'description' => "This is a sample description for the {$tagName} tag.",
                'product_count' => 0, // Initially, no products are associated
            ]);
        }

        $this->command->info('10 tags seeded successfully!');
        // Attach tags to existing products
        $products = Product::all(); // Fetch all products
        $tags = Tag::all(); // Fetch all tags

        if ($products->isEmpty()) {
            $this->command->warn('No products found to attach tags.');
            return;
        }

        if ($tags->isEmpty()) {
            $this->command->warn('No tags found to attach to products.');
            return;
        }

        // Attach tags to products
        foreach ($products as $product) {
            // Randomly attach 1 to 3 tags to each product
            $randomTags = $tags->random(rand(1, 3))->pluck('id')->toArray();
            $product->tags()->attach($randomTags);

            // Update the product_count for each attached tag
            foreach ($randomTags as $tagId) {
                $tag = Tag::find($tagId);
                $tag->increment('product_count'); // Increment product_count
            }
        }

        $this->command->info('Tags attached to products successfully!');
    }
}
