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
    Schema::create('product_reviews', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');

        $table->tinyInteger('rating'); // 1 to 5
        $table->text('comment')->nullable();

        $table->string('status')->default('pending'); 
        // pending: waiting for admin validation
        // approved: visible on product page
        // rejected: hidden

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('product_reviews');
}

};
