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
        if (!Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table) {
                $table->id();
                $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
                $table->string('access_level', 50)->default('full');
                $table->timestamps();

                // Unique constraint
                $table->unique('userID');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};