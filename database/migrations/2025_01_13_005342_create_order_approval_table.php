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
        Schema::create('order_approval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_order_id');
            $table->unsignedBigInteger('vendor_id');
            $table->boolean('is_approved')->default(false); // Approval status
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('store_order_id')->references('store_order_id')->on('store_order')->onDelete('cascade');
            $table->foreign('vendor_id')->references('vendor_id')->on('vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_approval');
    }
};
