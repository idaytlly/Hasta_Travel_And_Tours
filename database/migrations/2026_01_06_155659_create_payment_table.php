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
        Schema::create('payment', function (Blueprint $table) {
            $table->string('payment_id')->primary();

            $table->double('amount')->nullable();
            $table->mediumText('payment_status')->nullable();
            $table->double('deposit')->nullable();
            $table->double('remaining_payment')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_proof')->nullable();

            //FK 
            $table->string('booking_id')->nullable();
            $table->foreign('booking_id')->references('booking_id')->on('booking');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
