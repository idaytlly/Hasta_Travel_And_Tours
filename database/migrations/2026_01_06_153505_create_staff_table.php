<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->string('staff_id')->primary(); // This is already auto-incrementing like STF001
            
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_no')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'manager', 'staff'])->default('staff');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};