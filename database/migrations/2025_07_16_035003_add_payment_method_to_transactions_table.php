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
            // إضافة عمود 'payment_method'
            // يمكنك اختيار نوع البيانات المناسب. varchar(255) هو الشائع.
            // يمكن أن يكون nullable إذا لم يكن إلزاميًا دائمًا.
            $table->string('payment_method')->nullable()->after('balance_after'); // يمكنك تغيير after حسب مكان العمود المفضل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // حذف عمود 'payment_method' إذا تراجعت عن الـ Migration
            $table->dropColumn('payment_method');
        });
    }
};

