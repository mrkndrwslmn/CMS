<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AdiutorProfile;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\ProjectAssignment;
use Illuminate\Support\Facades\DB;

class SetupEarningsSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'earnings:setup
                            {--default-rate=500 : Default hourly rate for adiutors}
                            {--currency=PHP : Default currency}
                            {--min-payout=500 : Minimum payout amount}
                            {--force : Force update existing values}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the earnings system for existing adiutors and time entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Earnings System...');
        $this->newLine();

        $defaultRate = $this->option('default-rate');
        $currency = $this->option('currency');
        $minPayout = $this->option('min-payout');
        $force = $this->option('force');

        // Step 1: Update Adiutor Profiles
        $this->info('Step 1: Updating adiutor profiles...');
        $this->updateAdiutorProfiles($defaultRate, $currency, $minPayout, $force);
        $this->newLine();

        // Step 2: Update Time Entries with Hourly Rates
        $this->info('Step 2: Calculating earnings for existing time entries...');
        $this->updateTimeEntries();
        $this->newLine();

        // Step 3: Update Task Earnings
        $this->info('Step 3: Updating task earnings...');
        $this->updateTaskEarnings();
        $this->newLine();

        // Step 4: Update Project Assignment Earnings
        $this->info('Step 4: Updating project assignment earnings...');
        $this->updateProjectAssignments();
        $this->newLine();

        $this->info('✅ Earnings system setup complete!');
        $this->displaySummary();
    }

    /**
     * Update adiutor profiles with default rates
     */
    protected function updateAdiutorProfiles($defaultRate, $currency, $minPayout, $force)
    {
        $adiutors = User::where('role', 'adiutor')->get();
        $updated = 0;
        $created = 0;

        $progressBar = $this->output->createProgressBar($adiutors->count());
        $progressBar->start();

        foreach ($adiutors as $adiutor) {
            if (!$adiutor->adiutorProfile) {
                // Create new profile
                AdiutorProfile::create([
                    'user_id' => $adiutor->id,
                    'standard_hourly_rate' => $defaultRate,
                    'currency' => $currency,
                    'minimum_payout_amount' => $minPayout,
                    'status' => 'active',
                ]);
                $created++;
            } else {
                // Update existing profile
                $data = [];
                
                if ($force || !$adiutor->adiutorProfile->standard_hourly_rate) {
                    $data['standard_hourly_rate'] = $defaultRate;
                }
                
                if ($force || !$adiutor->adiutorProfile->currency) {
                    $data['currency'] = $currency;
                }
                
                if ($force || !$adiutor->adiutorProfile->minimum_payout_amount) {
                    $data['minimum_payout_amount'] = $minPayout;
                }

                if (!empty($data)) {
                    $adiutor->adiutorProfile->update($data);
                    $updated++;
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("  ✓ Created: $created profiles");
        $this->info("  ✓ Updated: $updated profiles");
    }

    /**
     * Update time entries with hourly rates and calculated amounts
     */
    protected function updateTimeEntries()
    {
        $timeEntries = TimeEntry::whereNotNull('end_time')
            ->whereNull('calculated_amount')
            ->with(['task', 'adiutor.adiutorProfile'])
            ->get();

        if ($timeEntries->isEmpty()) {
            $this->info('  ℹ No time entries need updating');
            return;
        }

        $progressBar = $this->output->createProgressBar($timeEntries->count());
        $progressBar->start();

        $updated = 0;

        foreach ($timeEntries as $entry) {
            // Get hourly rate from task or adiutor
            $hourlyRate = null;
            
            if ($entry->task) {
                $hourlyRate = $entry->task->getEffectiveHourlyRate();
            }
            
            if (!$hourlyRate && $entry->adiutor->adiutorProfile) {
                $hourlyRate = $entry->adiutor->adiutorProfile->standard_hourly_rate;
            }

            if ($hourlyRate && $entry->duration_minutes) {
                $hours = $entry->duration_minutes / 60;
                $calculatedAmount = $hours * $hourlyRate;

                $entry->update([
                    'hourly_rate' => $hourlyRate,
                    'calculated_amount' => $calculatedAmount,
                ]);

                $updated++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("  ✓ Updated: $updated time entries");
    }

    /**
     * Update task earnings from time entries
     */
    protected function updateTaskEarnings()
    {
        $tasks = Task::whereHas('timeEntries')->get();

        if ($tasks->isEmpty()) {
            $this->info('  ℹ No tasks with time entries found');
            return;
        }

        $progressBar = $this->output->createProgressBar($tasks->count());
        $progressBar->start();

        foreach ($tasks as $task) {
            $task->updateEarnings();
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("  ✓ Updated earnings for {$tasks->count()} tasks");
    }

    /**
     * Update project assignment earnings
     */
    protected function updateProjectAssignments()
    {
        $assignments = ProjectAssignment::all();

        if ($assignments->isEmpty()) {
            $this->info('  ℹ No project assignments found');
            return;
        }

        $progressBar = $this->output->createProgressBar($assignments->count());
        $progressBar->start();

        foreach ($assignments as $assignment) {
            $assignment->updateEarnings();
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("  ✓ Updated earnings for {$assignments->count()} assignments");
    }

    /**
     * Display summary of the setup
     */
    protected function displaySummary()
    {
        $this->newLine();
        $this->info('📊 Summary:');
        $this->line('─────────────────────────────────────');

        // Count adiutors with rates
        $adiutorsWithRates = User::where('role', 'adiutor')
            ->whereHas('adiutorProfile', function($q) {
                $q->whereNotNull('standard_hourly_rate');
            })
            ->count();

        $this->info("  • Adiutors with standard rates: $adiutorsWithRates");

        // Count time entries with earnings
        $entriesWithEarnings = TimeEntry::whereNotNull('calculated_amount')->count();
        $totalEarnings = TimeEntry::whereNotNull('calculated_amount')->sum('calculated_amount');

        $this->info("  • Time entries with earnings: $entriesWithEarnings");
        $this->info("  • Total calculated earnings: ₱" . number_format($totalEarnings, 2));

        // Count tasks with earnings
        $tasksWithEarnings = Task::whereNotNull('calculated_earnings')
            ->where('calculated_earnings', '>', 0)
            ->count();

        $this->info("  • Tasks with calculated earnings: $tasksWithEarnings");

        $this->line('─────────────────────────────────────');
        $this->newLine();
        $this->info('🎉 You can now use the earnings and payout system!');
        $this->newLine();
        $this->comment('Next steps:');
        $this->comment('  1. Review adiutor profiles to adjust rates as needed');
        $this->comment('  2. Configure payout methods for each adiutor');
        $this->comment('  3. Review and approve pending time entries');
        $this->comment('  4. Process any pending payout requests');
    }
}
