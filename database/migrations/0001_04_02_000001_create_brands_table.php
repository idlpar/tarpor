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
        Schema::create('brands', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 255); // Brand name
            $table->string('slug', 255)->unique(); // URL-friendly slug
            $table->foreignId('logo_id')->nullable()->constrained('media')->onDelete('set null'); // Brand logo ID
            $table->text('description')->nullable(); // Brand description
            $table->enum('status', ['active', 'inactive'])->default('active'); // Brand status
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
