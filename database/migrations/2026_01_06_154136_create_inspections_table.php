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
        Schema::create('inspections', function (Blueprint $table) {
            $table->string('inspection_id')->primary();

            $table->date('inspection_date')->nullable();
            $table->string('fuel_level')->nullable();
            $table->mediumText('inspection_status')->nullable();
            $table->string('damage_notes')->nullable();
            $table->string('photo_evidence')->nullable();
            $table->string('person_in_charge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
