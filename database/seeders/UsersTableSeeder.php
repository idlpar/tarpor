<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        User::create([
//            'name' => 'Super Admin',
//            'email' => 'super@admin.com',
//            'password' => bcrypt('password'), // Temporary password
//            'role' => 'super'
//        ]);


        $users = [
            [
                'name' => 'Super Parvez',
                'email' => 'super@tarpor.com',
                'password' => Hash::make('password'),
                'role' => 'super',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'Admin Parvez',
                'email' => 'admin@tarpor.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'User Parvez',
                'email' => 'user@tarpor.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'Guest Parvez',
                'email' => 'guest@tarpor.com',
                'password' => Hash::make('password'),
                'role' => 'guest',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'David Williams',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'Eva Green',
                'email' => 'eva@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'Frank White',
                'email' => 'frank@example.com',
                'password' => Hash::make('password'),
                'role' => 'super',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'Grace Lee',
                'email' => 'grace@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_verified' => true, // Add this line
            ],
            [
                'name' => 'Henry Brown',
                'email' => 'henry@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_verified' => true, // Add this line
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
