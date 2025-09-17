<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            ['name' => 'Summer Breeze', 'description' => 'Light and airy styles for the warm season.'],
            ['name' => 'Winter Elegance', 'description' => 'Sophisticated and warm attire for colder months.'],
            ['name' => 'Urban Chic', 'description' => 'Modern and edgy fashion for city life.'],
            ['name' => 'Bohemian Rhapsody', 'description' => 'Flowy fabrics and earthy tones for a free spirit.'],
            ['name' => 'Athleisure Luxe', 'description' => 'Comfortable yet stylish activewear.'],
            ['name' => 'Evening Glamour', 'description' => 'Dazzling outfits for special occasions.'],
            ['name' => 'Denim Essentials', 'description' => 'Timeless denim pieces for every wardrobe.'],
            ['name' => 'Minimalist Modern', 'description' => 'Clean lines and neutral palettes for a sleek look.'],
            ['name' => 'Vintage Revival', 'description' => 'Classic designs reimagined with a contemporary twist.'],
            ['name' => 'Resort Wear', 'description' => 'Effortless and stylish clothing for your next getaway.'],
        ];

        foreach ($collections as $collectionData) {
            Collection::create([
                'name' => $collectionData['name'],
                'slug' => Str::slug($collectionData['name']),
                'description' => $collectionData['description'],
            ]);
        }
    }
}
