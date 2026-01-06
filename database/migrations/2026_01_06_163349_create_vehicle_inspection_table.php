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
        Schema::create('vehicle_inspection', function (Blueprint $table) {
            $table->string('inspection_id')->primary();

            
            //FK
            $table->string('plate_no')->nullable();
            $table->foreign('plate_no')->references('plate_no')->on('vehicle');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_inspection');
    }
};
