<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Add category column
            $table->string('category')->default('Sedan')->after('model');
            
            // Add status column (keep is_available for backwards compatibility)
            $table->string('status')->default('available')->after('is_available');
            
            // Add seats column (keep passengers for backwards compatibility)
            $table->integer('seats')->default(5)->after('passengers');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['category', 'status', 'seats']);
        });
    }
};