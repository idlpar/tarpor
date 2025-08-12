<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_cross_selling', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('cross_selling_product_id')->constrained('products')->onDelete('cascade');
            $table->primary(['product_id', 'cross_selling_product_id'], 'product_cross_selling_primary');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_cross_selling');
    }
};
