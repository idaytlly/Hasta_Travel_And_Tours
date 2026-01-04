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
        Schema::create('voucher_management', function (Blueprint $table) {
            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff');

            $table->string('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('voucher_id')->on('voucher');

            $table->primary(['staff_id', 'voucher_id'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_management');
    }
};
