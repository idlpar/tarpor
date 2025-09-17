<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use App\Models\InventoryItem;
use App\Models\ProductPricingTier;
use App\Models\ProductSpecialOffer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create product attributes: Size and Color
        $sizeAttr = ProductAttribute::create(['name' => 'Size', 'slug' => 'size']);
        $colorAttr = ProductAttribute::create(['name' => 'Color', 'slug' => 'color']);

        // Attribute values
        $sizes = collect([
            ['value' => '4-5 Yrs'],
            ['value' => '6-7 Yrs'],
            ['value' => 'M'],
            ['value' => 'L'],
        ])->map(fn($data) => ProductAttributeValue::create([
            'attribute_id' => $sizeAttr->id,
            'value' => $data['value'],
        ]));

        $colors = collect([
            ['value' => 'Red', 'color_code' => '#FF0000'],
            ['value' => 'Blue', 'color_code' => '#0000FF'],
            ['value' => 'Black', 'color_code' => '#000000'],
        ])->map(fn($data) => ProductAttributeValue::create([
            'attribute_id' => $colorAttr->id,
            'value' => $data['value'],
            'color_code' => $data['color_code'],
        ]));

        // Create product 1: Boys Cotton T-Shirt
        $product1 = Product::create([
            'name' => 'Boys Cotton T-Shirt – Red & Blue Combo',
            'slug' => Str::slug('Boys Cotton T-Shirt – Red & Blue Combo'),
            'description' => 'Pack of 2 soft cotton T-shirts for boys with playful prints. Red and Blue colors, perfect for summer.',
            'short_description' => 'Boys Cotton T-Shirts (Pack of 2)',
            'type' => 'variable',
            'price' => 500.00,
            'sale_price' => 450.00,
            'cost_price' => 300.00,
            'sku' => 'TSHRT-B-KID',
            'barcode' => '1234567890123',
            'stock_quantity' => 0,
            'stock_status' => 'in_stock',
            'inventory_tracking' => true,
            'low_stock_threshold' => 5,
            'weight' => 0.3,
            'length' => 10.0,
            'width' => 8.0,
            'height' => 1.0,
            'brand_id' => 1, // Ensure brand_id 1 exists
            'thumbnail' => 'products/tshirt-combo.jpg',
            'views' => 120,
            'status' => 'published',
            'is_featured' => true,
        ]);

        // Attach categories to product 1
        $categoryIds = Category::whereIn('slug', ['kids', 'summer-wear'])->pluck('id')->toArray();
        $product1->categories()->syncWithoutDetaching($categoryIds);

        // Variants for product 1
        $variant1 = ProductVariant::create([
            'product_id' => $product1->id,
            'sku' => 'TSHRT-B-KID-RED-4',
            'price' => 500.00,
            'sale_price' => 450.00,
            'cost_price' => 300.00,
            'stock_quantity' => 10,
            'stock_status' => 'in_stock',
            'weight' => 0.15,
            'length' => 10,
            'width' => 8,
            'height' => 1,
            'is_default' => true,
            'image' => 'products/variants/red-4.jpg',
        ]);

        $variant2 = ProductVariant::create([
            'product_id' => $product1->id,
            'sku' => 'TSHRT-B-KID-BLU-6',
            'price' => 500.00,
            'sale_price' => 450.00,
            'cost_price' => 300.00,
            'stock_quantity' => 15,
            'stock_status' => 'in_stock',
            'weight' => 0.15,
            'length' => 10,
            'width' => 8,
            'height' => 1,
            'is_default' => false,
            'image' => 'products/variants/blue-6.jpg',
        ]);

        // Attach attribute values to variants
        $variant1->attributeValues()->attach([$sizes[0]->id, $colors[0]->id]); // 4-5 Yrs, Red
        $variant2->attributeValues()->attach([$sizes[1]->id, $colors[1]->id]); // 6-7 Yrs, Blue

        // Inventory for product 1
        InventoryItem::create([
            'product_id' => $product1->id,
            'variant_id' => $variant1->id,
            'quantity' => 10,
            'low_stock_threshold' => 2,
            'location' => 'Warehouse A - Rack 1',
            'batch_number' => 'B2301',
        ]);

        InventoryItem::create([
            'product_id' => $product1->id,
            'variant_id' => $variant2->id,
            'quantity' => 15,
            'low_stock_threshold' => 2,
            'location' => 'Warehouse A - Rack 1',
            'batch_number' => 'B2302',
        ]);

        // Pricing Tiers for product 1
        ProductPricingTier::create([
            'product_id' => $product1->id,
            'variant_id' => $variant1->id,
            'min_quantity' => 5,
            'max_quantity' => 10,
            'price' => 430.00,
        ]);

        ProductPricingTier::create([
            'product_id' => $product1->id,
            'variant_id' => $variant2->id,
            'min_quantity' => 10,
            'price' => 400.00,
        ]);

        // Special Offers for product 1
        ProductSpecialOffer::create([
            'product_id' => $product1->id,
            'variant_id' => null,
            'name' => 'Eid Offer',
            'discount_amount' => 50,
            'discount_type' => 'fixed',
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(7),
            'is_active' => true,
        ]);

        // Create product 2: Men’s Jacket
        $product2 = Product::create([
            'name' => 'Men’s Casual Jacket',
            'slug' => Str::slug('Men’s Casual Jacket'),
            'description' => 'Stylish black jacket for men, perfect for casual and winter wear.',
            'short_description' => 'Men’s Casual Jacket',
            'type' => 'variable',
            'price' => 1500.00,
            'sale_price' => 1350.00,
            'cost_price' => 900.00,
            'sku' => 'JCKT-MEN',
            'barcode' => '9876543210987',
            'stock_quantity' => 0,
            'stock_status' => 'in_stock',
            'inventory_tracking' => true,
            'low_stock_threshold' => 5,
            'weight' => 0.8,
            'length' => 20.0,
            'width' => 15.0,
            'height' => 2.0,
            'brand_id' => 1, // Ensure brand_id 1 exists
            'thumbnail' => 'products/mens-jacket.jpg',
            'views' => 80,
            'status' => 'published',
            'is_featured' => false,
        ]);

        // Attach categories to product 2
        $categoryIds2 = Category::whereIn('slug', ['men', 'winter-wear'])->pluck('id')->toArray();
        $product2->categories()->syncWithoutDetaching($categoryIds2);

        // Variants for product 2
        $variant3 = ProductVariant::create([
            'product_id' => $product2->id,
            'sku' => 'JCKT-MEN-BLK-M',
            'price' => 1500.00,
            'sale_price' => 1350.00,
            'cost_price' => 900.00,
            'stock_quantity' => 8,
            'stock_status' => 'in_stock',
            'weight' => 0.8,
            'length' => 20,
            'width' => 15,
            'height' => 2,
            'is_default' => true,
            'image' => 'products/variants/black-m.jpg',
        ]);

        $variant4 = ProductVariant::create([
            'product_id' => $product2->id,
            'sku' => 'JCKT-MEN-BLK-L',
            'price' => 1500.00,
            'sale_price' => 1350.00,
            'cost_price' => 900.00,
            'stock_quantity' => 12,
            'stock_status' => 'in_stock',
            'weight' => 0.8,
            'length' => 20,
            'width' => 15,
            'height' => 2,
            'is_default' => false,
            'image' => 'products/variants/black-l.jpg',
        ]);

        // Attach attribute values to variants
        $variant3->attributeValues()->attach([$sizes[2]->id, $colors[2]->id]); // M, Black
        $variant4->attributeValues()->attach([$sizes[3]->id, $colors[2]->id]); // L, Black

        // Inventory for product 2
        InventoryItem::create([
            'product_id' => $product2->id,
            'variant_id' => $variant3->id,
            'quantity' => 8,
            'low_stock_threshold' => 2,
            'location' => 'Warehouse B - Rack 2',
            'batch_number' => 'B2303',
        ]);

        InventoryItem::create([
            'product_id' => $product2->id,
            'variant_id' => $variant4->id,
            'quantity' => 12,
            'low_stock_threshold' => 2,
            'location' => 'Warehouse B - Rack 2',
            'batch_number' => 'B2304',
        ]);

        // Pricing Tiers for product 2
        ProductPricingTier::create([
            'product_id' => $product2->id,
            'variant_id' => $variant3->id,
            'min_quantity' => 3,
            'max_quantity' => 7,
            'price' => 1300.00,
        ]);

        ProductPricingTier::create([
            'product_id' => $product2->id,
            'variant_id' => $variant4->id,
            'min_quantity' => 8,
            'price' => 1250.00,
        ]);

        // Special Offers for product 2
        ProductSpecialOffer::create([
            'product_id' => $product2->id,
            'variant_id' => null,
            'name' => 'Winter Sale',
            'discount_amount' => 100,
            'discount_type' => 'fixed',
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(7),
            'is_active' => true,
        ]);
    }
}
