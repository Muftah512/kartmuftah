<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) إضافة/تعديل العمود ليطابق BIGINT UNSIGNED و NULLable
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'pos_id')) {
                // إنشاء العمود فقط
                $table->unsignedBigInteger('pos_id')->nullable()->after('id');
            } else {
                // تأكيد النوع والـ nullable (يتطلب doctrine/dbal للتغيير؛
                // إن لم يتوفر، نفّذ تعديل النوع يدوياً بهجرة منفصلة)
                $table->unsignedBigInteger('pos_id')->nullable()->change();
            }
        });

        // 2) تنظيف البيانات اليتيمة حتى لا يفشل إنشاء القيد
        DB::statement("
            UPDATE transactions t
            LEFT JOIN point_of_sales p ON p.id = t.pos_id
            SET t.pos_id = NULL
            WHERE p.id IS NULL
        ");

        // 3) إضافة القيد (بعد التأكد أنه غير موجود)
        if (!$this->foreignKeyExists('transactions', 'transactions_pos_id_foreign')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreign('pos_id', 'transactions_pos_id_foreign')
                      ->references('id')->on('point_of_sales')
                      ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // حذف القيد إن وجد
        if ($this->foreignKeyExists('transactions', 'transactions_pos_id_foreign')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropForeign('transactions_pos_id_foreign');
            });
        }

        // حذف العمود إن أردت التراجع الكامل
        if (Schema::hasColumn('transactions', 'pos_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('pos_id');
            });
        }
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        $db = DB::getDatabaseName();
        $cnt = DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('TABLE_SCHEMA', $db)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->where('CONSTRAINT_NAME', $constraint)
            ->count();

        return $cnt > 0;
    }
};
