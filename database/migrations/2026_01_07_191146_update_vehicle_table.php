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
        Schema::table('vehicle', function (Blueprint $table) {

            // Drop column
            $table->dropColumn('daily_rate');
            $table->dropColumn('mileage');
            $table->dropColumn('air_conditioner');
            $table->dropColumn('passengers');
            $table->dropColumn('seats');
            $table->dropColumn('fuel_type');
            $table->dropColumn('vehicle_type');
            $table->dropColumn('price_perDay');
            $table->dropColumn('phone_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            $table->string('daily_rate')->nullable();
            $table->string('mileage')->nullable();
            $table->string('air_conditioner')->nullable();
            $table->string('passengers')->nullable();
            $table->string('seats')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->double('price_perDay')->nullable();
            $table->string('phone_no')->nullable();
    }
};
