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
            $table->unsignedBigInteger('cashier_id')->nullable()->change();
             $table->unsignedBigInteger('brand_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('cashier_id')->nullable(false)->change();
            $table->unsignedBigInteger('brand_id')->nullable(false)->change();
        });
    }
};
