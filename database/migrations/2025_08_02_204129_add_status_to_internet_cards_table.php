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
                // أضف عمود status فقط إذا لم يكن موجودًا بالفعل
                if (!Schema::hasColumn('internet_cards', 'status')) {
                    $table->string('status')->default('active');
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('internet_cards', function (Blueprint $table) {
                // احذف عمود status فقط إذا كان موجودًا
                if (Schema::hasColumn('internet_cards', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    };
    