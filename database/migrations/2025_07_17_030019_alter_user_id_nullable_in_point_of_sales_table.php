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
        Schema::table('point_of_sales', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id')->nullable()->change();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_of_sales', function (Blueprint $table) {
            //
        });
    }
};
