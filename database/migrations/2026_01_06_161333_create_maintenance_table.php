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
        Schema::create('maintenance', function (Blueprint $table) {
            $table->string('maintenance_id')->primary();

            $table->date('maintenance_date')->nullable();
            $table->string('description')->nullable();
            $table->string('cost')->nullable();
            $table->mediumText('status')->nullable();

            //FK
            $table->string('plate_no')->nullable();
            $table->foreign('plate_no')->references('plate_no')->on('vehicle');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance');
    }
};
