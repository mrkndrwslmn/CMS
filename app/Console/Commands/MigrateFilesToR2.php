<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\CloudflareR2Service;
use App\Models\Document;

class MigrateFilesToR2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:files-to-r2 
                           {--dry-run : Run without actually moving files}
                           {--chunk=50 : Number of files to process at once}
                           {--type= : Type of files to migrate (documents, attachments, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing files from local storage to Cloudflare R2';

    protected $r2Service;
    protected $migrated = 0;
    protected $failed = 0;
    protected $skipped = 0;

    public function __construct()
    {
        parent::__construct();
        $this->r2Service = new CloudflareR2Service();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $chunkSize = (int) $this->option('chunk') ?: 50;
        $type = $this->option('type') ?: 'all';

        $this->info('Starting file migration to Cloudflare R2...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No files will actually be moved');
        }

        // Test R2 connection first
        $this->info('Testing R2 connection...');
        $connectionTest = $this->r2Service->testConnection();
        
        if (!$connectionTest['success']) {
            $this->error('R2 connection failed: ' . $connectionTest['message']);
            return Command::FAILURE;
        }
        
        $this->info('R2 connection successful!');

        // Migrate documents
        if ($type === 'all' || $type === 'documents') {
            $this->migrateDocuments($isDryRun, $chunkSize);
        }

        // Migrate request attachments
        if ($type === 'all' || $type === 'attachments') {
            $this->migrateRequestAttachments($isDryRun, $chunkSize);
        }

        // Display summary
        $this->newLine();
        $this->info('Migration Summary:');
        $this->info("✅ Migrated: {$this->migrated}");
        $this->info("❌ Failed: {$this->failed}");
        $this->info("⏭️ Skipped: {$this->skipped}");

        return Command::SUCCESS;
    }

    protected function migrateDocuments($isDryRun, $chunkSize)
    {
        $this->info('Migrating documents...');

        // Get documents that are still using local storage
        $totalDocuments = DB::table('documents')
            ->where('filePath', 'like', '/storage/%')
            ->orWhere('filePath', 'not like', 'http%')
            ->count();

        if ($totalDocuments === 0) {
            $this->info('No documents need migration.');
            return;
        }

        $this->info("Found {$totalDocuments} documents to migrate");
        $progressBar = $this->output->createProgressBar($totalDocuments);
        $progressBar->start();

        DB::table('documents')
            ->where('filePath', 'like', '/storage/%')
            ->orWhere('filePath', 'not like', 'http%')
            ->chunkById($chunkSize, function ($documents) use ($isDryRun, $progressBar) {
                foreach ($documents as $document) {
                    $this->migrateDocument($document, $isDryRun);
                    $progressBar->advance();
                }
            }, 'documentID');

        $progressBar->finish();
        $this->newLine();
    }

    protected function migrateDocument($document, $isDryRun)
    {
        // Clean up the file path
        $localPath = str_replace('/storage/', '', $document->filePath);
        $localPath = ltrim($localPath, '/');
        $fullLocalPath = storage_path('app/public/' . $localPath);

        // Check if local file exists
        if (!file_exists($fullLocalPath)) {
            $this->skipped++;
            $this->warn("Skipped document {$document->documentID}: Local file not found at {$fullLocalPath}");
            return;
        }

        // Determine R2 path based on service request
        $serviceRequestId = $document->service_request_id ?? 'unknown';
        $r2Path = "documents/{$serviceRequestId}/doc-{$document->documentID}-" . 
                  pathinfo($document->fileName, PATHINFO_FILENAME) . '.' . 
                  pathinfo($document->fileName, PATHINFO_EXTENSION);

        if ($isDryRun) {
            $this->info("Would migrate: {$document->fileName} -> {$r2Path}");
            $this->migrated++;
            return;
        }

        // Upload to R2
        $success = $this->r2Service->copyFromLocal($fullLocalPath, $r2Path);

        if ($success) {
            // Update database with R2 URL
            $r2Url = $this->r2Service->getPublicUrl($r2Path);
            
            DB::table('documents')
                ->where('documentID', $document->documentID)
                ->update([
                    'filePath' => $r2Url,
                    'updated_at' => now(),
                ]);

            $this->migrated++;
            $this->line("✅ Migrated: {$document->fileName}");

            // Optionally delete local file after successful migration
            // Uncomment if you want to clean up local files
            // unlink($fullLocalPath);

        } else {
            $this->failed++;
            $this->error("❌ Failed: {$document->fileName}");
        }
    }

    protected function migrateRequestAttachments($isDryRun, $chunkSize)
    {
        $this->info('Migrating request attachments...');

        // Get attachments that don't have file_url set (indicating they're local)
        $totalAttachments = DB::table('request_attachments')
            ->whereNull('file_url')
            ->orWhere('file_url', '')
            ->count();

        if ($totalAttachments === 0) {
            $this->info('No request attachments need migration.');
            return;
        }

        $this->info("Found {$totalAttachments} attachments to migrate");
        $progressBar = $this->output->createProgressBar($totalAttachments);
        $progressBar->start();

        DB::table('request_attachments')
            ->whereNull('file_url')
            ->orWhere('file_url', '')
            ->chunkById($chunkSize, function ($attachments) use ($isDryRun, $progressBar) {
                foreach ($attachments as $attachment) {
                    $this->migrateAttachment($attachment, $isDryRun);
                    $progressBar->advance();
                }
            }, 'id');

        $progressBar->finish();
        $this->newLine();
    }

    protected function migrateAttachment($attachment, $isDryRun)
    {
        $fullLocalPath = storage_path('app/public/' . $attachment->file_path);

        // Check if local file exists
        if (!file_exists($fullLocalPath)) {
            $this->skipped++;
            $this->warn("Skipped attachment {$attachment->id}: Local file not found at {$fullLocalPath}");
            return;
        }

        // Generate R2 path
        $r2Path = "attachments/{$attachment->service_request_id}/att-{$attachment->id}-{$attachment->stored_filename}";

        if ($isDryRun) {
            $this->info("Would migrate: {$attachment->original_filename} -> {$r2Path}");
            $this->migrated++;
            return;
        }

        // Upload to R2
        $success = $this->r2Service->copyFromLocal($fullLocalPath, $r2Path);

        if ($success) {
            // Update database with R2 URL
            $r2Url = $this->r2Service->getPublicUrl($r2Path);
            
            DB::table('request_attachments')
                ->where('id', $attachment->id)
                ->update([
                    'file_url' => $r2Url,
                    'updated_at' => now(),
                ]);

            $this->migrated++;
            $this->line("✅ Migrated: {$attachment->original_filename}");

            // Optionally delete local file after successful migration
            // Uncomment if you want to clean up local files
            // unlink($fullLocalPath);

        } else {
            $this->failed++;
            $this->error("❌ Failed: {$attachment->original_filename}");
        }
    }
}