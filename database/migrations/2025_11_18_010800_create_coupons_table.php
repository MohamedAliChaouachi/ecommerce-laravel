<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('coupons', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->enum('type', ['fixed', 'percent']); // 'fixed' = value DT, 'percent' = %
        $table->decimal('value', 10, 2);          // 10 or 20.00 % etc.
        $table->decimal('max_discount', 10, 2)->nullable(); // cap for % coupons (optional)

        $table->timestamp('starts_at')->nullable();
        $table->timestamp('ends_at')->nullable();

        $table->unsignedInteger('usage_limit')->nullable(); // null = unlimited
        $table->unsignedInteger('used_count')->default(0);

        $table->boolean('is_active')->default(true);

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('coupons');
}


};
