<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Upazila;

class UpazilaSeeder extends Seeder
{
    public function run(): void
    {
        Upazila::create([
            'district_id' => 1,
            'name' => 'Savar',
            'bn_name' => 'সাভার',
        ]);

        Upazila::create([
            'district_id' => 2,
            'name' => 'Hathazari',
            'bn_name' => 'হাটহাজারী',
        ]);
    }
}
