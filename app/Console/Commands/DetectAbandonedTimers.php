<?php

namespace App\Console\Commands;

use App\Models\TimeEntry;
use App\Models\User;
use App\Notifications\AbandonedTimerNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DetectAbandonedTimers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timers:detect-abandoned
                            {--hours=8 : Hours threshold for considering a timer abandoned}
                            {--auto-stop : Automatically stop abandoned timers}
                            {--notify : Send notifications to adiutors with abandoned timers}
                            {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect and optionally handle abandoned timers (running for too long)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hoursThreshold = (int) $this->option('hours');
        $autoStop = $this->option('auto-stop');
        $notify = $this->option('notify');
        $dryRun = $this->option('dry-run');

        $this->info("🔍 Detecting abandoned timers (running > {$hoursThreshold} hours)...");

        if ($dryRun) {
            $this->warn('Running in dry-run mode - no changes will be made.');
        }

        // Find timers that have been running too long
        $abandonedTimers = TimeEntry::whereNull('end_time')
            ->where('start_time', '<', now()->subHours($hoursThreshold))
            ->with(['adiutor:id,fullName,email', 'task:taskID,taskName', 'project:id,title'])
            ->get();

        if ($abandonedTimers->isEmpty()) {
            $this->info('✅ No abandoned timers found.');
            return Command::SUCCESS;
        }

        $this->warn("⚠️ Found {$abandonedTimers->count()} abandoned timer(s):");
        $this->newLine();

        // Display table of abandoned timers
        $tableData = $abandonedTimers->map(function ($timer) {
            $runningHours = round($timer->start_time->diffInMinutes(now()) / 60, 2);
            return [
                'ID' => $timer->id,
                'Adiutor' => $timer->adiutor?->fullName ?? 'Unknown',
                'Task' => $timer->task?->taskName ?? 'N/A',
                'Project' => $timer->project?->title ?? 'N/A',
                'Started' => $timer->start_time->format('M d, Y H:i'),
                'Running' => "{$runningHours} hrs",
            ];
        })->toArray();

        $this->table(
            ['ID', 'Adiutor', 'Task', 'Project', 'Started', 'Running'],
            $tableData
        );

        $notified = 0;
        $stopped = 0;

        foreach ($abandonedTimers as $timer) {
            // Send notification if requested
            if ($notify && $timer->adiutor) {
                if (!$dryRun) {
                    try {
                        $timer->adiutor->notify(new AbandonedTimerNotification($timer));
                        $notified++;
                    } catch (\Exception $e) {
                        $this->error("Failed to notify {$timer->adiutor->fullName}: {$e->getMessage()}");
                        Log::error('Failed to send abandoned timer notification', [
                            'timer_id' => $timer->id,
                            'adiutor_id' => $timer->adiutor_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    $this->line("  [DRY-RUN] Would notify: {$timer->adiutor->fullName}");
                    $notified++;
                }
            }

            // Auto-stop if requested
            if ($autoStop) {
                if (!$dryRun) {
                    try {
                        // Calculate duration up to max allowed hours
                        $maxMinutes = $hoursThreshold * 60;
                        $actualMinutes = $timer->start_time->diffInMinutes(now());
                        $cappedMinutes = min($actualMinutes, $maxMinutes);

                        $timer->update([
                            'end_time' => $timer->start_time->addMinutes($cappedMinutes),
                            'duration_minutes' => $cappedMinutes,
                            'billable_minutes' => $cappedMinutes,
                            'is_capped' => $actualMinutes > $maxMinutes,
                            'notes' => ($timer->notes ? $timer->notes . "\n\n" : '') . 
                                      "[System] Timer auto-stopped after {$hoursThreshold} hours. " .
                                      "Original duration: " . round($actualMinutes / 60, 2) . " hrs.",
                        ]);

                        // Calculate amount
                        if ($timer->hourly_rate) {
                            $timer->calculated_amount = round(($cappedMinutes / 60) * $timer->hourly_rate, 2);
                            $timer->save();
                        }

                        $stopped++;
                    } catch (\Exception $e) {
                        $this->error("Failed to stop timer #{$timer->id}: {$e->getMessage()}");
                        Log::error('Failed to auto-stop abandoned timer', [
                            'timer_id' => $timer->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    $this->line("  [DRY-RUN] Would auto-stop timer #{$timer->id}");
                    $stopped++;
                }
            }
        }

        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("   • Abandoned timers found: {$abandonedTimers->count()}");
        
        if ($notify) {
            $this->line("   • Notifications " . ($dryRun ? "would be " : "") . "sent: {$notified}");
        }
        
        if ($autoStop) {
            $this->line("   • Timers " . ($dryRun ? "would be " : "") . "auto-stopped: {$stopped}");
        }

        return Command::SUCCESS;
    }
}
