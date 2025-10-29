<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CloudflareR2Service;

class TestR2Connection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r2:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Cloudflare R2 connection and configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Cloudflare R2 connection...');

        $r2Service = new CloudflareR2Service();
        
        // Test connection
        $result = $r2Service->testConnection();

        if ($result['success']) {
            $this->info('✅ ' . $result['message']);
            
            // Show configuration
            $this->newLine();
            $this->info('R2 Configuration:');
            $this->info('Bucket: ' . config('filesystems.disks.r2.bucket'));
            $this->info('Endpoint: ' . config('filesystems.disks.r2.endpoint'));
            $this->info('Custom Domain: ' . (config('filesystems.disks.r2.url') ?: 'Not configured'));
            
            return Command::SUCCESS;
        } else {
            $this->error('❌ ' . $result['message']);
            
            $this->newLine();
            $this->warn('Please check your R2 configuration in .env file:');
            $this->warn('- CLOUDFLARE_R2_ACCESS_KEY_ID');
            $this->warn('- CLOUDFLARE_R2_SECRET_ACCESS_KEY');
            $this->warn('- CLOUDFLARE_R2_BUCKET');
            $this->warn('- CLOUDFLARE_R2_ENDPOINT');
            $this->warn('- CLOUDFLARE_R2_URL (optional custom domain)');
            
            return Command::FAILURE;
        }
    }
}