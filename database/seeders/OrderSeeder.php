<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::create([
            'user_id' => 3,
            'product_id' => 1,
            'quantity' => 2,
            'total_price' => 25.98,
            'address' => '123 Dhaka Street, Dhaka',
            'status' => 'pending',
        ]);
    }
}
