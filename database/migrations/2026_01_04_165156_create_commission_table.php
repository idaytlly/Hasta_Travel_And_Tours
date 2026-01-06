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
        Schema::create('commission', function (Blueprint $table) {
            $table->string('commission_id')->primary();

            $table->date('comm_date')->nullable();
            $table->integer('comm_hour')->nullable();
            $table->string('reason')->nullable();
            $table->mediumText('status')->nullable();
            $table->double('total_commission')->nullable();

            //FK
            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission');
    }
};
