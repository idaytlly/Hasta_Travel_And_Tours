<?php
// 2026_01_06_154136_create_inspections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->string('inspection_id')->primary();
            $table->date('inspection_date')->nullable();
            $table->string('fuel_level')->nullable();
            $table->mediumText('inspection_status')->nullable();
            $table->string('damage_notes')->nullable();
            $table->string('photo_evidence')->nullable();
            
            // FK to staff table
            $table->string('person_in_charge')->nullable();
            $table->foreign('person_in_charge')->references('staff_id')->on('staff')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};