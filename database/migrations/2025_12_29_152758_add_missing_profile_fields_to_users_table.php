<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ic')) {
                $table->string('ic', 12)->nullable();
            }
            if (!Schema::hasColumn('users', 'license_no')) {
                $table->string('license_no')->nullable();
            }
            if (!Schema::hasColumn('users', 'street')) {
                $table->string('street')->nullable();
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('users', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('users', 'postcode')) {
                $table->string('postcode')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ic', 'license_no', 'street', 'city', 'state', 'postcode']);
        });
    }
};
?>