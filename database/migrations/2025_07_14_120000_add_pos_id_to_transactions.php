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
        Schema::table('transactions', function (Blueprint $table) {
            // أضف عمود pos_id فقط إذا لم يكن موجودًا
            if (!Schema::hasColumn('transactions', 'pos_id')) {
                $table->unsignedBigInteger('pos_id')->nullable()->after('id'); // يمكنك تعديل 'after' حسب رغبتك
            }
            // إضافة المفتاح الأجنبي بشكل آمن
            if (Schema::hasColumn('transactions', 'pos_id') && !Schema::hasColumn('transactions', 'point_of_sale_id')) { // التأكد من عدم وجود قيد مماثل
                // التحقق من عدم وجود المفتاح الأجنبي مسبقًا
                $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('transactions');
                $fkExists = false;
                foreach ($foreignKeys as $fk) {
                    if (in_array('pos_id', $fk->getColumns()) && $fk->getForeignTableName() === 'point_of_sales') {
                        $fkExists = true;
                        break;
                    }
                }
                if (!$fkExists) {
                    $table->foreign('pos_id')->references('id')->on('point_of_sales')->onDelete('set null');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // حذف المفتاح الأجنبي أولاً بأمان
            if (Schema::hasColumn('transactions', 'pos_id')) {
                $foreignKeys = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys('transactions');
                foreach ($foreignKeys as $fk) {
                    if (in_array('pos_id', $fk->getColumns()) && $fk->getForeignTableName() === 'point_of_sales') {
                        $table->dropForeign($fk->getName());
                        break;
                    }
                }
            }

            // حذف عمود pos_id بأمان
            if (Schema::hasColumn('transactions', 'pos_id')) {
                $table->dropColumn('pos_id');
            }
        });
    }
};