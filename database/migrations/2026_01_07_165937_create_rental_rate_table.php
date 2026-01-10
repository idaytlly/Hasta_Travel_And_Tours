<?php
// database/migrations/2026_01_07_165937_create_rental_rate_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_rate', function (Blueprint $table) {
            $table->string('rate_id')->primary();
            $table->string('rate_type')->default('normal'); // normal, late_return, overtime
            $table->integer('hours');
            $table->double('rate_price');
            
            // Late return specific
            $table->double('late_penalty_percentage')->nullable();
            $table->integer('grace_period_minutes')->default(30);
            
            $table->string('plate_no')->nullable();
            $table->foreign('plate_no')->references('plate_no')->on('vehicle')->onDelete('cascade');
            
            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_rate');
    }
};