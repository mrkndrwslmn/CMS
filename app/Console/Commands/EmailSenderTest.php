<?php

namespace App\Console\Commands;

use App\Services\EmailSenderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EmailSenderTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-senders {--to=mark@treisadiutor.com : Email address to send test emails to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sender configurations and validate setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emailService = app(EmailSenderService::class);
        $testEmail = $this->option('to');

        $this->info('🧪 Testing Email Sender Configurations');
        $this->newLine();

        // Validate configuration
        $this->info('🔍 Validating Configuration...');
        $issues = $emailService->validateConfiguration();
        
        if (!empty($issues)) {
            $this->error('❌ Configuration Issues Found:');
            foreach ($issues as $issue) {
                $this->line("   • {$issue}");
            }
            $this->newLine();
        } else {
            $this->info('✅ Configuration validation passed');
            $this->newLine();
        }

        // Display all sender configurations
        $this->info('📧 Sender Configurations:');
        $this->newLine();

        $senders = $emailService->getSendersWithLabels();
        
        $headers = ['Type', 'Email Address', 'Display Name', 'Description'];
        $rows = [];

        foreach ($senders as $type => $details) {
            $config = $details['config'];
            $rows[] = [
                $type,
                $config['address'],
                $config['name'],
                $details['description']
            ];
        }

        $this->table($headers, $rows);
        $this->newLine();

        // Test auto-detection
        $this->info('🔍 Testing Auto-Detection:');
        $testClasses = [
            'App\Mail\NewUserCredentials',
            'App\Mail\PaymentConfirmed',
            'App\Mail\ProjectCompleted',
            'App\Mail\RequestApproved',
            'App\Mail\RevisionApproved',
            'App\Mail\RevisionRejected',
        ];

        foreach ($testClasses as $class) {
            $detectedType = $emailService->autoDetectSender($class);
            $sender = $emailService->getSender($detectedType);
            
            $this->line("   • {$class}");
            $this->line("     → Type: {$detectedType}");
            $this->line("     → From: {$sender['name']} <{$sender['address']}>");
            $this->newLine();
        }

        // Optionally send test emails
        if ($this->confirm('Would you like to send test emails to verify SMTP configuration?')) {
            $this->sendTestEmails($emailService, $testEmail);
        }

        $this->info('✅ Email sender test completed!');
    }

    /**
     * Send test emails for each sender type
     */
    private function sendTestEmails(EmailSenderService $emailService, string $testEmail): void
    {
        $this->info("📤 Sending test emails to: {$testEmail}");
        $this->newLine();

        $senders = $emailService->getSendersWithLabels();
        
        foreach ($senders as $type => $details) {
            try {
                $config = $details['config'];
                
                Mail::raw(
                    "This is a test email from the {$details['label']} sender.\n\n" .
                    "Sender Type: {$type}\n" .
                    "From Address: {$config['address']}\n" .
                    "From Name: {$config['name']}\n" .
                    "Description: {$details['description']}\n\n" .
                    "If you received this email, your {$type} sender configuration is working correctly!\n\n" .
                    "Sent at: " . now()->format('Y-m-d H:i:s T'),
                    function ($message) use ($config, $testEmail, $type) {
                        $message->to($testEmail)
                                ->from($config['address'], $config['name'])
                                ->subject("Test Email - {$type} Sender - Treis Adiutor");
                    }
                );

                $this->info("   ✅ {$type}: Sent successfully");
                
            } catch (\Exception $e) {
                $this->error("   ❌ {$type}: Failed - {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("📬 Check your inbox at: {$testEmail}");
    }
}
