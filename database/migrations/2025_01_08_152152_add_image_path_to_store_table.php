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
         Schema::table('store', function (Blueprint $table) {
        $table->string('image_path')->nullable()->after('operating_hours');
    });
}

public function down()
{
    Schema::table('store', function (Blueprint $table) {
        $table->dropColumn('image_path');
    });
    }
};
