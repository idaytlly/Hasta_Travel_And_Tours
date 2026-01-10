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
            $table->id()->primary();

            $table->string('email')->unique();
            $table->string('password');
            
            $table->string('matricNum')->nullable()->unique();

            $table->string('name')->nullable();
            $table->string('ic')->unique()->nullable();
            $table->string('phone_no')->nullable();
            $table->text('address')->nullable();
            $table->tinyText('state')->nullable();
            $table->text('city')->nullable();
            $table->integer('postcode')->nullable();
            $table->string('license_no')->nullable();
            $table->string('emergency_phoneNo')->nullable();
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
