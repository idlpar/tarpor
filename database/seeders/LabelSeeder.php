<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Label;
use Illuminate\Support\Str;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = [
            ['name' => 'New Arrival', 'description' => 'Recently added to our collection.', 'status' => true],
            ['name' => 'Limited Edition', 'description' => 'Exclusive and rare pieces.', 'status' => true],
            ['name' => 'Best Seller', 'description' => 'Our most popular items.', 'status' => true],
            ['name' => 'On Sale', 'description' => 'Special discounted price.', 'status' => true],
            ['name' => "Editor's Pick", 'description' => 'Curated by our fashion experts.', 'status' => true],
            ['name' => 'Sustainable', 'description' => 'Made with eco-friendly materials.', 'status' => true],
            ['name' => 'Handcrafted', 'description' => 'Artisan-made with unique details.', 'status' => true],
            ['name' => 'Pre-Order', 'description' => 'Available for purchase before release.', 'status' => true],
            ['name' => 'Exclusive Online', 'description' => 'Only available on our website.', 'status' => true],
            ['name' => 'Back in Stock', 'description' => 'Popular items that have been restocked.', 'status' => true],
        ];

        foreach ($labels as $labelData) {
            Label::create([
                'name' => $labelData['name'],
                'slug' => Str::slug($labelData['name']),
                'description' => $labelData['description'],
                'status' => $labelData['status'],
            ]);
        }
    }
}