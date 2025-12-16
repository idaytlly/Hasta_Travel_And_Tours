<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
return new class extends Migration {
    public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
};
*/
return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->date('pickup_date');
            $table->date('dropoff_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
