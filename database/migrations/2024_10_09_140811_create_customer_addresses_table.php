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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            // Add the customer_id field to link the customer with the address
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // Address details
            $table->string('type', 50);           // Address type (e.g., home, work)
            $table->string('address1', 255);      // Primary address
            $table->string('address2', 255)->nullable(); // Secondary address (optional)
            $table->string('city', 255);          // City
            $table->string('state', 50);          // State
            $table->string('zip', 50);            // ZIP code
            $table->string('country_code', 4);    // Country code

            $table->timestamps();

            // Add foreign key reference for the country_code
            $table->foreign('country_code')->references('code')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
