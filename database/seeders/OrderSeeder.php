<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Skipping OrderSeeder.');
            return;
        }
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping OrderSeeder.');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $userId = $users->random()->id;
            $status = $statuses[array_rand($statuses)];
            $numProducts = rand(1, min(3, $products->count()));
            $totalPrice = 0;
            $totalQuantity = 0;
            $productData = [];

            $selectedProducts = $products->random($numProducts);
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 5);
                $price = $product->sale_price ?? $product->price;
                $totalPrice += $price * $quantity;
                $totalQuantity += $quantity;
                $productData[$product->id] = [
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            // Use the first selected product's ID for product_id
            $firstProductId = $selectedProducts->first()->id;

            $order = Order::create([
                'user_id' => $userId,
                'product_id' => $firstProductId, // Satisfy non-nullable product_id
                'quantity' => $totalQuantity,
                'address' => "Address $i, Dhaka",
                'status' => $status,
                'total_price' => $totalPrice,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);

            $order->products()->attach($productData);

            foreach ($productData as $productId => $data) {
                $product = Product::find($productId);
                if ($product && $product->inventory_tracking) {
                    $product->decrement('stock_quantity', $data['quantity']);
                }
            }
        }

        $this->command->info('OrderSeeder completed successfully.');
    }
}
