<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id(); // unsigned big integer primary key 'id'
            $table->string('name')->unique()->comment('Name of the collection');
            $table->string('slug')->unique()->comment('URL-friendly identifier');
            $table->text('description')->nullable()->comment('Detailed description of the collection');
            $table->string('image')->nullable()->comment('Path to collection image');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Status of the collection');
            $table->softDeletes(); // For soft deletion support
            $table->timestamps();  // created_at and updated_at

            // Indexes for faster searching
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
}
