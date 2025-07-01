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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Product foreign key
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User foreign key
            $table->tinyInteger('rating'); // Rating (1 to 5)
            $table->string('title', 255)->nullable(); // Review title
            $table->text('comment')->nullable(); // Review comment
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Review status
            $table->timestamps(); // created_at and updated_at

            $table->index('product_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
