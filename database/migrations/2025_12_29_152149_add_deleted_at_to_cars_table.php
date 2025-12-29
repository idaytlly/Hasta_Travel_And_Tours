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
    // ...repeat for other fields
    });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
?>