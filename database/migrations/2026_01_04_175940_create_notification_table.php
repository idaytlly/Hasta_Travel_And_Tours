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
        Schema::create('notification', function (Blueprint $table) {
            $table->string('notification_id')->primary();

            $table->string('notification_type')->nullable();
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_read')->default(false);
            $table->json('data')->nullable();
            $table->timestamps();

            $table->string('staff_id')->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('staff');

            $table->string('matricNum')->nullable();
            $table->foreign('matricNum')->references('matricNum')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
