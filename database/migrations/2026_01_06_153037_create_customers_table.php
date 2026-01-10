<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            
            // Personal Information
            $table->string('name');
            $table->string('ic_number')->unique(); // IC or Passport number
            $table->string('phone_no');
            
            // File Uploads (REQUIRED)
            $table->string('ic_passport_image'); // Front of IC/Passport (Required)
            $table->string('license_image'); // Driving License (Required)
            $table->string('matric_card_image')->nullable(); // Matric Card (Optional)
            
            // Optional Matric Number
            $table->string('matricNum')->nullable()->unique();
            
            // License Information
            $table->string('license_no');
            $table->date('license_expiry');
            
            // Emergency Contact (REQUIRED)
            $table->string('emergency_phoneNo');
            $table->string('emergency_name');
            $table->string('emergency_relationship');
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};