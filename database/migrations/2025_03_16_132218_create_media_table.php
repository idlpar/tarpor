<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Model relationship (make nullable for standalone gallery)
            $table->string('model_type')->nullable()->comment('Class name of the model this media belongs to');
            $table->unsignedBigInteger('model_id')->nullable()->comment('ID of the model this media belongs to');

            // File identification
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name')->default('default');
            $table->string('name'); // Original name without extension
            $table->string('file_name'); // Actual stored filename with extension
            $table->string('file_hash')->unique()->comment('MD5 hash of file contents');
            $table->string('mime_type');
            $table->string('disk')->default('public');
            $table->string('conversions_disk')->nullable()->comment('Disk for conversions if different');

            // File metadata
            $table->unsignedBigInteger('size');
            $table->json('dimensions')->nullable()->comment('Width and height for images/videos');
            $table->integer('duration')->nullable()->comment('Duration in seconds for videos/audio');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->string('title')->nullable();

            // Processing data
            $table->json('manipulations')->nullable();
            $table->json('custom_properties')->nullable();
            $table->json('generated_conversions')->nullable();
            $table->json('responsive_images')->nullable();

            // Folder structure
            $table->string('directory')->default('');

            // Organization
            $table->unsignedInteger('order_column')->nullable();
            $table->boolean('is_featured')->default(false);

            // Timestamps
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['model_type', 'model_id']);
            $table->index(['collection_name', 'disk', 'directory']);
            $table->index('file_name');
            $table->index('file_hash');
        });

        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('lft')->nullable()->index();
            $table->unsignedBigInteger('rgt')->nullable()->index();
            $table->unsignedBigInteger('depth')->default(0)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('media_folders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
        Schema::dropIfExists('media_folders');
    }
};
