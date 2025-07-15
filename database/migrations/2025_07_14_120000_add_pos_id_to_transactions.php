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
        // نتحقق أولاً أن العمود غير موجود لتفادي التضارب
        if (! Schema::hasColumn('transactions', 'pos_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                // إضافة العمود وربطه بجدول point_of_sales
                $table->foreignId('pos_id')
                      ->after('id')
                      ->constrained('point_of_sales')
                      ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'pos_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                // أولاً نزيل القيد
                $table->dropForeign(['pos_id']);
                // ثم نحذف العمود نفسه
                $table->dropColumn('pos_id');
            });
        }
    }
};
