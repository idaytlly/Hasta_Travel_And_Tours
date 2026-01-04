<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'link')) {
                $table->string('link')->nullable()->after('message');
                $table->json('data')->nullable()->after('link');
                $table->timestamp('read_at')->nullable()->after('is_read');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['link', 'data', 'read_at']);
        });
    }
};