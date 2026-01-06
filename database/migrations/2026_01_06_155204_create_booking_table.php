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
        Schema::create('booking', function (Blueprint $table) {
            $table->string('booking_id')->primary();

            $table->date('pickup_date')->nullable();
            $table->date('return_date')->nullable();
            $table->double('total_price')->nullable();
            $table->mediumText('booking_status')->nullable();
            
            //FK
            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customers');

            $table->string('plate_no')->nullable();
            $table->foreign('plate_no')->references('plate_no')->on('vehicle');

            $table->string('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('voucher_id')->on('voucher');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
