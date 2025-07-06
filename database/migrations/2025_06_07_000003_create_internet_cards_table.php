<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternetCardsTable extends Migration
{
    public function up(): void
    {
Schema::create('internet_cards', function (Blueprint $table) {
    $table->id();
    $table->string('username')->unique();
    $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
    $table->foreignId('pos_id')->constrained('point_of_sales')->onDelete('cascade');
    $table->date('expiration_date');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('internet_cards');
    }
}
