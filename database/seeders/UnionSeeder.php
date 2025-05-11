<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Union;

class UnionSeeder extends Seeder
{
    public function run(): void
    {
        Union::create([
            'upazila_id' => 1,
            'name' => 'Tetuljhora',
            'bn_name' => 'তেঁতুলঝোড়া',
        ]);

        Union::create([
            'upazila_id' => 2,
            'name' => 'Forhadabad',
            'bn_name' => 'ফরহাদাবাদ',
        ]);
    }
}
