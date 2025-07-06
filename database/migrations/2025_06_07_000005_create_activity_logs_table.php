<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
public function up(): void
{
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->string('action');
        $table->text('description')->nullable();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        // ÈÇÞí ÇáÃÚãÏÉ...
        $table->timestamps();

        // ÚÏøá åÐÇ ÇáÓØÑ:
       $table->foreignId('pos_id')->nullable()->constrained('point_of_sales')->onDelete('cascade');

    });
}

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}