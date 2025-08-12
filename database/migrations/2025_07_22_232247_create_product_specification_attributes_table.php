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
        Schema::create('product_specification_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_specification_group_id');
            $table->string('name');
            $table->string('unit')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_specification_group_id', 'psa_group_fk')->references('id')->on('product_specification_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specification_attributes');
    }
};
