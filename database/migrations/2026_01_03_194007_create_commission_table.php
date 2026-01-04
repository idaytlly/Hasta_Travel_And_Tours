<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('commissions')) {
            Schema::create('commissions', function (Blueprint $table) {
                $table->id();

                $table->foreignId('payment_id')
                    ->constrained('payments')
                    ->onDelete('cascade');

                $table->foreignId('staff_id')
                    ->constrained('staff')
                    ->onDelete('cascade');

                $table->foreignId('admin_id')
                    ->constrained('admin')
                    ->onDelete('cascade');


                $table->decimal('amount', 8, 2);
                $table->decimal('rate', 5, 2);
                $table->text('reason')->nullable();
                $table->enum('status', ['pending', 'paid'])->default('pending');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
