<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UnifyPosColumnsInInvoices extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'point_of_sale_id')) {
                // حذف القيد بطريقة آمنة
                $this->safeDropForeign($table, 'point_of_sale_id');
                
                $table->dropColumn('point_of_sale_id');
            }
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'point_of_sale_id')) {
                $table->foreignId('point_of_sale_id')
                      ->nullable()
                      ->constrained('point_of_sales');
            }
        });
    }
    
    /**
     * حذف آمن للقيد الخارجي بدون استخدام Doctrine
     */
    protected function safeDropForeign(Blueprint $table, $column)
    {
        $tableName = $table->getTable();
        $indexName = $this->getForeignKeyName($tableName, $column);
        
        if ($indexName) {
            $table->dropForeign($indexName);
        }
    }
    
    /**
     * الحصول على اسم القيد الخارجي
     */
    protected function getForeignKeyName($table, $column)
    {
        $conn = Schema::getConnection();
        $dbSchemaManager = $conn->getDoctrineSchemaManager();
        $foreignKeys = $dbSchemaManager->listTableForeignKeys($table);
        
        foreach ($foreignKeys as $foreignKey) {
            if (in_array($column, $foreignKey->getColumns())) {
                return $foreignKey->getName();
            }
        }
        
        return null;
    }
}