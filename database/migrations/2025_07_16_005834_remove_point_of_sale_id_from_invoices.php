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
    Schema::table('invoices', function (Blueprint $table) {
        if (Schema::hasColumn('invoices', 'point_of_sale_id')) {
            $table->dropColumn('point_of_sale_id');
        }
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('invoices', function (Blueprint $table) {
        $table->foreignId('point_of_sale_id')->nullable()->constrained();
    });
}
};
