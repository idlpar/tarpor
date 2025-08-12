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
        Schema::create('product_specification_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // e.g., 'technical', 'general'
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_specification_table_group', function (Blueprint $table) {
            $table->foreignId('product_specification_table_id')->constrained('product_specification_tables', 'id', 'pst_tbl_fk')->onDelete('cascade');
            $table->foreignId('product_specification_group_id')->constrained('product_specification_groups', 'id', 'pst_grp_fk')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->primary(['product_specification_table_id', 'product_specification_group_id'], 'pstg_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specification_tables');
    }
};
