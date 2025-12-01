<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskSchedule;

class RemoveDuplicateSchedules extends Command
{
    protected $signature = 'calendar:remove-duplicates';
    protected $description = 'Remove duplicate task schedules (keep the most recent one)';

    public function handle()
    {
        $this->info('Checking for duplicate schedules...');

        $schedules = TaskSchedule::with('task')->get();
        $grouped = $schedules->groupBy('task_id');
        
        $duplicatesFound = 0;
        $duplicatesRemoved = 0;

        foreach ($grouped as $taskId => $group) {
            if ($group->count() > 1) {
                $task = $group->first()->task;
                $taskName = $task ? $task->taskTitle : "Task ID {$taskId}";
                
                $this->warn("Found {$group->count()} schedules for: {$taskName}");
                $duplicatesFound++;
                
                // Keep the most recent schedule (latest updated_at)
                $keepSchedule = $group->sortByDesc('updated_at')->first();
                $removeSchedules = $group->reject(function($schedule) use ($keepSchedule) {
                    return $schedule->id === $keepSchedule->id;
                });
                
                foreach ($removeSchedules as $schedule) {
                    $this->line("  Removing schedule #{$schedule->id} (created: {$schedule->created_at})");
                    $schedule->delete();
                    $duplicatesRemoved++;
                }
                
                $this->info("  Kept schedule #{$keepSchedule->id} (updated: {$keepSchedule->updated_at})");
            }
        }

        if ($duplicatesFound === 0) {
            $this->info('No duplicate schedules found!');
        } else {
            $this->info("\nDone! Found {$duplicatesFound} tasks with duplicates, removed {$duplicatesRemoved} duplicate schedules.");
        }
    }
}
