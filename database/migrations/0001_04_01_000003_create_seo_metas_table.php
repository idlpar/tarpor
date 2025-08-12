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
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->string('meta_title');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->json('schema_markup')->nullable();
            $table->string('robots')->nullable();
            $table->string('entity_type'); // Polymorphic entity type (e.g., "App\Models\Product")
            $table->unsignedBigInteger('entity_id'); // Polymorphic entity ID
            $table->timestamps();

            // Indexes
            $table->index('entity_type'); // Index for entity_type
            $table->index('entity_id'); // Index for entity_id
            $table->unique(['entity_type', 'entity_id']); // Unique index for entity_type + entity_id
            $table->index('meta_title'); // Index for meta_title (optional)
            $table->index('robots'); // Index for robots (optional)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
