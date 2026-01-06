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
        Schema::create('voucher_redemption', function (Blueprint $table) {

            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customers');

            $table->string('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('voucher_id')->on('voucher');

            $table->primary(['matricNum', 'voucher_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_redemption');
    }
};
