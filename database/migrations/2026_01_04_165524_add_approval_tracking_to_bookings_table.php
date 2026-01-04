<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Check if columns don't already exist
            if (!Schema::hasColumn('bookings', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('bookings', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            
            if (Schema::hasColumn('bookings', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });
    }
};