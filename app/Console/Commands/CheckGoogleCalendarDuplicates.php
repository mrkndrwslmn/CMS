<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskSchedule;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class CheckGoogleCalendarDuplicates extends Command
{
    protected $signature = 'calendar:check-gcal-duplicates';
    protected $description = 'Check for tasks with multiple Google Calendar events';

    public function handle()
    {
        // Find task_ids with multiple Google Calendar event IDs
        $duplicates = DB::table('task_schedules')
            ->select('task_id', DB::raw('COUNT(*) as event_count'))
            ->whereNotNull('google_calendar_event_id')
            ->groupBy('task_id')
            ->having('event_count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No tasks with multiple Google Calendar events found.');
            
            // Show all tasks with Google Calendar events
            $this->info("\nAll tasks with Google Calendar events:");
            $schedules = TaskSchedule::whereNotNull('google_calendar_event_id')
                ->with('task')
                ->get();
            
            foreach ($schedules as $schedule) {
                $this->line("Task ID {$schedule->task_id}: {$schedule->task->task_name}");
                $this->line("  Schedule ID: {$schedule->id}");
                $this->line("  Event ID: {$schedule->google_calendar_event_id}");
                $this->line("  Date: {$schedule->scheduled_start} to {$schedule->scheduled_end}");
                $this->line("");
            }
            
            return;
        }

        $this->error('Tasks with multiple Google Calendar events:');
        foreach ($duplicates as $dup) {
            $task = Task::find($dup->task_id);
            $this->error("\nTask ID {$dup->task_id}: {$task->task_name} ({$dup->event_count} events)");
            
            $schedules = TaskSchedule::where('task_id', $dup->task_id)
                ->whereNotNull('google_calendar_event_id')
                ->get();
            
            foreach ($schedules as $schedule) {
                $this->line("  Schedule ID: {$schedule->id}");
                $this->line("  Event ID: {$schedule->google_calendar_event_id}");
                $this->line("  Type: {$schedule->schedule_type}");
                $this->line("  Date: {$schedule->scheduled_start} to {$schedule->scheduled_end}");
                $this->line("");
            }
        }
    }
}
