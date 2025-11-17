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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade'); // if user deleted, delete orders

        $table->decimal('total_amount', 10, 2);
        $table->string('status')->default('pending'); // pending, confirmed, shipped, cancelled
        $table->string('payment_status')->default('unpaid'); // unpaid, paid, refunded

        // Simple address fields (you can adapt for Tunisia)
        $table->string('address');
        $table->string('city');
        $table->string('phone', 30);
        $table->text('notes')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
