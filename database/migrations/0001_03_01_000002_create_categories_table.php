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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 255); // Category name
            $table->string('slug', 255)->unique(); // URL-friendly slug
            $table->unsignedBigInteger('parent_id')->nullable(); // Parent category ID
            $table->text('description')->nullable(); // Category description
            $table->string('image')->nullable(); // Category image URL
            $table->enum('status', ['active', 'inactive'])->default('active'); // Category status
            $table->integer('position')->default(0);
            $table->timestamps(); // created_at and updated_at

            $table->index('parent_id');
            $table->index('name');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
