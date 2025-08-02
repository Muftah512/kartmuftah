<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // إضافة العمود كـ foreign key
            $table->foreignId('pos_id')
                  ->nullable()
                  ->constrained('point_of_sales')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['pos_id']);
            $table->dropColumn('pos_id');
        });
    }
};