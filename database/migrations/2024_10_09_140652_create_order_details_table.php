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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();  // Primary key (auto-incrementing)

            // Customer name details
            $table->string('first_name', 255);
            $table->string('last_name', 255);

            // Contact details (optional)
            $table->string('email')->nullable();  // Optional
            $table->string('phone')->nullable();  // Optional

            // Address fields
            $table->string('address1', 255);
            $table->string('address2', 255)->nullable();  // Optional address line
            $table->string('city', 255);
            $table->string('state', 50);  // Should be sufficient for most cases (use 50 for US, adjust for others)
            $table->string('zip', 20);  // Reduced to 20, as ZIP codes are typically shorter (adjust as needed)

            // Country code (use ISO 3166-1 alpha-2 or alpha-3)
            $table->string('country_code', 2);  // Changed to 2 characters for ISO 3166-1 alpha-2 country code

            // Timestamp columns (created_at, updated_at)
            $table->timestamps();

            // Optional: Adding indexes on frequently searched fields
            $table->index('email');
            $table->index('phone');
            $table->index('zip');
            $table->index('country_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
