<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductReview;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        ProductReview::create([
            'product_id' => 1,
            'user_id' => 3,
            'rating' => 4,
            'title' => 'Great T-Shirt',
            'comment' => 'Really comfortable and fits well!',
            'status' => 'approved',
        ]);
    }
}
