<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Label;
use PhpParser\Node\Stmt\Label;

class LabelSeeder extends Seeder
{
    public function run(): void
    {
        $labels = [
            [
                'name' => 'New Arrival',
                'slug' => 'new-arrival',
                'description' => 'Recently added products',
                'image' => 'labels/new-arrival.png',
                'status' => 'active',
            ],
            [
                'name' => 'Best Seller',
                'slug' => 'best-seller',
                'description' => 'Top selling products',
                'image' => 'labels/best-seller.png',
                'status' => 'active',
            ],
            [
                'name' => 'Limited Edition',
                'slug' => 'limited-edition',
                'description' => 'Exclusive, limited availability',
                'image' => 'labels/limited-edition.png',
                'status' => 'active',
            ],
            [
                'name' => 'Organic',
                'slug' => 'organic',
                'description' => 'Made from organic materials',
                'image' => 'labels/organic.png',
                'status' => 'active',
            ],
            [
                'name' => 'Sale',
                'slug' => 'sale',
                'description' => 'Products currently on discount',
                'image' => 'labels/sale.png',
                'status' => 'active',
            ],
            // Add other labels similarly...
        ];

        foreach ($labels as $label) {
            Label::create($label);
        }
    }
}
