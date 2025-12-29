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
    Schema::table('bookings', function (Blueprint $table) {
        // Add reference column if it doesn't exist
        if (!Schema::hasColumn('bookings', 'reference')) {
            $table->string('reference')->unique()->after('id');
        }
        
        // Add deleted_at column for Soft Deletes
        if (!Schema::hasColumn('bookings', 'deleted_at')) {
            $table->softDeletes();
        }
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        // Drop safely
        
        if (Schema::hasColumn('bookings', 'deleted_at')) {
            $table->dropSoftDeletes();
        }
    });
}
};
