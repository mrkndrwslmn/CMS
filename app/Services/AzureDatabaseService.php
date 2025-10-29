<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AzureDatabaseService
{
    /**
     * Test Azure Database connection
     */
    public function testConnection(): array
    {
        try {
            $connection = DB::connection('azure_mysql');
            $pdo = $connection->getPdo();
            
            // Test basic connectivity
            $result = $connection->select('SELECT 1 as test_connection');
            
            // Get server information
            $serverInfo = $connection->select('SELECT VERSION() as mysql_version')[0];
            
            // Test SSL status
            $sslStatus = $connection->select("SHOW STATUS LIKE 'Ssl_cipher'")[0] ?? null;
            
            return [
                'status' => 'success',
                'connected' => true,
                'mysql_version' => $serverInfo->mysql_version,
                'ssl_enabled' => !empty($sslStatus->Value),
                'ssl_cipher' => $sslStatus->Value ?? 'Not available',
                'connection_info' => [
                    'host' => Config::get('database.connections.azure_mysql.host'),
                    'database' => Config::get('database.connections.azure_mysql.database'),
                    'username' => Config::get('database.connections.azure_mysql.username'),
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'connected' => false,
                'error' => $e->getMessage(),
                'suggestion' => $this->getSuggestion($e->getMessage())
            ];
        }
    }

    /**
     * Migrate from local MySQL to Azure
     */
    public function migrateToAzure(): array
    {
        try {
            Log::info('Starting Azure database migration');
            
            // Step 1: Test Azure connection
            $connectionTest = $this->testConnection();
            if (!$connectionTest['connected']) {
                throw new \Exception('Cannot connect to Azure database: ' . $connectionTest['error']);
            }
            
            // Step 2: Export local database schema and data
            $exportResult = $this->exportLocalDatabase();
            if (!$exportResult['success']) {
                throw new \Exception('Failed to export local database: ' . $exportResult['error']);
            }
            
            // Step 3: Import to Azure database
            $importResult = $this->importToAzure($exportResult['dump_file']);
            if (!$importResult['success']) {
                throw new \Exception('Failed to import to Azure database: ' . $importResult['error']);
            }
            
            // Step 4: Verify migration
            $verificationResult = $this->verifyMigration();
            
            Log::info('Azure database migration completed successfully');
            
            return [
                'status' => 'success',
                'message' => 'Migration completed successfully',
                'verification' => $verificationResult
            ];
            
        } catch (\Exception $e) {
            Log::error('Azure database migration failed: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => 'Migration failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Export local database
     */
    private function exportLocalDatabase(): array
    {
        try {
            $config = Config::get('database.connections.mysql');
            $timestamp = date('Y-m-d_H-i-s');
            $dumpFile = storage_path("app/backups/mysql_dump_{$timestamp}.sql");
            
            // Ensure backup directory exists
            if (!is_dir(dirname($dumpFile))) {
                mkdir(dirname($dumpFile), 0755, true);
            }
            
            // Build mysqldump command
            $command = sprintf(
                'docker-compose exec -T mysql mysqldump -h%s -P%s -u%s -p%s --single-transaction --routines --triggers %s > %s',
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password'],
                $config['database'],
                $dumpFile
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new \Exception('mysqldump failed with return code: ' . $returnCode);
            }
            
            return [
                'success' => true,
                'dump_file' => $dumpFile,
                'size' => filesize($dumpFile)
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Import dump to Azure database
     */
    private function importToAzure(string $dumpFile): array
    {
        try {
            $config = Config::get('database.connections.azure_mysql');
            
            $command = sprintf(
                'mysql -h%s -P%s -u%s -p%s --ssl-ca=%s %s < %s',
                $config['host'],
                $config['port'],
                $config['username'],
                $config['password'],
                $config['options'][PDO::MYSQL_ATTR_SSL_CA] ?? '',
                $config['database'],
                $dumpFile
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new \Exception('MySQL import failed with return code: ' . $returnCode);
            }
            
            return ['success' => true];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify migration by comparing table counts
     */
    private function verifyMigration(): array
    {
        $localConnection = DB::connection('mysql');
        $azureConnection = DB::connection('azure_mysql');
        
        $verification = [];
        
        // Get table list
        $tables = $localConnection->select("SHOW TABLES");
        $tableColumn = 'Tables_in_' . Config::get('database.connections.mysql.database');
        
        foreach ($tables as $table) {
            $tableName = $table->$tableColumn;
            
            $localCount = $localConnection->table($tableName)->count();
            $azureCount = $azureConnection->table($tableName)->count();
            
            $verification[$tableName] = [
                'local_count' => $localCount,
                'azure_count' => $azureCount,
                'matches' => $localCount === $azureCount
            ];
        }
        
        return $verification;
    }

    /**
     * Get suggestion based on error message
     */
    private function getSuggestion(string $error): string
    {
        if (strpos($error, 'SSL') !== false) {
            return 'Check SSL configuration and certificate paths';
        }
        
        if (strpos($error, 'timeout') !== false) {
            return 'Check network connectivity and firewall rules';
        }
        
        if (strpos($error, 'Access denied') !== false) {
            return 'Verify username, password, and user permissions';
        }
        
        if (strpos($error, 'Unknown database') !== false) {
            return 'Ensure the database exists in Azure MySQL server';
        }
        
        return 'Check Azure MySQL server configuration and connection parameters';
    }
}