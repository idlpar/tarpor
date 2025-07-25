<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tarpor.com',
            'phone' => '01612233369',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'verified_at' => now(),
            'is_verified' => true,
            'profile_photo' => 'avatars/admin.jpg',
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@tarpor.com',
            'phone' => '01551805527',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'verified_at' => now(),
            'is_verified' => true,
            'profile_photo' => 'avatars/staff.jpg',
        ]);

        User::factory()->count(10)->create([
            'role' => 'user',
            'profile_photo' => fn() => 'avatars/user' . rand(1, 5) . '.jpg',
        ]);
    }
}
