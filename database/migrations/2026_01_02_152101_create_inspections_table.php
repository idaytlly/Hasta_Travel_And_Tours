<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['pickup', 'return']);
            $table->string('vehicle_type'); // car or motorcycle
            
            
            // Exterior Inspection
            $table->string('exterior_condition')->nullable();
            $table->text('exterior_damages')->nullable();
            $table->string('exterior_cleanliness')->nullable();
            
            // Interior Inspection
            $table->string('interior_condition')->nullable();
            $table->text('interior_damages')->nullable();
            $table->string('interior_cleanliness')->nullable();
            
            // Mechanical Inspection
            $table->string('engine_condition')->nullable();
            $table->string('brake_condition')->nullable();
            $table->string('tire_condition')->nullable();
            $table->string('lights_condition')->nullable();
            $table->string('wipers_condition')->nullable();
            $table->string('horn_condition')->nullable();
            
            // Fluids & Levels
            $table->string('fuel_level')->nullable();
            $table->string('oil_level')->nullable();
            $table->string('coolant_level')->nullable();
            
            // Documents & Accessories
            $table->boolean('spare_tire')->default(false);
            $table->boolean('jack')->default(false);
            $table->boolean('vehicle_manual')->default(false);
            $table->boolean('first_aid_kit')->default(false);
            $table->boolean('fire_extinguisher')->default(false);
            
            // For Motorcycles
            $table->string('helmet_condition')->nullable();
            $table->boolean('side_mirrors')->default(false);
            
            // Mileage
            $table->integer('mileage_reading')->nullable();
            
            // Images
            $table->json('images')->nullable(); // Store multiple image paths
            
            // Notes
            $table->text('notes')->nullable();
            
            // Inspector
            $table->foreignId('inspector_id')->constrained('users');
            $table->timestamp('inspected_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};