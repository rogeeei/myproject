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
        Schema::create('store_order', function (Blueprint $table) {
            $table->id('store_order_id');
            $table->date('order_date'); 
            $table->float('total_amount');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('store_id');

            $table->foreign('vendor_id')->references('vendor_id')->on('vendor')->onDelete('cascade');
            $table->foreign('store_id')->references('store_id')->on('store')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_order');
    }
};
