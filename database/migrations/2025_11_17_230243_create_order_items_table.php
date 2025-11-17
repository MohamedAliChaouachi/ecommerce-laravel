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
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')
              ->constrained()
              ->onDelete('cascade');

        $table->foreignId('product_id')
              ->constrained()
              ->onDelete('restrict'); // don't delete product if used in orders (optional)

        $table->decimal('price', 10, 2);   // price at the moment of purchase
        $table->unsignedInteger('quantity');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
