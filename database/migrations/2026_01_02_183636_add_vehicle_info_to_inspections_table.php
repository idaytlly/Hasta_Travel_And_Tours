<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->string('vehicle_brand')->nullable()->after('vehicle_type');
            $table->string('vehicle_model')->nullable()->after('vehicle_brand');
            $table->string('vehicle_year')->nullable()->after('vehicle_model');
            $table->string('license_plate')->nullable()->after('vehicle_year');
        });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn(['vehicle_brand', 'vehicle_model', 'vehicle_year', 'license_plate']);
        });
    }
};