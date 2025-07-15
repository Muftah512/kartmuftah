<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointOfSalesTable extends Migration
{
   public function up()
   {
    Schema::create('point_of_sales', function (Blueprint $table) {	
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('location')->nullable();
        $table->decimal('balance', 15, 2)->default(0);
        $table->boolean('is_active')->default(true);
        $table->boolean('whatsapp_enabled')->default(true); // ÅÖÇÝÉ åÐÇ ÇáÍÞá
        $table->foreignId('accountant_id')->nullable()->constrained('users');
        $table->timestamps();
    });
}
    public function down()
    {
        Schema::dropIfExists('point_of_sales');
    }
}