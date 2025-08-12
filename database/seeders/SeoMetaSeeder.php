<?php

namespace Database\Seeders;

use App\Models\SeoMeta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeoMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seoMetas = [
            [
                'entity_type' => 'product',
                'entity_id' => 1,
                'meta_title' => 'iPhone 13 - Buy Now',
                'meta_description' => 'Get the latest iPhone 13 with A15 Bionic chip.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 2,
                'meta_title' => 'Galaxy S21 - Buy Now',
                'meta_description' => 'Samsung flagship smartphone with amazing features.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 3,
                'meta_title' => 'MacBook Pro - Buy Now',
                'meta_description' => 'Powerful laptop for professionals.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 4,
                'meta_title' => 'Surface Laptop 4 - Buy Now',
                'meta_description' => 'Sleek and powerful Windows laptop.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 5,
                'meta_title' => 'PlayStation 5 - Buy Now',
                'meta_description' => 'Next-gen gaming console.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 6,
                'meta_title' => 'Xbox Series X - Buy Now',
                'meta_description' => 'Powerful gaming console from Microsoft.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 7,
                'meta_title' => 'Sony WH-1000XM4 - Buy Now',
                'meta_description' => 'Noise-cancelling headphones.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 8,
                'meta_title' => 'Canon EOS R5 - Buy Now',
                'meta_description' => 'Professional mirrorless camera.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 9,
                'meta_title' => 'LG OLED CX - Buy Now',
                'meta_description' => '4K OLED TV with stunning picture quality.',
            ],
            [
                'entity_type' => 'product',
                'entity_id' => 10,
                'meta_title' => 'Apple Watch Series 7 - Buy Now',
                'meta_description' => 'Latest smartwatch from Apple.',
            ],
        ];

        foreach ($seoMetas as $seoMeta) {
            SeoMeta::create($seoMeta);
        }
    }
}
