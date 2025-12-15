<?php

// database/migrations/..._create_cars_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('transmission')->default('Automatic');
            $table->decimal('daily_rate', 8, 2); // Price per day
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true); // Matches the query condition
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
