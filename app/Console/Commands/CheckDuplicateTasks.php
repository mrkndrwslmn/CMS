<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaskSchedule;
use App\Models\Task;

class CheckDuplicateTasks extends Command
{
    protected $signature = 'calendar:check-duplicates {task_id}';
    protected $description = 'Check if a task appears multiple times on timeline';

    public function handle()
    {
        $taskId = $this->argument('task_id');
        $task = Task::find($taskId);
        
        if (!$task) {
            $this->error("Task {$taskId} not found");
            return;
        }
        
        $this->info("Task: {$task->taskTitle}");
        $this->info("Deadline: " . ($task->deadline ?? 'None'));
        $this->info("Assigned to: " . ($task->assignedTo ?? 'Unassigned'));
        
        $schedules = TaskSchedule::where('task_id', $taskId)->get();
        $this->info("\nSchedules in database: " . $schedules->count());
        
        foreach ($schedules as $schedule) {
            $this->line("  - Schedule #{$schedule->id}");
            $this->line("    Start: {$schedule->scheduled_start}");
            $this->line("    End: {$schedule->scheduled_end}");
            $this->line("    Type: {$schedule->schedule_type}");
            $this->line("    Adiutor: {$schedule->adiutor_id}");
        }
    }
}
