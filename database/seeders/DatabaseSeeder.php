<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            
            JobSeeder::class,
//            SeoMetasSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
//            ProductSeeder::class,
//            ProductReviewSeeder::class,
//            CategoryProductSeeder::class,
            TagSeeder::class,
//            ProductTagSeeder::class,
//            MediaSeeder::class,
            OrderSeeder::class,
            DivisionSeeder::class,
            DistrictSeeder::class,
//            UpazilaSeeder::class,
//            UnionSeeder::class,
//            AddressSeeder::class,
//            SeoSeeder::class,
            ProductAttributeSeeder::class,
            CollectionSeeder::class,
            LabelSeeder::class,
            ProductAttributeValueSeeder::class,
            FaqSeeder::class,
            CouponSeeder::class,
            NewsletterSubscriptionSeeder::class,
            ProductSpecificationTableSeeder::class,
            ProductSpecificationGroupSeeder::class,
            ProductSpecificationTableGroupSeeder::class,
            ProductSpecificationSeeder::class,
        ]);
    }
}
