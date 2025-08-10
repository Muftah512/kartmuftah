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
        Schema::table('users', function (Blueprint $table) {
            // إذا كان العمود موجوداً فقط أضف قيد المفتاح الأجنبي
            if (Schema::hasColumn('users', 'point_of_sale_id')) {
                // تأكد أننا لم نربط الـ FK سابقاً
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->listTableDetails('users');
                $hasFk = collect($doctrineTable->getForeignKeys())
                    ->contains(fn($fk) => in_array('point_of_sale_id', $fk->getLocalColumns()));

                if (! $hasFk) {
                    $table->foreign('point_of_sale_id')
                          ->references('id')
                          ->on('point_of_sales')
                          ->nullOnDelete();
                }
            }
            // إذا كان العمود غير موجود (نادر)، يمكنك إضافته هنا:
            else {
                $table->foreignId('point_of_sale_id')
                      ->nullable()
                      ->constrained('point_of_sales')
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // حذْف قيد المفتاح الأجنبي ثم حذف العمود إن أردت
            if (Schema::hasColumn('users', 'point_of_sale_id')) {
                $table->dropForeign(['point_of_sale_id']);
                // إذا تود حذف العمود:
                // $table->dropColumn('point_of_sale_id');
            }
        });
    }
};
