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
        Schema::create('store_order_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity'); 
            $table->float('unit_price'); 
            $table->float('subtotal'); 
            $table->unsignedBigInteger('store_order_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('store_order_id')->references('store_order_id')->on('store_order')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_order_detail');
    }
};
