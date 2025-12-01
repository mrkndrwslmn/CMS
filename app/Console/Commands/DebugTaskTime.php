<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskSchedule;

class DebugTaskTime extends Command
{
    protected $signature = 'calendar:debug-time {schedule_id}';
    protected $description = 'Debug task schedule time';

    public function handle()
    {
        $scheduleId = $this->argument('schedule_id');
        $schedule = TaskSchedule::find($scheduleId);
        
        if (!$schedule) {
            $this->error("Schedule {$scheduleId} not found");
            return;
        }

        $this->info("Schedule ID: {$schedule->id}");
        $this->info("Task: {$schedule->task->taskTitle}");
        $this->line("");
        
        $this->info("Database values:");
        $this->line("  scheduled_start: {$schedule->scheduled_start}");
        $this->line("  scheduled_end: {$schedule->scheduled_end}");
        $this->line("");
        
        $start = \Carbon\Carbon::parse($schedule->scheduled_start);
        $end = \Carbon\Carbon::parse($schedule->scheduled_end);
        
        $this->info("Parsed Carbon objects:");
        $this->line("  Start: {$start->toDateTimeString()} ({$start->timezone})");
        $this->line("  End: {$end->toDateTimeString()} ({$end->timezone})");
        $this->line("");
        
        $this->info("RFC3339 format (what Google Calendar receives):");
        $this->line("  Start: {$start->toRfc3339String()}");
        $this->line("  End: {$end->toRfc3339String()}");
        $this->line("");
        
        $this->info("Event times (deadline minus 1 hour to deadline):");
        $eventStart = $end->copy()->subHour();
        $eventEnd = $end->copy();
        $this->line("  Event Start: {$eventStart->toRfc3339String()}");
        $this->line("  Event End: {$eventEnd->toRfc3339String()}");
    }
}
