<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إذا العمود غير موجود، أضفه
        if (! Schema::hasColumn('internet_cards', 'pos_id')) {
            Schema::table('internet_cards', function (Blueprint $table) {
                $table->foreignId('pos_id')
                      ->after('package_id')             // أو أي عمود حقيقي لديك
                      ->constrained('point_of_sales')
                      ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        // إذا العمود موجود، احذفه (للتراجع)
        if (Schema::hasColumn('internet_cards', 'pos_id')) {
            Schema::table('internet_cards', function (Blueprint $table) {
                $table->dropConstrainedForeignId('pos_id');
            });
        }
    }
};
