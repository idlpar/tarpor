<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        Division::create([
            'name' => 'Dhaka',
            'bn_name' => 'ঢাকা',
            'url' => 'https://dhaka.gov.bd',
        ]);

        Division::create([
            'name' => 'Chittagong',
            'bn_name' => 'চট্টগ্রাম',
            'url' => 'https://chittagong.gov.bd',
        ]);
    }
}
