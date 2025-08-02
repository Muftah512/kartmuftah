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
        Schema::table('users', function (Blueprint $table) {
            // أضف عمود 'role' فقط إذا لم يكن موجودًا
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('pos')->after('password');
            }
            // أضف عمود 'is_active' فقط إذا لم يكن موجودًا
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // احذف عمود 'is_active' فقط إذا كان موجودًا
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
            // احذف عمود 'role' فقط إذا كان موجودًا
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};