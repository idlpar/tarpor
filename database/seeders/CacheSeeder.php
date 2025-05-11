<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CacheSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cache')->insert([
            'key' => 'site_settings',
            'value' => json_encode(['theme' => 'dark', 'currency' => 'BDT']),
            'expiration' => now()->addDays(30)->timestamp,
        ]);
    }
}
