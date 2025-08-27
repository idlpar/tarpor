<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('short_id', 16)->nullable()->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->decimal('delivery_charge', 8, 2)->default(0.00);
            $table->decimal('coupon_discount', 8, 2)->default(0.00);
            $table->decimal('reward_discount', 8, 2)->default(0.00);
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->json('attribution_data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('address_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
