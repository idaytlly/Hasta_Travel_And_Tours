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
        Schema::table('users', function (Blueprint $table) {
        //$table->string('phone')->nullable();
        $table->string('ic', 12)->nullable()->unique();
        $table->string('street')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('postcode', 10)->nullable();
        $table->string('license_no', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['phone', 'ic', 'street', 'city', 'state', 'postcode', 'license_no']);
        });
    }
};
