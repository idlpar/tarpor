<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        District::create([
            'division_id' => 1,
            'name' => 'Dhaka',
            'bn_name' => 'ঢাকা',
            'lat' => '23.8103',
            'lon' => '90.4125',
        ]);

        District::create([
            'division_id' => 2,
            'name' => 'Chittagong',
            'bn_name' => 'চট্টগ্রাম',
            'lat' => '22.3569',
            'lon' => '91.7832',
        ]);
    }
}
