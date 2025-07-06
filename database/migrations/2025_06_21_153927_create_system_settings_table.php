<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingsTable extends Migration
{
    public function up()
    {
Schema::create('system_settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
}