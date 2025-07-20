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
            // إضافة عمود invoice_id
            $table->unsignedBigInteger('invoice_id')->nullable(); // استخدم unsignedBigInteger للمفاتيح الأجنبية

            // إضافة قيد المفتاح الأجنبي (اختياري ولكنه موصى به لسلامة البيانات)
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // قم بإزالة قيد المفتاح الأجنبي أولاً (إذا قمت بإضافته)
            $table->dropForeign(['invoice_id']); // يؤدي هذا إلى إزالة قيد المفتاح الأجنبي

            // ثم قم بإزالة العمود
            $table->dropColumn('invoice_id');
        });
    }
};

