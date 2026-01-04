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

            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->integer('year')->nullable();
            $table->string('category')->nullable();
            $table->double('daily_rate')->nullable();
            $table->integer('mileage')->nullable();
            $table->string('image')->nullable();
            $table->string('air_conditioner')->nullable();
            $table->integer('passengers')->nullable();
            $table->integer('seats')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->double('price_perDay')->nullable();
            $table->double('price_perHour')->nullable();
            $table->mediumText('availability_status')->nullable();
            $table->string('maintenance_records')->nullable();

            //FK
            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customers');

            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff');

            $table->string('inspection_id')->nullable();
            $table->foreign('inspection_id')->references('inspection_id')->on('inspections');

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
