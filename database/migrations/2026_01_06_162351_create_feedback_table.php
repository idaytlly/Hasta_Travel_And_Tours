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
        Schema::create('feedback', function (Blueprint $table) {
            $table->string('feedback_id')->primary();

            $table->string('rating')->nullable();
            $table->string('comment')->nullable();
            $table->date('feedback_date')->nullable();
            
            //FK
            $table->string('booking_id')->nullable();
            $table->foreign('booking_id')->references('booking_id')->on('booking');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
