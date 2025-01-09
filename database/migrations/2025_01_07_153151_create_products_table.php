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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('UPC')-> unique();
            $table->string('product_name');
            $table->string('size');
            $table->string('packaging');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('product_type_id');
            $table->unsignedBigInteger('vendor_id');

            $table->foreign('brand_id')->references('brand_id')->on('brand')->onDelete('cascade');
            $table->foreign('product_type_id')->references('product_type_id')->on('product_type')->onDelete('cascade');
            $table->foreign('vendor_id')->references('vendor_id')->on('vendor')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
