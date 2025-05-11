<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CacheSeeder::class,
            JobSeeder::class,
            SeoMetasSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ProductReviewSeeder::class,
            CategoryProductSeeder::class,
            TagSeeder::class,
            ProductTagSeeder::class,
            MediaSeeder::class,
            OrderSeeder::class,
            DivisionSeeder::class,
            DistrictSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,
            AddressSeeder::class,
            SeoSeeder::class,
        ]);
    }
}
