<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // تأكد أن العمود موجود (احتياط)
        if (!Schema::hasColumn('internet_cards', 'username')) {
            Schema::table('internet_cards', function (Blueprint $table) {
                // 191 آمن للفهارس القديمة؛ واليوزر عندك 8–10 أرقام فقط
                $table->string('username', 191)->after('id');
            });
        }

        // لا تنشئ الفهرس لو كان موجودًا مسبقًا
        $indexName = 'internet_cards_username_unique';
        $exists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', 'internet_cards')
            ->where('index_name', $indexName)
            ->exists();

        if (!$exists) {
            Schema::table('internet_cards', function (Blueprint $table) use ($indexName) {
                $table->unique('username', $indexName);
            });
        }
    }

    public function down(): void
    {
        $indexName = 'internet_cards_username_unique';

        $exists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', 'internet_cards')
            ->where('index_name', $indexName)
            ->exists();

        if ($exists) {
            Schema::table('internet_cards', function (Blueprint $table) use ($indexName) {
                $table->dropUnique($indexName);
            });
        }
    }
};
