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
        // إذا العمود غير موجود فعلياً، نضيفه
        if (! Schema::hasColumn('transactions', 'balance_after')) {
            Schema::table('transactions', function (Blueprint $table) {
                // حرفياً نفس التعريف اللي استخدمته في الميجريشن الأصلي
                $table->decimal('balance_after', 15, 2)
                      ->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // عند التراجع، نحذفه إن كان موجوداً
        if (Schema::hasColumn('transactions', 'balance_after')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('balance_after');
            });
        }
    }
};
