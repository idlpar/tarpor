<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // Basic product info
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->enum('type', ['simple', 'variable'])->default('simple');

            // Pricing
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();

            // Inventory
            $table->string('sku', 50)->unique()->nullable();
            $table->string('barcode', 100)->unique()->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'backorder'])->default('in_stock');
            $table->boolean('inventory_tracking')->default(true);
            $table->integer('low_stock_threshold')->nullable();

            // Shipping
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->integer('min_order_quantity')->default(0);
            $table->integer('max_order_quantity')->default(0);

            // Organization
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('views')->default(0);

            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['name', 'slug', 'sku']);
            $table->index('price');
            $table->index('sale_price');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
