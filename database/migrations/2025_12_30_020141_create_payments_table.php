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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();

        // kalau payment ni untuk booking
        $table->unsignedBigInteger('booking_id')->nullable();

        // simpan file path
        $table->string('payment_proof');

        // jumlah bayaran
        $table->decimal('amount', 8, 2)->nullable();

        // status payment
        $table->enum('status', ['pending', 'approved', 'rejected'])
              ->default('pending');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
