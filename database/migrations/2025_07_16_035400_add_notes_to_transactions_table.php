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
            // إضافة عمود 'notes'
            // يمكنك اختيار نوع البيانات المناسب، text هو الأفضل للملاحظات الطويلة.
            // يجب أن يكون nullable بناءً على استخدامك (?? null).
            $table->text('notes')->nullable()->after('payment_method'); // يمكنك تغيير after حسب مكان العمود المفضل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // حذف عمود 'notes' إذا تراجعت عن الـ Migration
            $table->dropColumn('notes');
        });
    }
};

