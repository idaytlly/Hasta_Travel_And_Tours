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
        Schema::create('vehicle', function (Blueprint $table) {
            $table->string('plate_no')->primary();

            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->integer('year')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('image')->nullable();
            $table->double('price_perHour')->nullable();
            $table->integer('passengers')->nullable();
            $table->double('distance_travelled')->nullable();
            $table->string('availability_status')->nullable();


            //FK
            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle');
    }
};
