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
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id');
            $table->string('staff_id');
            $table->string('staff_name');
            $table->timestamp('verified_at');
            
            // Foreign keys
            $table->foreign('booking_id')
                  ->references('booking_id')
                  ->on('booking')
                  ->onDelete('cascade');
                  
            $table->foreign('staff_id')
                  ->references('staff_id')
                  ->on('staff')
                  ->onDelete('cascade');
            
            // Indexes for faster queries
            $table->index('booking_id');
            $table->index('staff_id');
            $table->index('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_logs');
    }
};