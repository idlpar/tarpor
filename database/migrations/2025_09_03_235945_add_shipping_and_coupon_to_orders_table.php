<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->foreignId('shipping_method_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_method_id')) {
                $table->dropForeign(['shipping_method_id']);
                $table->dropColumn('shipping_method_id');
            }
            if (Schema::hasColumn('orders', 'coupon_id')) {
                $table->dropForeign(['coupon_id']);
                $table->dropColumn('coupon_id');
            }
        });
    }
};
