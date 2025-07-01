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
        Schema::create('product_tag', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->unsignedBigInteger('product_id'); // Foreign key to products table
                $table->unsignedBigInteger('tag_id'); // Foreign key to tags table
                $table->timestamps(); // created_at and updated_at

                // Foreign key constraints
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');

                // Composite unique index to prevent duplicate entries
                $table->unique(['product_id', 'tag_id']);
                $table->index(['product_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tag');
    }
};
