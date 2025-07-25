<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSpecificationTable;
use App\Models\ProductSpecificationGroup;

class ProductSpecificationTableGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apparelTable = ProductSpecificationTable::where('name', 'Apparel Specifications')->first();
        $footwearTable = ProductSpecificationTable::where('name', 'Footwear Specifications')->first();
        $accessoryTable = ProductSpecificationTable::where('name', 'Accessory Details')->first();
        $careTable = ProductSpecificationTable::where('name', 'Care Instructions')->first();

        $generalGroup = ProductSpecificationGroup::where('name', 'General Features')->first();
        $materialGroup = ProductSpecificationGroup::where('name', 'Material & Care')->first();
        $dimensionsGroup = ProductSpecificationGroup::where('name', 'Dimensions & Fit')->first();
        $performanceGroup = ProductSpecificationGroup::where('name', 'Performance & Technology')->first();
        $originGroup = ProductSpecificationGroup::where('name', 'Origin & Manufacturing')->first();
        $sustainabilityGroup = ProductSpecificationGroup::where('name', 'Sustainability')->first();
        $footwearSpecificsGroup = ProductSpecificationGroup::where('name', 'Footwear Specifics')->first();
        $accessorySpecificsGroup = ProductSpecificationGroup::where('name', 'Accessory Specifics')->first();

        // Link Apparel Specifications Table to groups
        if ($apparelTable) {
            $apparelTable->groups()->attach($generalGroup->id, ['order' => 1]);
            $apparelTable->groups()->attach($materialGroup->id, ['order' => 2]);
            $apparelTable->groups()->attach($dimensionsGroup->id, ['order' => 3]);
            $apparelTable->groups()->attach($performanceGroup->id, ['order' => 4]);
            $apparelTable->groups()->attach($originGroup->id, ['order' => 5]);
            $apparelTable->groups()->attach($sustainabilityGroup->id, ['order' => 6]);
        }

        // Link Footwear Specifications Table to groups
        if ($footwearTable) {
            $footwearTable->groups()->attach($generalGroup->id, ['order' => 1]);
            $footwearTable->groups()->attach($materialGroup->id, ['order' => 2]);
            $footwearTable->groups()->attach($footwearSpecificsGroup->id, ['order' => 3]);
            $footwearTable->groups()->attach($performanceGroup->id, ['order' => 4]);
        }

        // Link Accessory Details Table to groups
        if ($accessoryTable) {
            $accessoryTable->groups()->attach($generalGroup->id, ['order' => 1]);
            $accessoryTable->groups()->attach($materialGroup->id, ['order' => 2]);
            $accessoryTable->groups()->attach($accessorySpecificsGroup->id, ['order' => 3]);
        }

        // Link Care Instructions Table to groups
        if ($careTable) {
            $careTable->groups()->attach($materialGroup->id, ['order' => 1]);
        }
    }
}
