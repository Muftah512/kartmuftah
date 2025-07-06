<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ÊÃßÏ ãä Ãä ÞÇÚÏÉ ÇáÈíÇäÇÊ ãÚÏÉ ÇÝÊÑÇÖíðÇ Úáì utf8mb4
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('size_mb')->nullable()->default(0);
            $table->unsignedInteger('validity_days')->nullable()->default(0);
            $table->string('mikrotik_profile')->nullable();
            $table->timestamps();
        });

        // ÊÍæíá ÇáÌÏæá áÏÚã utf8mb4 ÈÇáßÇãá ãÚ Collation ãäÇÓÈ
        DB::statement("ALTER TABLE `packages` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
}
