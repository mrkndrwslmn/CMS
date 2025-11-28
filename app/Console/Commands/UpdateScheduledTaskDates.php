<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateScheduledTaskDates extends Command
{
    protected $signature = 'schedule:update-dates';
    protected $description = 'Update scheduled task dates to match task deadlines';

    public function handle()
    {
        $this->info('Updating scheduled task dates to match deadlines...');
        
        $schedules = DB::table('task_schedules')
            ->join('tasks', 'task_schedules.task_id', '=', 'tasks.taskID')
            ->select(
                'task_schedules.id',
                'task_schedules.task_id',
                'task_schedules.scheduled_start',
                'task_schedules.scheduled_end',
                'task_schedules.estimated_duration_minutes',
                'tasks.deadline',
                'tasks.taskTitle'
            )
            ->whereNotNull('tasks.deadline')
            ->get();
        
        $updated = 0;
        
        foreach ($schedules as $schedule) {
            $deadline = Carbon::parse($schedule->deadline);
            
            // Set end time to deadline at 5 PM if no time specified
            if ($deadline->hour === 0 && $deadline->minute === 0) {
                $deadline->setTime(17, 0, 0);
            }
            
            // Calculate start time based on estimated duration (max 8 hours per day)
            $durationHours = $schedule->estimated_duration_minutes / 60;
            
            // Cap at 8 hours per day to keep task on deadline date
            if ($durationHours > 8) {
                $durationHours = 8;
            }
            
            $startTime = $deadline->copy()->setTime(9, 0, 0);
            
            // If duration is less than 8 hours, calculate actual start time
            if ($durationHours < 8) {
                $startTime = $deadline->copy()->subHours($durationHours);
                // If start time is before 9 AM, adjust to 9 AM on same day
                if ($startTime->hour < 9) {
                    $startTime->setTime(9, 0, 0);
                }
            }
            
            // Update the schedule
            DB::table('task_schedules')
                ->where('id', $schedule->id)
                ->update([
                    'scheduled_start' => $startTime,
                    'scheduled_end' => $deadline,
                    'updated_at' => now()
                ]);
            
            $this->line("✓ Updated: {$schedule->taskTitle}");
            $this->line("  From: {$schedule->scheduled_start} → {$schedule->scheduled_end}");
            $this->line("  To:   {$startTime} → {$deadline}");
            $this->line('');
            
            $updated++;
        }
        
        $this->info("Updated {$updated} scheduled tasks!");
        
        return 0;
    }
}
