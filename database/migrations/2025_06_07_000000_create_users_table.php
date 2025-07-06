<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
                        $table->string('role')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->unique()->nullable();
            $table->unsignedBigInteger('point_of_sale_id')->nullable(); // ÓíÊã ÅÖÇÝÉ ÇáÚáÇÞÉ áÇÍÞÇð
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_activity')->nullable(); // ÌÏíÏ ?
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}