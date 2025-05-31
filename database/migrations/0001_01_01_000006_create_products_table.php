<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('sku', 50)->unique()->nullable(); // Stock Keeping Unit
            $table->string('name', 255); // Product name
            $table->string('slug', 255)->unique(); // URL-friendly slug
            $table->text('description'); // Product description
            $table->text('short_description')->nullable(); // Short description
            $table->decimal('price', 6, 2)->default(0.00); // Product price
            $table->decimal('sale_price', 6, 2)->nullable(); // Sale price
            $table->decimal('cost_price', 6, 2)->nullable(); // Cost price
            $table->integer('stock_quantity')->default(0); // Stock quantity
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'backorder'])->default('in_stock'); // Stock status
            $table->json('tags')->nullable();
            $table->json('product_collections')->nullable();
            $table->json('labels')->nullable();
            $table->json('related_products')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('barcode', 100)->nullable()->unique();
            $table->decimal('discount', 6, 2)->nullable(); // Discount percentage or amount
            $table->boolean('inventory_tracking')->default(true);
            $table->string('weight')->nullable(); // Product weight
            $table->string('length')->nullable(); // Product length
            $table->string('width')->nullable(); // Product width
            $table->string('height')->nullable(); // Product height
            $table->unsignedBigInteger('brand_id')->nullable(); // Brand foreign key
            $table->json('attributes')->nullable(); // Product attributes (JSON)
            $table->json('images')->nullable(); // Product images (JSON)
            $table->string('thumbnail')->nullable(); // Thumbnail image URL
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft'); // Product status
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at for soft deletes

            // Foreign key constraints
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

            // Indexes
            $table->index('name'); // Index for product name
            $table->index('slug'); // Index for product slug
            $table->index('sku'); // Index for product SKU
            $table->index('price'); // Index for price
            $table->index('sale_price'); // Index for sale price
            $table->index('status'); // Index for status
            $table->index('product_collections');
            $table->index('labels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
