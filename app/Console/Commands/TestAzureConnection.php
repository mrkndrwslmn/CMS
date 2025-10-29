<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AzureDatabaseService;

class TestAzureConnection extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'azure:test-connection 
                            {--connection=azure_mysql : Database connection to test}';

    /**
     * The console command description.
     */
    protected $description = 'Test Azure Database for MySQL connection';

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
        $this->info('Testing Azure Database connection...');
        $this->newLine();
        
        $result = $this->azureService->testConnection();
        
        if ($result['connected']) {
            $this->info('✅ Connection successful!');
            $this->newLine();
            
            $this->table(['Property', 'Value'], [
                ['Status', $result['status']],
                ['MySQL Version', $result['mysql_version']],
                ['SSL Enabled', $result['ssl_enabled'] ? 'Yes' : 'No'],
                ['SSL Cipher', $result['ssl_cipher']],
                ['Host', $result['connection_info']['host']],
                ['Database', $result['connection_info']['database']],
                ['Username', $result['connection_info']['username']],
            ]);
        } else {
            $this->error('❌ Connection failed!');
            $this->newLine();
            
            $this->error('Error: ' . $result['error']);
            $this->info('Suggestion: ' . $result['suggestion']);
        }
        
        return $result['connected'] ? 0 : 1;
    }
}