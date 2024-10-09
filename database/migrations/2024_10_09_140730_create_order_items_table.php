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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Using constrained() for foreign keys with cascade on delete
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Use unsigned integers for quantity and ensure unit_price is decimal
            $table->unsignedInteger('quantity'); // Use unsigned since quantities cannot be negative
            $table->decimal('unit_price', 10, 2); // Changed to decimal to store prices with precision

            // Timestamps
            $table->timestamps();

            // Optional: Add indexes explicitly for performance (though Laravel should add these automatically)
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
