<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // نجلب نوع العمود الحالي لنطبّق ALTER المناسب (TIMESTAMP أو DATETIME)
        $col = DB::selectOne("SHOW COLUMNS FROM `internet_cards` LIKE 'expiration_date'");
        if (!$col) {
            // لو العمود غير موجود (نادر جدًا)
            DB::statement("ALTER TABLE `internet_cards` ADD `expiration_date` DATETIME NULL");
            return;
        }

        $type = strtolower($col->Type ?? '');

        // لو TIMESTAMP: اجعله NULL DEFAULT NULL
        if (str_contains($type, 'timestamp')) {
            DB::statement("ALTER TABLE `internet_cards` MODIFY `expiration_date` TIMESTAMP NULL DEFAULT NULL");
        } else {
            // DATETIME أو أي نوع تاريخي آخر
            DB::statement("ALTER TABLE `internet_cards` MODIFY `expiration_date` DATETIME NULL");
        }
    }

    public function down(): void
    {
        // الرجوع إلى NOT NULL (قد تحتاج تعديل حسب سلوكك السابق)
        $col = DB::selectOne("SHOW COLUMNS FROM `internet_cards` LIKE 'expiration_date'");
        if (!$col) return;

        $type = strtolower($col->Type ?? '');

        if (str_contains($type, 'timestamp')) {
            // إعادة الإلزام بدون DEFAULT (عدّلها إن لزمك DEFAULT معين)
            DB::statement("ALTER TABLE `internet_cards` MODIFY `expiration_date` TIMESTAMP NOT NULL");
        } else {
            DB::statement("ALTER TABLE `internet_cards` MODIFY `expiration_date` DATETIME NOT NULL");
        }
    }
};
