<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('download_speed')->nullable(); // سرعة التحميل
            $table->integer('upload_speed')->nullable();   // سرعة الرفع
            $table->integer('device_limit')->nullable();   // حد عدد الأجهزة
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['download_speed', 'upload_speed', 'device_limit']);
        });
    }
};
