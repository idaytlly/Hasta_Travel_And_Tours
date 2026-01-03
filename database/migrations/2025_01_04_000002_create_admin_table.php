<?php

// database/migrations/2025_01_04_000002_create_admin_table.php

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
        Schema::create('admin', function (Blueprint $table) {
            $table->id('adminID');
            $table->unsignedBigInteger('userID');
            $table->string('access_level', 50)->default('full');
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
        Schema::dropIfExists('admin');
    }
};