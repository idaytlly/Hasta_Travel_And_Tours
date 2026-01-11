<?php
// database/migrations/2026_01_06_155204_create_booking_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->string('booking_id')->primary();

            // Booking details
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->string('pickup_location');          
            $table->string('pickup_details')->nullable();

            $table->date('return_date');
            $table->time('return_time');
            $table->string('dropoff_location');         
            $table->string('dropoff_details')->nullable(); 
            $table->boolean('delivery_required')->default(false);
            $table->text('signature')->nullable();  

            $table->double('total_price');
            $table->enum('booking_status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending');
            $table->text('special_requests')->nullable();
            
            // Late return charges
            $table->date('actual_return_date')->nullable();
            $table->time('actual_return_time')->nullable();
            $table->integer('late_return_hours')->default(0);
            $table->double('late_return_charge')->default(0);
            $table->text('late_return_notes')->nullable();
            $table->boolean('late_charge_paid')->default(false);
            
            // Staff approvals
            $table->string('late_charge_approved_by')->nullable();
            $table->foreign('late_charge_approved_by')->references('staff_id')->on('staff')->onDelete('set null');
            $table->timestamp('late_charge_approved_at')->nullable();
            
            // Foreign keys
            $table->string('customer_id')->nullable();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');

            $table->string('plate_no')->nullable();
            $table->foreign('plate_no')->references('plate_no')->on('vehicle')->onDelete('cascade');

            $table->string('voucher_id')->nullable();
            $table->foreign('voucher_id')->references('voucher_id')->on('voucher')->onDelete('set null');
            
            // Staff who approved the booking
            $table->string('approved_by_staff')->nullable();
            $table->foreign('approved_by_staff')->references('staff_id')->on('staff')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};