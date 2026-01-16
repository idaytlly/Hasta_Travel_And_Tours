<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->string('inspection_id')->primary();
            
            // Customer inspection fields
            $table->string('booking_id')->nullable();
            $table->enum('inspection_type', ['pickup', 'dropoff'])->nullable();
            $table->json('car_photos')->nullable();
            $table->string('fuel_photo')->nullable();
            $table->text('remarks')->nullable();
            $table->string('signature')->nullable();

            $table->string('inspected by')->nullable();
            $table->foreign('inspected by')->references('staff_id')->on('staff')->onDelete('set null');
            
            $table->timestamp('inspected_at')->nullable();
            
            // Original fields
            $table->date('inspection_date')->nullable();
            $table->mediumText('inspection_status')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};