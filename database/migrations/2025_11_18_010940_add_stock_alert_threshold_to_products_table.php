<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->unsignedInteger('stock_alert_threshold')->default(5)->after('stock');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('stock_alert_threshold');
    });
}


};
