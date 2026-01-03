<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customerID'); // primary key
            $table->foreignId('userID')->constrained('users')->onDelete('cascade'); // foreign key to users.id
            $table->string('name', 100);
            $table->string('ic', 14);
            $table->string('email', 50);
            $table->string('phone', 12);
            $table->string('address', 100)->nullable();
            $table->string('licenceNo', 10)->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
