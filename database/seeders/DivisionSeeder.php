<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            ['name' => 'Chittagong', 'bn_name' => 'চট্টগ্রাম', 'url' => 'https://chittagong.gov.bd'],
            ['name' => 'Rajshahi', 'bn_name' => 'রাজশাহী', 'url' => 'https://rajshahi.gov.bd'],
            ['name' => 'Khulna', 'bn_name' => 'খুলনা', 'url' => 'https://khulna.gov.bd'],
            ['name' => 'Barisal', 'bn_name' => 'বরিশাল', 'url' => 'https://barisal.gov.bd'],
            ['name' => 'Sylhet', 'bn_name' => 'সিলেট', 'url' => 'https://sylhet.gov.bd'],
            ['name' => 'Dhaka', 'bn_name' => 'ঢাকা', 'url' => 'https://dhaka.gov.bd'],
            ['name' => 'Rangpur', 'bn_name' => 'রংপুর', 'url' => 'https://rangpur.gov.bd'],
            ['name' => 'Mymensingh', 'bn_name' => 'ময়মনসিংহ', 'url' => 'https://mymensingh.gov.bd'],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
