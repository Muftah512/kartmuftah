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
            // إضافة عمود 'reference_id'
            // نوع البيانات يجب أن يكون unsignedBigInteger لأنه يشير إلى ID فاتورة
            // يجب أن يكون nullable لأنه قد لا يكون لكل معاملة معرف مرجعي
            $table->unsignedBigInteger('reference_id')->nullable()->after('notes'); // يمكنك تغيير after حسب مكان العمود المفضل
            // إذا كان reference_id يشير إلى جدول آخر، يمكنك إضافة foreign key constraint
            // $table->foreign('reference_id')->references('id')->on('invoices')->onDelete('set null'); // مثال إذا كنت تريد مفتاح أجنبي
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // حذف المفتاح الأجنبي أولاً إذا كنت قد أضفته
            // $table->dropForeign(['reference_id']);

            // حذف عمود 'reference_id'
            $table->dropColumn('reference_id');
        });
    }
};

