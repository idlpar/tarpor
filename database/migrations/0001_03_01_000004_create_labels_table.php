<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->id(); // unsigned big integer primary key 'id'
            $table->string('name')->unique()->comment('Name of the label');
            $table->string('slug')->unique()->comment('URL-friendly identifier');
            $table->text('description')->nullable()->comment('Detailed description of the label');
            $table->string('image')->nullable()->comment('Optional image/icon for the label');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Status of the label');
            $table->softDeletes(); // For soft deletion support
            $table->timestamps();  // created_at and updated_at

            // Index for faster searching by status
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
}
