<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudflareR2Service;

class R2QuickTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'r2:quick-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform a quick test of R2 functionality with sample operations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Running Cloudflare R2 Quick Test...');
        
        $r2Service = new CloudflareR2Service();

        // 1. Test connection
        $this->info('1. Testing R2 connection...');
        $connectionTest = $r2Service->testConnection();
        
        if (!$connectionTest['success']) {
            $this->error('❌ Connection failed: ' . $connectionTest['message']);
            return Command::FAILURE;
        }
        $this->info('✅ Connection successful!');

        // 2. Test file operations
        $this->info('2. Testing file operations...');
        
        // Create a test file content
        $testContent = "This is a test file created at " . now()->toISOString();
        $testFileName = 'test-file-' . time() . '.txt';
        $testPath = 'test/' . $testFileName;

        try {
            // Upload test content
            $disk = $r2Service->getDisk();
            $uploadResult = $disk->put($testPath, $testContent, ['visibility' => 'public']);
            
            if ($uploadResult) {
                $this->info('✅ File upload successful');
                
                // Test URL generation
                $url = $r2Service->getPublicUrl($testPath);
                $this->info("📎 Public URL: {$url}");
                
                // Test file existence
                if ($r2Service->fileExists($testPath)) {
                    $this->info('✅ File existence check passed');
                } else {
                    $this->warn('⚠️ File existence check failed');
                }
                
                // Test file size
                $size = $r2Service->getFileSize($testPath);
                if ($size !== false) {
                    $this->info("📏 File size: {$size} bytes");
                } else {
                    $this->warn('⚠️ Could not get file size');
                }
                
                // Test file metadata
                $metadata = $r2Service->getFileMetadata($testPath);
                if ($metadata) {
                    $this->info('✅ File metadata retrieved');
                } else {
                    $this->warn('⚠️ Could not get file metadata');
                }
                
                // Clean up test file
                if ($r2Service->deleteFile($testPath)) {
                    $this->info('✅ File deletion successful');
                } else {
                    $this->warn('⚠️ File deletion failed');
                }
                
            } else {
                $this->error('❌ File upload failed');
                return Command::FAILURE;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error during file operations: ' . $e->getMessage());
            return Command::FAILURE;
        }

        // 3. Test directory listing
        $this->info('3. Testing directory operations...');
        try {
            $files = $r2Service->listFiles('test/');
            $this->info('✅ Directory listing successful (' . count($files) . ' files found)');
        } catch (\Exception $e) {
            $this->warn('⚠️ Directory listing error: ' . $e->getMessage());
        }

        // 4. Summary
        $this->newLine();
        $this->info('🎉 R2 Quick Test Completed Successfully!');
        $this->newLine();
        $this->info('Next steps:');
        $this->info('1. Upload some test files through your application');
        $this->info('2. Run: php artisan migrate:files-to-r2 --dry-run');
        $this->info('3. When ready, run: php artisan migrate:files-to-r2');
        $this->info('4. Monitor logs for any issues');

        return Command::SUCCESS;
    }
}