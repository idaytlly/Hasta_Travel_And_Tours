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

            $table->string('voucherCode')->nullable();
            $table->integer('voucherAmount')->nullable();
            $table->tinyInteger('used_count')->nullable();
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
