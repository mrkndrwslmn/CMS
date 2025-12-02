<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskSchedule;
use Carbon\Carbon;

class CheckScheduleConflicts extends Command
{
    protected $signature = 'schedule:check-conflicts {project_id}';
    protected $description = 'Check for scheduling conflicts in a project';

    public function handle()
    {
        $projectId = $this->argument('project_id');
        
        $this->info("Checking schedules for Project ID: {$projectId}");
        $this->info("========================================");
        
        // Get all tasks for this project with deadlines
        $tasks = Task::where('project_id', $projectId)
            ->whereNotNull('deadline')
            ->whereNotNull('assignedTo')
            ->orderBy('taskID')
            ->get();
        
        $this->info("Total tasks with deadlines: " . $tasks->count());
        $this->newLine();
        
        foreach ($tasks as $task) {
            $this->info("Task {$task->taskID}: {$task->taskTitle}");
            $this->line("  Assigned to: {$task->assignedTo}");
            $this->line("  Deadline: {$task->deadline}");
            
            $schedule = TaskSchedule::where('task_id', $task->taskID)->first();
            if ($schedule) {
                $this->line("  Schedule: {$schedule->schedule_type} (ID: {$schedule->id})");
                $this->line("  Scheduled: {$schedule->scheduled_start} to {$schedule->scheduled_end}");
            } else {
                $this->warn("  NOT SCHEDULED");
            }
            $this->newLine();
        }
        
        // Check for deadline conflicts
        $this->info("Deadline Conflict Analysis:");
        $this->info("===========================");
        
        $conflictGroups = $tasks->groupBy(function($task) {
            return $task->assignedTo . '_' . Carbon::parse($task->deadline)->format('Y-m-d');
        });
        
        foreach ($conflictGroups as $key => $group) {
            if ($group->count() > 1) {
                list($adiutorId, $deadline) = explode('_', $key);
                $this->error("CONFLICT: Adiutor {$adiutorId} has {$group->count()} tasks on {$deadline}");
                
                $sorted = $group->sortByDesc('taskID');
                foreach ($sorted as $index => $task) {
                    $schedule = TaskSchedule::where('task_id', $task->taskID)->first();
                    $status = $schedule ? "SCHEDULED ({$schedule->schedule_type})" : "UNSCHEDULED";
                    $position = $index < count($sorted) - 1 ? "NEWER (should be unscheduled)" : "OLDER (should be scheduled)";
                    $this->line("  Task {$task->taskID}: {$position} - {$status}");
                }
                $this->newLine();
            }
        }
        
        return 0;
    }
}
