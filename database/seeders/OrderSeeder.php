<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        for ($i = 1; $i <= 10; $i++) {
            $userId = rand(1, 5); // Assuming users with IDs 1-5 exist
            $status = $statuses[array_rand($statuses)];
            $numProducts = rand(1, 3); // 1-3 products per order
            $totalPrice = 0;
            $totalQuantity = 0; // Sum of quantities for all products
            $productData = [];

            // Select random products
            $selectedProducts = $products->random($numProducts);
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 5);
                $price = $product->sale_price ?? $product->price;
                $totalPrice += $price * $quantity;
                $totalQuantity += $quantity; // Add to total quantity
                $productData[$product->id] = [
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            // Assign the first selected product’s ID to product_id
            $firstProductId = $selectedProducts->first()->id;

            // Create order with product_id and quantity
            $order = Order::create([
                'user_id' => $userId,
                'product_id' => $firstProductId, // Satisfy non-nullable product_id
                'quantity' => $totalQuantity, // Sum of all product quantities
                'address' => "Address $i, Dhaka",
                'status' => $status,
                'total_price' => $totalPrice,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);

            // Attach products to order via pivot table
            $order->products()->attach($productData);

            // Update product stock
            foreach ($productData as $productId => $data) {
                Product::find($productId)->decrement('stock_quantity', $data['quantity']);
            }
        }
    }
}
