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
                'position' => 1,
                'children' => [
                    [
                        'name' => 'Computers & Accessories',
                        'position' => 1,
                        'children' => [
                            ['name' => 'Laptops', 'position' => 1],
                            ['name' => 'Desktops', 'position' => 2],
                            ['name' => 'Monitors', 'position' => 3],
                            ['name' => 'Keyboards & Mice', 'position' => 4],
                            ['name' => 'Printers & Scanners', 'position' => 5],
                        ],
                    ],
                    [
                        'name' => 'Phones & Tablets',
                        'position' => 2,
                        'children' => [
                            ['name' => 'Smartphones', 'position' => 1],
                            ['name' => 'Tablets', 'position' => 2],
                            ['name' => 'Cases & Covers', 'position' => 3],
                            ['name' => 'Chargers & Cables', 'position' => 4],
                        ],
                    ],
                    [
                        'name' => 'Audio & Video',
                        'position' => 3,
                        'children' => [
                            ['name' => 'Headphones', 'position' => 1],
                            ['name' => 'Speakers', 'position' => 2],
                            ['name' => 'Televisions', 'position' => 3],
                            ['name' => 'Cameras', 'position' => 4],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Fashion',
                'position' => 2,
                'children' => [
                    [
                        'name' => 'Men\'s Fashion',
                        'position' => 1,
                        'children' => [
                            ['name' => 'T-Shirts & Polos', 'position' => 1],
                            ['name' => 'Shirts', 'position' => 2],
                            ['name' => 'Jeans & Trousers', 'position' => 3],
                            ['name' => 'Shoes', 'position' => 4],
                        ],
                    ],
                    [
                        'name' => 'Women\'s Fashion',
                        'position' => 2,
                        'children' => [
                            ['name' => 'Dresses', 'position' => 1],
                            ['name' => 'Tops & Tees', 'position' => 2],
                            ['name' => 'Skirts & Shorts', 'position' => 3],
                            ['name' => 'Handbags', 'position' => 4],
                        ],
                    ],
                    [
                        'name' => 'Kid\'s Fashion',
                        'position' => 3,
                        'children' => [
                            ['name' => 'Boys\' Clothing', 'position' => 1],
                            ['name' => 'Girls\' Clothing', 'position' => 2],
                            ['name' => 'Toys', 'position' => 3],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Home & Garden',
                'position' => 3,
                'children' => [
                    [
                        'name' => 'Furniture',
                        'position' => 1,
                        'children' => [
                            ['name' => 'Living Room Furniture', 'position' => 1],
                            ['name' => 'Bedroom Furniture', 'position' => 2],
                            ['name' => 'Office Furniture', 'position' => 3],
                        ],
                    ],
                    [
                        'name' => 'Kitchen & Dining',
                        'position' => 2,
                        'children' => [
                            ['name' => 'Cookware', 'position' => 1],
                            ['name' => 'Tableware', 'position' => 2],
                            ['name' => 'Appliances', 'position' => 3],
                        ],
                    ],
                    [
                        'name' => 'Garden & Outdoor',
                        'position' => 3,
                        'children' => [
                            ['name' => 'Gardening Tools', 'position' => 1],
                            ['name' => 'Outdoor Furniture', 'position' => 2],
                            ['name' => 'Grills', 'position' => 3],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Networking',
                'position' => 4,
                'children' => [
                    [
                        'name' => 'Routers',
                        'position' => 1,
                        'children' => [],
                    ],
                    [
                        'name' => 'Switches',
                        'position' => 2,
                        'children' => [],
                    ],
                    [
                        'name' => 'Modems',
                        'position' => 3,
                        'children' => [],
                    ],
                    [
                        'name' => 'Network Adapters',
                        'position' => 4,
                        'children' => [],
                    ],
                    [
                        'name' => 'Cables & Tools',
                        'position' => 5,
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
            'position' => $categoryData['position'] ?? 0,
        ]);

        if (isset($categoryData['children'])) {
            foreach ($categoryData['children'] as $childData) {
                $this->createCategory($childData, $category);
            }
        }
    }
}
