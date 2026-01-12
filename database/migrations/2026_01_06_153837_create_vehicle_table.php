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
            
            // Basic Information
            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->integer('year')->nullable();
            $table->json('pickup_location')->nullable();
            $table->string('pickup_date')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('return_date')->nullable();
            $table->string('return_time')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('image')->nullable();
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->string('left_image')->nullable();
            $table->string('right_image')->nullable();
            $table->string('interior1_image')->nullable();
            $table->string('interior2_image')->nullable();
            
            // New Fields
            $table->date('roadtax_expiry')->nullable();
            $table->string('transmission')->nullable(); // automatic, manual
            $table->string('fuel_type')->nullable(); // petrol, diesel, electric, hybrid
            $table->integer('seating_capacity')->default(4);
            
            // Pricing
            $table->double('price_perHour')->nullable();
            
            // Features and Details
            $table->json('features')->nullable(); // Store as JSON array
            $table->string('display_image')->nullable(); // Display image for listing
            $table->json('images')->nullable(); // Store multiple images as JSON array
            $table->text('description')->nullable();
            
            // Status and Maintenance
            $table->double('distance_travelled')->nullable();
            $table->string('availability_status')->default('available'); // available, booked, maintenance
            $table->text('maintenance_notes')->nullable();
            
            // Foreign Key
            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('set null');
            
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