<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [
            [
                'name' => 'Electronics',
                'children' => [
                    [
                        'name' => 'Computers & Accessories',
                        'children' => [
                            ['name' => 'Laptops'],
                            ['name' => 'Desktops'],
                            ['name' => 'Monitors'],
                            ['name' => 'Keyboards & Mice'],
                            ['name' => 'Printers & Scanners'],
                        ],
                    ],
                    [
                        'name' => 'Phones & Tablets',
                        'children' => [
                            ['name' => 'Smartphones'],
                            ['name' => 'Tablets'],
                            ['name' => 'Cases & Covers'],
                            ['name' => 'Chargers & Cables'],
                        ],
                    ],
                    [
                        'name' => 'Audio & Video',
                        'children' => [
                            ['name' => 'Headphones'],
                            ['name' => 'Speakers'],
                            ['name' => 'Televisions'],
                            ['name' => 'Cameras'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Fashion',
                'children' => [
                    [
                        'name' => 'Men\'s Fashion',
                        'children' => [
                            ['name' => 'T-Shirts & Polos'],
                            ['name' => 'Shirts'],
                            ['name' => 'Jeans & Trousers'],
                            ['name' => 'Shoes'],
                        ],
                    ],
                    [
                        'name' => 'Women\'s Fashion',
                        'children' => [
                            ['name' => 'Dresses'],
                            ['name' => 'Tops & Tees'],
                            ['name' => 'Skirts & Shorts'],
                            ['name' => 'Handbags'],
                        ],
                    ],
                    [
                        'name' => 'Kid\'s Fashion',
                        'children' => [
                            ['name' => 'Boys\' Clothing'],
                            ['name' => 'Girls\' Clothing'],
                            ['name' => 'Toys'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Home & Garden',
                'children' => [
                    [
                        'name' => 'Furniture',
                        'children' => [
                            ['name' => 'Living Room Furniture'],
                            ['name' => 'Bedroom Furniture'],
                            ['name' => 'Office Furniture'],
                        ],
                    ],
                    [
                        'name' => 'Kitchen & Dining',
                        'children' => [
                            ['name' => 'Cookware'],
                            ['name' => 'Tableware'],
                            ['name' => 'Appliances'],
                        ],
                    ],
                    [
                        'name' => 'Garden & Outdoor',
                        'children' => [
                            ['name' => 'Gardening Tools'],
                            ['name' => 'Outdoor Furniture'],
                            ['name' => 'Grills'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Networking',
                'children' => [
                    [
                        'name' => 'Routers',
                        'children' => [],
                    ],
                    [
                        'name' => 'Switches',
                        'children' => [],
                    ],
                    [
                        'name' => 'Modems',
                        'children' => [],
                    ],
                    [
                        'name' => 'Network Adapters',
                        'children' => [],
                    ],
                    [
                        'name' => 'Cables & Tools',
                        'children' => [],
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $this->createCategory($categoryData);
        }
    }

    private function createCategory(array $categoryData, Category $parent = null): void
    {
        $category = Category::create([
            'name' => $categoryData['name'],
            'slug' => Str::slug($categoryData['name']),
            'parent_id' => $parent ? $parent->id : null,
        ]);

        if (isset($categoryData['children'])) {
            foreach ($categoryData['children'] as $childData) {
                $this->createCategory($childData, $category);
            }
        }
    }
}
