<?php

namespace App\Console\Commands;

use App\Mail\PointsExpiringMail;
use App\Models\LoyaltyTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPointsExpiryWarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:send-expiry-warnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email warnings to users about loyalty points expiring soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring loyalty points...');

        // Get warning days from config (default 30 days)
        $warningDays = config('loyalty.points.expiry_warning_days', 30);
        $expiringDate = now()->addDays($warningDays);
        
        // Get all expiring transactions grouped by user
        $expiringTransactions = LoyaltyTransaction::where('transaction_type', 'earned')
            ->whereDate('expires_at', $expiringDate->toDateString())
            ->whereNull('expiry_warning_sent_at')
            ->with('user.loyaltyPoints')
            ->get()
            ->groupBy('user_id');

        if ($expiringTransactions->isEmpty()) {
            $this->info('No expiring points found.');
            return 0;
        }

        $this->info("Found {$expiringTransactions->count()} user(s) with expiring points.");

        $sentCount = 0;
        $errorCount = 0;

        $progressBar = $this->output->createProgressBar($expiringTransactions->count());
        $progressBar->start();

        foreach ($expiringTransactions as $userId => $userTransactions) {
            $user = $userTransactions->first()->user;
            
            // Skip if user doesn't exist
            if (!$user) {
                $this->newLine();
                $this->warn("User ID {$userId} not found. Skipping...");
                $errorCount++;
                $progressBar->advance();
                continue;
            }

            try {
                // Send email notification with all expiring points for this user
                Mail::to($user->email)->send(
                    new PointsExpiringMail($user, $userTransactions)
                );
                
                // Mark all transactions as having warning sent
                LoyaltyTransaction::whereIn('id', $userTransactions->pluck('id'))
                    ->update(['expiry_warning_sent_at' => now()]);
                
                $sentCount++;
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed to send warning to {$user->email}: {$e->getMessage()}");
                $errorCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("✓ Successfully sent warnings to {$sentCount} user(s).");
        
        if ($errorCount > 0) {
            $this->warn("✗ Failed to send {$errorCount} warning(s).");
        }

        return 0;
    }
}
