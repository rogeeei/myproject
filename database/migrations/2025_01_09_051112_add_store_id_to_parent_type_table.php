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
        Schema::table('parent_type', function (Blueprint $table) {
            // Make store_id nullable to avoid conflict with existing data
            $table->unsignedBigInteger('store_id')->nullable()->after('name'); // Nullable store_id

            // Add foreign key constraint
            $table->foreign('store_id')->references('store_id')->on('store')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
    }
};
