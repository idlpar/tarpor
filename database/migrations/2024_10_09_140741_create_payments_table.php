<?php

use App\Models\User;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Primary key

            // Foreign key for 'orders'
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Payment details
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('total', 10, 2);

            // Payment status and type
            $table->string('status', 50)->default('pending');
            $table->string('type', 50);

            // Timestamps for created_at, updated_at
            $table->timestamps();

            // Foreign keys for user tracking
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();

            // Indexing foreign keys
            $table->index('order_id');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
