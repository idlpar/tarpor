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
        Schema::create('label_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('label_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unique(['label_id', 'product_id']);
            $table->index(['label_id', 'product_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_product');
    }
};
