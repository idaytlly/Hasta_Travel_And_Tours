<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id(); // id
            $table->foreignId('customers_id')->constrained('customers')->onDelete('cascade'); // FK user
            $table->foreignId('bookings_id')->nullable()->constrained('bookings')->onDelete('set null'); // FK booking
            $table->tinyInteger('rating'); 
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
