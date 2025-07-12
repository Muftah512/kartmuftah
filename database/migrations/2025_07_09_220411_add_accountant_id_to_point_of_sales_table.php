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
            // إضافة العمود accountant_id
            $table->unsignedBigInteger('accountant_id')->nullable()->after('id');

            // ربط المفتاح الأجنبي بجدول المستخدمين
            $table->foreign('accountant_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_of_sales', function (Blueprint $table) {
            $table->dropForeign(['accountant_id']);
            $table->dropColumn('accountant_id');
        });
    }
};
