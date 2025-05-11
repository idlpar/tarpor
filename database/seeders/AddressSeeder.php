<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        Address::create([
            'user_id' => 3,
            'label' => 'Default Address',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Savar',
            'union' => 'Tetuljhora',
            'street_address' => '123 Dhaka Street',
            'postal_code' => '1215',
            'is_default' => true,
        ]);
    }
}
