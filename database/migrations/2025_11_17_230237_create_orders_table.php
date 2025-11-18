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
              ->onDelete('cascade');

        $table->string('order_number')->unique();
        
        // Status fields
        $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])
              ->default('pending');
        $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])
              ->default('pending');
        $table->string('payment_method'); // cash_on_delivery, credit_card, paypal
        
        // Price breakdown
        $table->decimal('subtotal', 10, 2);
        $table->decimal('tax', 10, 2)->default(0);
        $table->decimal('shipping_cost', 10, 2)->default(0);
        $table->decimal('total', 10, 2);
        
        // Addresses (JSON for flexibility)
        $table->json('shipping_address');
        $table->json('billing_address');
        
        $table->text('notes')->nullable();

        $table->timestamps();
        
        // Indexes for better performance
        $table->index('user_id');
        $table->index('status');
        $table->index('payment_status');
        $table->index('created_at');
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
