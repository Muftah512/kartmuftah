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
            Schema::table('internet_cards', function (Blueprint $table) {
                // أضف عمود customer_phone فقط إذا لم يكن موجودًا بالفعل
                if (!Schema::hasColumn('internet_cards', 'customer_phone')) {
                    $table->string('customer_phone')->nullable();
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('internet_cards', function (Blueprint $table) {
                // احذف عمود customer_phone فقط إذا كان موجودًا
                if (Schema::hasColumn('internet_cards', 'customer_phone')) {
                    $table->dropColumn('customer_phone');
                }
            });
        }
    };
    