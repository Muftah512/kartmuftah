<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type; // Required for Doctrine DBAL types
use Doctrine\DBAL\Types\StringType; // Required for Doctrine DBAL types for ->change() method

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure the StringType is registered with Doctrine DBAL.
        // This is a common workaround for issues with the `->change()` method in some Laravel/Doctrine DBAL versions.
        if (!Type::hasType('string')) {
            Type::addType('string', StringType::class);
        }

        Schema::table('point_of_sales', function (Blueprint $table) {
            // Check if the 'user_id' column already exists.
            if (!Schema::hasColumn('point_of_sales', 'user_id')) {
                // If 'user_id' does NOT exist, add it as a nullable unsignedBigInteger.
                // Adjust 'id' to an appropriate existing column after which you want 'user_id'.
                // For example, 'id', 'created_at', or any other column that exists.
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            } else {
                // If 'user_id' DOES exist, then proceed to change its properties (make it nullable).
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }

            // If you also have a foreign key for 'user_id' in 'point_of_sales' table,
            // you would add similar foreign key logic here as you did for 'invoices'.
            // Example (uncomment and adjust if applicable):
            /*
            $foreignKeyName = $this->getForeignKeyName($table->getTable(), 'user_id');
            if (!$foreignKeyName) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users') // Assuming 'users' table
                      ->onDelete('set null'); // Or 'cascade' depending on your requirements
            }
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_of_sales', function (Blueprint $table) {
            // If there was a foreign key for 'user_id', drop it safely if it exists.
            /*
            $foreignKeyName = $this->getForeignKeyName($table->getTable(), 'user_id');
            if ($foreignKeyName) {
                $table->dropForeign($foreignKeyName);
            }
            */

            // Revert 'user_id' to non-nullable or drop it, depending on the migration's original intent.
            // Only attempt to change/drop if the column exists.
            if (Schema::hasColumn('point_of_sales', 'user_id')) {
                // IMPORTANT:
                // If this migration's primary purpose was to ADD the 'user_id' column,
                // you should uncomment the line below to drop it on rollback:
                // $table->dropColumn('user_id');

                // If 'user_id' already existed and this migration only made it nullable,
                // then reverting to non-nullable is appropriate.
                // WARNING: This will fail if there are any NULL values in 'user_id' in your database.
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            }
        });
    }

    /**
     * Get the foreign key name for a given table and column.
     * This method uses Doctrine DBAL to inspect the database schema.
     *
     * @param string $table The name of the table.
     * @param string $column The name of the column.
     * @return string|null The foreign key name if found, otherwise null.
     */
    protected function getForeignKeyName($table, $column)
    {
        $conn = Schema::getConnection();
        $dbSchemaManager = $conn->getDoctrineSchemaManager();
        $foreignKeys = $dbSchemaManager->listTableForeignKeys($table);

        foreach ($foreignKeys as $foreignKey) {
            // Check if the column is part of this foreign key
            if (in_array($column, $foreignKey->getColumns())) {
                return $foreignKey->getName();
            }
        }

        return null;
    }
};