<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AzureDatabaseService;

class MigrateToAzure extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'azure:migrate 
                            {--dry-run : Perform a dry run without actual migration}
                            {--force : Force migration without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Migrate MySQL database to Azure Database for MySQL';

    private AzureDatabaseService $azureService;

    public function __construct(AzureDatabaseService $azureService)
    {
        parent::__construct();
        $this->azureService = $azureService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Azure Database Migration Tool');
        $this->newLine();
        
        // Pre-flight checks
        if (!$this->performPreflightChecks()) {
            return 1;
        }
        
        // Dry run mode
        if ($this->option('dry-run')) {
            $this->info('🔍 Performing dry run...');
            return $this->performDryRun();
        }
        
        // Confirmation prompt
        if (!$this->option('force')) {
            if (!$this->confirm('This will migrate your local MySQL database to Azure. Continue?')) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }
        
        // Perform actual migration
        return $this->performMigration();
    }

    /**
     * Perform pre-flight checks
     */
    private function performPreflightChecks(): bool
    {
        $this->info('🔍 Performing pre-flight checks...');
        
        // Check Azure connection
        $this->line('  • Testing Azure connection...');
        $connectionTest = $this->azureService->testConnection();
        
        if (!$connectionTest['connected']) {
            $this->error('  ❌ Azure connection failed: ' . $connectionTest['error']);
            return false;
        }
        $this->info('  ✅ Azure connection successful');
        
        // Check local database
        $this->line('  • Checking local database...');
        try {
            \DB::connection('mysql')->getPdo();
            $this->info('  ✅ Local database accessible');
        } catch (\Exception $e) {
            $this->error('  ❌ Local database connection failed: ' . $e->getMessage());
            return false;
        }
        
        // Check required directories
        $this->line('  • Checking backup directories...');
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $this->info('  ✅ Backup directory ready');
        
        $this->newLine();
        return true;
    }

    /**
     * Perform dry run
     */
    private function performDryRun(): int
    {
        $this->info('This would perform the following steps:');
        $this->line('  1. Export local MySQL database');
        $this->line('  2. Validate Azure connection');
        $this->line('  3. Import data to Azure Database');
        $this->line('  4. Verify data integrity');
        
        $this->newLine();
        $this->info('✅ Dry run completed successfully');
        
        return 0;
    }

    /**
     * Perform actual migration
     */
    private function performMigration(): int
    {
        $this->info('🚀 Starting migration process...');
        $this->newLine();
        
        $progressBar = $this->output->createProgressBar(4);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        
        $progressBar->setMessage('Initializing...');
        $progressBar->start();
        
        try {
            // Step 1: Export
            $progressBar->setMessage('Exporting local database...');
            $progressBar->advance();
            
            // Step 2: Validate
            $progressBar->setMessage('Validating Azure connection...');
            $progressBar->advance();
            
            // Step 3: Import
            $progressBar->setMessage('Importing to Azure...');
            $progressBar->advance();
            
            // Step 4: Verify
            $progressBar->setMessage('Verifying migration...');
            $progressBar->advance();
            
            $progressBar->finish();
            $this->newLine(2);
            
            // Perform actual migration
            $result = $this->azureService->migrateToAzure();
            
            if ($result['status'] === 'success') {
                $this->info('✅ Migration completed successfully!');
                $this->newLine();
                
                // Display verification results
                if (isset($result['verification'])) {
                    $this->displayVerification($result['verification']);
                }
                
                $this->info('🎉 Your application is now using Azure Database for MySQL!');
                $this->line('Don\'t forget to update your .env file to use the azure_mysql connection.');
                
                return 0;
            } else {
                $this->error('❌ Migration failed: ' . $result['message']);
                return 1;
            }
            
        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine(2);
            $this->error('❌ Migration failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Display verification results
     */
    private function displayVerification(array $verification): void
    {
        $this->info('📊 Migration Verification:');
        
        $tableData = [];
        $allMatch = true;
        
        foreach ($verification as $table => $data) {
            $status = $data['matches'] ? '✅' : '❌';
            $tableData[] = [
                $table,
                $data['local_count'],
                $data['azure_count'],
                $status
            ];
            
            if (!$data['matches']) {
                $allMatch = false;
            }
        }
        
        $this->table(['Table', 'Local Count', 'Azure Count', 'Status'], $tableData);
        
        if ($allMatch) {
            $this->info('✅ All table counts match - migration verified successfully!');
        } else {
            $this->warn('⚠️  Some table counts don\'t match - please review the migration');
        }
        
        $this->newLine();
    }
}