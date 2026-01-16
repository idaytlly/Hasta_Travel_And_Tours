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
        Schema::create('voucher', function (Blueprint $table) {
            $table->string('voucher_id')->primary();
            
            // Add customer_id to track which customer owns this voucher
            $table->string('customer_id')->nullable();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');

            $table->string('voucherCode')->nullable();
            $table->integer('voucherAmount')->nullable();
            
            // Track which stamp milestone generated this voucher (3, 9, or 12)
            $table->integer('stamp_milestone')->nullable();
            
            $table->tinyInteger('used_count')->nullable();
            
            // Track if voucher has been used (one-time use)
            
            $table->date('expiryDate')->nullable();
            $table->mediumText('voucherStatus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};