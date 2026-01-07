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
        Schema::create('rental_rate', function (Blueprint $table) {
            $table->string('rate_id')->primary();

            $table->integer('hours');
            $table->double('rate_price');

            $table->timestamps();

            $table->string('plate_no')->nullable();
            $table->foreign('plate_no')->references('plate_no')->on('vehicle');
            
            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_rate');
    }
};
