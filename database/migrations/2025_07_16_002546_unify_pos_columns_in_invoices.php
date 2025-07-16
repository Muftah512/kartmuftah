<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UnifyPosColumnsInInvoices extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // 1. حذف الحقل الزائد
            if (Schema::hasColumn('invoices', 'point_of_sale_id')) {
                $table->dropColumn('point_of_sale_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // التراجع عن الحذف
            if (!Schema::hasColumn('invoices', 'point_of_sale_id')) {
                $table->foreignId('point_of_sale_id')->nullable()->constrained();
            }
        });
    }
}