<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1) إضافة العمود بدون تحديد موقع (حتى لا يتكلّم على phone)
            if (! Schema::hasColumn('users', 'point_of_sale_id')) {
                $table->unsignedBigInteger('point_of_sale_id')
                      ->nullable();
            }

            // 2) إنشاء قيد المفتاح الخارجي
            $table->foreign('point_of_sale_id')
                  ->references('id')
                  ->on('point_of_sales')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إسقاط القيد ثم حذف العمود
            $table->dropForeign(['point_of_sale_id']);
            $table->dropColumn('point_of_sale_id');
        });
    }
};
