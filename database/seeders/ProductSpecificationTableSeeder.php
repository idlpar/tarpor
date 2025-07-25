<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSpecificationTable;

class ProductSpecificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            ['name' => 'Apparel Specifications', 'type' => 'technical'],
            ['name' => 'Footwear Specifications', 'type' => 'technical'],
            ['name' => 'Accessory Details', 'type' => 'general'],
            ['name' => 'Care Instructions', 'type' => 'general'],
        ];

        foreach ($tables as $tableData) {
            ProductSpecificationTable::create($tableData);
        }
    }
}
