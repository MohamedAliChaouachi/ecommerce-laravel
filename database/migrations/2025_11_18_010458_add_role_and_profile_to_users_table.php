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
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('user')->after('email'); // user / admin
        $table->string('phone', 30)->nullable()->after('role');
        $table->string('city')->nullable()->after('phone');
        $table->string('address')->nullable()->after('city');
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role', 'phone', 'city', 'address']);
    });
}

};
