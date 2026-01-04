<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('staff')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admin')->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('transmission')->default('Automatic');
            $table->decimal('daily_rate', 8, 2); // Price per day
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            
            // Additional features
            $table->boolean('air_conditioner')->default(true);
            $table->integer('passengers')->default(5);
            $table->string('fuel_type')->default('Petrol');
            $table->string('license_plate')->unique()->nullable();
            $table->text('description')->nullable();
            
            $table->timestamps();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};