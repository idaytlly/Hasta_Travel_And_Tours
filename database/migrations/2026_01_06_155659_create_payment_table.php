<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->string('payment_id')->primary();

            // Payment details
            $table->double('amount');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_paid'])->default('pending');
            $table->double('deposit')->default(0);
            $table->double('remaining_payment')->default(0);
            $table->date('payment_date')->nullable();
            $table->string('payment_proof')->nullable();
            $table->string('payment_method')->nullable(); // cash, card, online
            $table->string('transaction_id')->nullable();
            
            // Foreign keys
            $table->string('booking_id');
            $table->foreign('booking_id')->references('booking_id')->on('booking')->onDelete('cascade');
            
            // Staff who verified/approved the payment
            $table->string('verified_by_staff')->nullable();
            $table->foreign('verified_by_staff')->references('staff_id')->on('staff')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            
            // Staff who processed refund
            $table->string('refunded_by_staff')->nullable();
            $table->foreign('refunded_by_staff')->references('staff_id')->on('staff')->onDelete('set null');
            $table->timestamp('refunded_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};