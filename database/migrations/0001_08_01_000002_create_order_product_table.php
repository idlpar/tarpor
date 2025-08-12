<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();

            // Add indexes
            $table->index('order_id', 'idx_order_product_order_id');
            $table->index('product_id', 'idx_order_product_product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
