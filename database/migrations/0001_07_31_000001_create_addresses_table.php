<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label')->nullable();
            $table->string('phone')->nullable();
            $table->text('note')->nullable();
            $table->string('district');
            $table->string('upazila')->nullable();
            $table->string('union')->nullable();
            $table->string('street_address')->nullable();
            $table->string('postal_code')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('address_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
