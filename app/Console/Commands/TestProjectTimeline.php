<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\TaskSchedule;
use App\Models\Task;
use Carbon\Carbon;

class TestProjectTimeline extends Command
{
    protected $signature = 'test:project-timeline {project_id}';
    protected $description = 'Test project timeline endpoint data';

    public function handle()
    {
        $id = $this->argument('project_id');
        
        try {
            $project = Project::findOrFail($id);
            $this->info("Project: {$project->projectName}");
            $this->line("");
            
            $weekStart = Carbon::now()->startOfWeek();
            $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();
            
            $this->info("Date Range: {$weekStart->format('Y-m-d')} to {$weekEnd->format('Y-m-d')}");
            $this->line("");
            
            // 1. Scheduled tasks
            $scheduledTasks = TaskSchedule::whereBetween('scheduled_end', [$weekStart, $weekEnd])
                ->with(['task', 'adiutor'])
                ->whereHas('task', function($query) use ($id) {
                    $query->where('project_id', $id);
                })
                ->get();
            
            $this->info("Scheduled Tasks: {$scheduledTasks->count()}");
            foreach ($scheduledTasks as $schedule) {
                $this->line("  - {$schedule->task->taskTitle} (Adiutor: {$schedule->adiutor->fullName})");
                $this->line("    Deadline: {$schedule->scheduled_end}");
            }
            $this->line("");
            
            // 2. Assigned tasks with deadlines
            $assignedTasks = Task::where('project_id', $id)
                ->whereNotNull('deadline')
                ->whereNotNull('assignedTo')
                ->whereBetween('deadline', [$weekStart, $weekEnd])
                ->where('status', '!=', 'completed')
                ->with('assignedUser')
                ->get();
            
            $this->info("Assigned Tasks: {$assignedTasks->count()}");
            foreach ($assignedTasks as $task) {
                $adiutorName = $task->assignedUser ? $task->assignedUser->fullName : 'Unknown';
                $this->line("  - {$task->taskTitle} (Adiutor: {$adiutorName})");
                $this->line("    Deadline: {$task->deadline}");
            }
            $this->line("");
            
            // Check for duplicates
            $scheduledTaskIds = $scheduledTasks->pluck('task_id')->toArray();
            $duplicates = $assignedTasks->filter(function($task) use ($scheduledTaskIds) {
                return in_array($task->taskID, $scheduledTaskIds);
            });
            
            if ($duplicates->count() > 0) {
                $this->warn("Tasks that appear in both (will be filtered):");
                foreach ($duplicates as $task) {
                    $this->line("  - {$task->taskTitle}");
                }
                $this->line("");
            }
            
            $finalCount = $scheduledTasks->count() + ($assignedTasks->count() - $duplicates->count());
            $this->info("Total Tasks to Display: {$finalCount}");
            
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
        }
    }
}
