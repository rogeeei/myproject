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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(); // Add the product_id column
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('set null'); // Add foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Drop foreign key constraint
            $table->dropColumn('product_id'); // Drop the product_id column
        });
    }
};
