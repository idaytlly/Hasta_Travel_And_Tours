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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('matricNum')->primary();
            $table->string('name');
            $table->string('ic');
            $table->string('email')->unique();
            $table->string('phone_no')->nullable();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('license_no');
            $table->string('emergency_phoneNo')->unique()->nullable();
            $table->string('emergency_name')->nullable();
            $table->tinyText('emergency_relationship')->nullable();


            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
