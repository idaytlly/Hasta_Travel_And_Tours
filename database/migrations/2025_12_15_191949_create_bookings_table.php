<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Booking details
            $table->string('booking_reference')->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            
            // Pickup & Return
            $table->string('pickup_location');
            $table->string('dropoff_location')->nullable();
            $table->string('destination')->nullable();
            $table->date('pickup_date');
            $table->time('pickup_time')->nullable();
            $table->date('return_date');
            $table->time('return_time')->nullable();
            $table->integer('duration'); // In days
            
            // Pricing
            $table->string('voucher')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            
            // Status & Notes
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'deposit_paid', 'fully_paid'])->default('unpaid');
            $table->text('remarks')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('pickup_date');
            $table->index('return_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};