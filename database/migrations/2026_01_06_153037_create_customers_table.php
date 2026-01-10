<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('customer_id')->primary();
            $table->string('email')->unique();
            $table->string('password');
            
            // Personal Information
            $table->string('name')->nullable();
            $table->string('ic_number')->unique()->nullable();// IC or Passport number
            $table->string('phone_no')->nullable();
            
            // File Uploads (REQUIRED)
            $table->string('ic_passport_image')->nullable(); // Front of IC/Passport (Required)
            $table->string('license_image')->nullable(); // Driving License (Required)
            $table->string('matric_card_image')->nullable(); // Matric Card (Optional)
            
            // Optional Matric Number
            $table->string('matricNum')->nullable()->unique();
            
            // License Information
            $table->string('license_no')->nullable();
            $table->date('license_expiry')->nullable();

            // Emergency Contact (REQUIRED)
            $table->string('emergency_phoneNo')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_relationship')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};