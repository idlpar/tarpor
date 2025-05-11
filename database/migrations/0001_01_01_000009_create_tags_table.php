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
        Schema::create('tags', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 255)->unique(); // Tag name (stored in lowercase)
            $table->string('slug', 255)->unique(); // URL-friendly slug
            $table->text('description')->nullable(); // Tag description
            $table->unsignedBigInteger('product_count')->default(0); // Number of products associated with this tag
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at for soft deletes

            // Indexes
            $table->index('name'); // Index for tag name
            $table->index('slug'); // Index for tag slug

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
