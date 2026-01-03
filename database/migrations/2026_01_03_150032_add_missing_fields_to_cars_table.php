<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Add missing columns
            $table->string('name')->after('id')->nullable();
            $table->string('registration_number')->after('year')->nullable();
            $table->string('color')->after('category')->nullable();
            $table->integer('mileage')->after('daily_rate')->nullable();
            $table->text('features')->after('description')->nullable();
            $table->json('maintenance_records')->after('features')->nullable();
            $table->date('last_maintenance_date')->after('maintenance_records')->nullable();
            
            // Rename if license_plate exists
            // $table->renameColumn('license_plate', 'registration_number');
        });
        
        // Update existing records to have a name
        DB::table('cars')->update([
            'name' => DB::raw("CONCAT(brand, ' ', model, ' ', year)")
        ]);
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'name', 'registration_number', 'color', 'mileage',
                'features', 'maintenance_records', 'last_maintenance_date'
            ]);
        });
    }
};