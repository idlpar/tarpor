<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'T-Shirts',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Crew Neck',
                        'subcategories' => [
                            ['name' => 'Graphic Tees'],
                            ['name' => 'Plain Tees'],
                            ['name' => 'Striped Tees'],
                        ],
                    ],
                    [
                        'name' => 'V-Neck',
                        'subcategories' => [
                            ['name' => 'Short Sleeve V-Neck'],
                            ['name' => 'Long Sleeve V-Neck'],
                            ['name' => 'Fitted V-Neck'],
                        ],
                    ],
                    [
                        'name' => 'Polo Shirts',
                        'subcategories' => [
                            ['name' => 'Classic Polo'],
                            ['name' => 'Slim Fit Polo'],
                            ['name' => 'Performance Polo'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Jeans',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Slim Fit',
                        'subcategories' => [
                            ['name' => 'Light Wash Slim'],
                            ['name' => 'Dark Wash Slim'],
                            ['name' => 'Distressed Slim'],
                        ],
                    ],
                    [
                        'name' => 'Straight Fit',
                        'subcategories' => [
                            ['name' => 'Classic Straight'],
                            ['name' => 'Stretch Straight'],
                            ['name' => 'Raw Denim Straight'],
                        ],
                    ],
                    [
                        'name' => 'Bootcut',
                        'subcategories' => [
                            ['name' => 'Regular Bootcut'],
                            ['name' => 'Flared Bootcut'],
                            ['name' => 'Low-Rise Bootcut'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Jackets',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Denim Jackets',
                        'subcategories' => [
                            ['name' => 'Classic Denim'],
                            ['name' => 'Distressed Denim'],
                            ['name' => 'Oversized Denim'],
                        ],
                    ],
                    [
                        'name' => 'Leather Jackets',
                        'subcategories' => [
                            ['name' => 'Biker Leather'],
                            ['name' => 'Bomber Leather'],
                            ['name' => 'Suede Leather'],
                        ],
                    ],
                    [
                        'name' => 'Puffer Jackets',
                        'subcategories' => [
                            ['name' => 'Hooded Puffer'],
                            ['name' => 'Lightweight Puffer'],
                            ['name' => 'Long Puffer'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Dresses',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Maxi Dresses',
                        'subcategories' => [
                            ['name' => 'Floral Maxi'],
                            ['name' => 'Boho Maxi'],
                            ['name' => 'Sleeveless Maxi'],
                        ],
                    ],
                    [
                        'name' => 'Midi Dresses',
                        'subcategories' => [
                            ['name' => 'A-Line Midi'],
                            ['name' => 'Wrap Midi'],
                            ['name' => 'Fit and Flare Midi'],
                        ],
                    ],
                    [
                        'name' => 'Mini Dresses',
                        'subcategories' => [
                            ['name' => 'Shift Mini'],
                            ['name' => 'Skater Mini'],
                            ['name' => 'Bodycon Mini'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Sweaters',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Crew Neck Sweaters',
                        'subcategories' => [
                            ['name' => 'Cable Knit Crew'],
                            ['name' => 'Wool Crew'],
                            ['name' => 'Cotton Crew'],
                        ],
                    ],
                    [
                        'name' => 'Cardigans',
                        'subcategories' => [
                            ['name' => 'Open Front Cardigan'],
                            ['name' => 'Button-Up Cardigan'],
                            ['name' => 'Longline Cardigan'],
                        ],
                    ],
                    [
                        'name' => 'Turtlenecks',
                        'subcategories' => [
                            ['name' => 'Fitted Turtleneck'],
                            ['name' => 'Oversized Turtleneck'],
                            ['name' => 'Ribbed Turtleneck'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Shirts',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Button-Down Shirts',
                        'subcategories' => [
                            ['name' => 'Oxford Shirt'],
                            ['name' => 'Flannel Shirt'],
                            ['name' => 'Denim Shirt'],
                        ],
                    ],
                    [
                        'name' => 'Blouses',
                        'subcategories' => [
                            ['name' => 'Silk Blouse'],
                            ['name' => 'Chiffon Blouse'],
                            ['name' => 'Lace Blouse'],
                        ],
                    ],
                    [
                        'name' => 'Tunic Shirts',
                        'subcategories' => [
                            ['name' => 'Long Tunic'],
                            ['name' => 'Printed Tunic'],
                            ['name' => 'Embroidered Tunic'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Pants',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Chinos',
                        'subcategories' => [
                            ['name' => 'Slim Chinos'],
                            ['name' => 'Regular Chinos'],
                            ['name' => 'Stretch Chinos'],
                        ],
                    ],
                    [
                        'name' => 'Leggings',
                        'subcategories' => [
                            ['name' => 'High-Waisted Leggings'],
                            ['name' => 'Athleisure Leggings'],
                            ['name' => 'Leather-Look Leggings'],
                        ],
                    ],
                    [
                        'name' => 'Cargo Pants',
                        'subcategories' => [
                            ['name' => 'Classic Cargo'],
                            ['name' => 'Slim Cargo'],
                            ['name' => 'Camo Cargo'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Skirts',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'A-Line Skirts',
                        'subcategories' => [
                            ['name' => 'Denim A-Line'],
                            ['name' => 'Pleated A-Line'],
                            ['name' => 'Floral A-Line'],
                        ],
                    ],
                    [
                        'name' => 'Pencil Skirts',
                        'subcategories' => [
                            ['name' => 'Fitted Pencil'],
                            ['name' => 'High-Waisted Pencil'],
                            ['name' => 'Leather Pencil'],
                        ],
                    ],
                    [
                        'name' => 'Mini Skirts',
                        'subcategories' => [
                            ['name' => 'Denim Mini'],
                            ['name' => 'Pleated Mini'],
                            ['name' => 'Wrap Mini'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Coats',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Trench Coats',
                        'subcategories' => [
                            ['name' => 'Classic Trench'],
                            ['name' => 'Belted Trench'],
                            ['name' => 'Double-Breasted Trench'],
                        ],
                    ],
                    [
                        'name' => 'Parkas',
                        'subcategories' => [
                            ['name' => 'Hooded Parka'],
                            ['name' => 'Fur-Lined Parka'],
                            ['name' => 'Lightweight Parka'],
                        ],
                    ],
                    [
                        'name' => 'Wool Coats',
                        'subcategories' => [
                            ['name' => 'Single-Breasted Wool'],
                            ['name' => 'Double-Breasted Wool'],
                            ['name' => 'Oversized Wool'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Accessories',
                'status' => 'active',
                'subcategories' => [
                    [
                        'name' => 'Hats',
                        'subcategories' => [
                            ['name' => 'Baseball Caps'],
                            ['name' => 'Beanies'],
                            ['name' => 'Fedora Hats'],
                        ],
                    ],
                    [
                        'name' => 'Scarves',
                        'subcategories' => [
                            ['name' => 'Wool Scarves'],
                            ['name' => 'Silk Scarves'],
                            ['name' => 'Infinity Scarves'],
                        ],
                    ],
                    [
                        'name' => 'Belts',
                        'subcategories' => [
                            ['name' => 'Leather Belts'],
                            ['name' => 'Canvas Belts'],
                            ['name' => 'Statement Belts'],
                        ],
                    ],
                ],
            ],
        ];

        // Create main categories
        foreach ($categories as $category) {
            $slug = Str::slug($category['name']);
            $createdCategory = Category::create([
                'name' => $category['name'],
                'slug' => $slug,
                'status' => $category['status'],
            ]);

            // Create subcategories
            foreach ($category['subcategories'] as $subcategory) {
                $subSlug = Str::slug($subcategory['name']);
                $createdSubcategory = Category::create([
                    'name' => $subcategory['name'],
                    'slug' => $subSlug,
                    'status' => 'active',
                    'parent_id' => $createdCategory->id,
                ]);

                // Create sub-subcategories
                foreach ($subcategory['subcategories'] as $subSubcategory) {
                    $subSubSlug = Str::slug($subSubcategory['name']);
                    Category::create([
                        'name' => $subSubcategory['name'],
                        'slug' => $subSubSlug,
                        'status' => 'active',
                        'parent_id' => $createdSubcategory->id,
                    ]);
                }
            }
        }
    }
}
