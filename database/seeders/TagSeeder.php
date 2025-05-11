<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        Tag::create([
            'name' => 'casual',
            'slug' => 'casual',
            'description' => 'Casual clothing.',
            'product_count' => 2,
        ]);

        Tag::create([
            'name' => 'sportswear',
            'slug' => 'sportswear',
            'description' => 'Sportswear items.',
            'product_count' => 1,
        ]);
    }
}
