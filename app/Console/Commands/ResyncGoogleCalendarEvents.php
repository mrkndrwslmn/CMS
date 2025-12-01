<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskSchedule;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;

class ResyncGoogleCalendarEvents extends Command
{
    protected $signature = 'calendar:resync';
    protected $description = 'Re-sync all existing Google Calendar events with updated deadline format';

    public function handle()
    {
        $schedules = TaskSchedule::whereNotNull('google_calendar_event_id')
            ->with(['task.project', 'adiutor.calendarIntegration'])
            ->get();

        $this->info("Found {$schedules->count()} schedules with Google Calendar events");

        $updated = 0;
        $failed = 0;

        foreach ($schedules as $schedule) {
            if (!$schedule->adiutor || !$schedule->task) {
                $this->warn("Skipping schedule #{$schedule->id}: Missing adiutor or task");
                continue;
            }

            if (!$schedule->adiutor->calendarIntegration || !$schedule->adiutor->calendarIntegration->is_connected) {
                $this->warn("Skipping {$schedule->task->taskTitle}: Calendar not connected");
                continue;
            }

            try {
                $calendarService = app(GoogleCalendarService::class);
                $calendarService->updateTaskEvent($schedule->adiutor, $schedule->google_calendar_event_id, [
                    'title' => $schedule->task->taskTitle,
                    'description' => $schedule->task->taskDescription,
                    'project' => $schedule->task->project->title ?? 'N/A',
                    'priority' => $schedule->task->priority ?? 'medium',
                    'start' => Carbon::parse($schedule->scheduled_start),
                    'end' => Carbon::parse($schedule->scheduled_end),
                    'url' => route('adiutor.tasks.show', $schedule->task->taskID)
                ]);

                $this->info("✓ Updated: {$schedule->task->taskTitle}");
                $updated++;
            } catch (\Exception $e) {
                $this->error("✗ Failed: {$schedule->task->taskTitle} - {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("\nDone! Updated: {$updated}, Failed: {$failed}");
    }
}
