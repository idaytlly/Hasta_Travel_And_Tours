<?php

// database/migrations/2025_01_04_000001_create_staff_table.php

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
        Schema::create('staff', function (Blueprint $table) {
            $table->id('staffID');
            $table->unsignedBigInteger('userID');
            $table->enum('staff_type', ['management', 'runner'])->default('runner');
            $table->date('hire_date')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('userID')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique('userID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};