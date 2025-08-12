<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'SUMMER20',
                'type' => 'percentage',
                'value' => 20.00,
                'min_amount' => 50.00,
                'expires_at' => Carbon::now()->addDays(30),
                'usage_limit' => 100,
                'times_used' => 0,
                'max_discount_amount' => null,
            ],
            [
                'code' => 'SAVE10',
                'type' => 'fixed',
                'value' => 10.00,
                'min_amount' => 30.00,
                'expires_at' => Carbon::now()->addDays(60),
                'usage_limit' => 200,
                'times_used' => 0,
                'max_discount_amount' => null,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'fixed',
                'value' => 0.00, // Represents free shipping
                'min_amount' => 25.00,
                'expires_at' => Carbon::now()->addDays(90),
                'usage_limit' => 500,
                'times_used' => 0,
                'max_discount_amount' => null,
            ],
            [
                'code' => 'NEWUSER15',
                'type' => 'percentage',
                'value' => 15.00,
                'min_amount' => 40.00,
                'expires_at' => Carbon::now()->addDays(120),
                'usage_limit' => 50,
                'times_used' => 0,
                'max_discount_amount' => 20.00,
            ],
            [
                'code' => 'FLASH50',
                'type' => 'percentage',
                'value' => 50.00,
                'min_amount' => 100.00,
                'expires_at' => Carbon::now()->addDays(7),
                'usage_limit' => 10,
                'times_used' => 0,
                'max_discount_amount' => 50.00,
            ],
            [
                'code' => 'VIP25',
                'type' => 'percentage',
                'value' => 25.00,
                'min_amount' => 75.00,
                'expires_at' => Carbon::now()->addDays(180),
                'usage_limit' => 30,
                'times_used' => 0,
                'max_discount_amount' => null,
            ],
            [
                'code' => 'WELCOMEBACK',
                'type' => 'fixed',
                'value' => 5.00,
                'min_amount' => 20.00,
                'expires_at' => Carbon::now()->addDays(30),
                'usage_limit' => 150,
                'times_used' => 0,
                'max_discount_amount' => null,
            ],
            [
                'code' => 'HOLIDAY10',
                'type' => 'percentage',
                'value' => 10.00,
                'min_amount' => 60.00,
                'expires_at' => Carbon::now()->addDays(45),
                'usage_limit' => 300,
                'times_used' => 0,
                'max_discount_amount' => 15.00,
            ],
            [
                'code' => 'BIGSPENDER',
                'type' => 'fixed',
                'value' => 25.00,
                'min_amount' => 150.00,
                'expires_at' => Carbon::now()->addDays(90),
                'usage_limit' => 20,
                'times_used' => 0,
                'max_discount_amount' => null,
            ],
            [
                'code' => 'APPONLY',
                'type' => 'percentage',
                'value' => 10.00,
                'min_amount' => 0.00,
                'expires_at' => Carbon::now()->addDays(365),
                'usage_limit' => null,
                'times_used' => 0,
                'max_discount_amount' => 10.00,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}
