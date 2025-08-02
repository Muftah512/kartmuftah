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
                // أضف عمود expires_at فقط إذا لم يكن موجودًا بالفعل
                if (!Schema::hasColumn('internet_cards', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable();
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('internet_cards', function (Blueprint $table) {
                // احذف عمود expires_at فقط إذا كان موجودًا
                if (Schema::hasColumn('internet_cards', 'expires_at')) {
                    $table->dropColumn('expires_at');
                }
            });
        }
    };
    