<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

/**
 * Trait to use cms.sql as the database schema for testing
 * instead of migrations (which are out of sync with actual database)
 * 
 * Usage: Add `use UseCmsSqlSchema;` to your test class
 */
trait UseCmsSqlSchema
{
    /**
     * Track if schema has been imported in this test run
     */
    protected static bool $schemaImported = false;

    /**
     * Set up the database schema from cms.sql
     */
    protected function setUpSchema(): void
    {
        if (static::$schemaImported) {
            // Schema already imported, just truncate tables for clean state
            $this->truncateAllTables();
            return;
        }

        $this->importCmsSql();
        static::$schemaImported = true;
    }

    /**
     * Import the cms.sql schema file
     */
    protected function importCmsSql(): void
    {
        $sqlFile = base_path('cms.sql');
        
        if (!file_exists($sqlFile)) {
            $this->fail('cms.sql file not found at: ' . $sqlFile);
        }

        // Drop all existing tables first
        $this->dropAllTables();

        // Import the SQL file
        $sql = file_get_contents($sqlFile);
        
        // Split by statements and execute (handle MySQL-specific syntax)
        DB::unprepared($sql);
    }

    /**
     * Drop all tables in the test database
     */
    protected function dropAllTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = "Tables_in_{$dbName}";
        
        foreach ($tables as $table) {
            $tableName = $table->$key ?? $table->{array_keys((array)$table)[0]};
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Truncate all tables (faster than dropping and recreating)
     */
    protected function truncateAllTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database');
        $key = "Tables_in_{$dbName}";
        
        foreach ($tables as $table) {
            $tableName = $table->$key ?? $table->{array_keys((array)$table)[0]};
            // Skip migrations table if using it
            if ($tableName !== 'migrations') {
                DB::table($tableName)->truncate();
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reset the schema imported flag (useful for specific test scenarios)
     */
    protected static function resetSchemaImported(): void
    {
        static::$schemaImported = false;
    }
}
