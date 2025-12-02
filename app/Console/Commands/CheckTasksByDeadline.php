<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskSchedule;
use Carbon\Carbon;

class CheckTasksByDeadline extends Command
{
    protected $signature = 'tasks:check-deadline {deadline}';
    protected $description = 'Check all tasks with a specific deadline';

    public function handle()
    {
        $deadlineInput = $this->argument('deadline');
        $deadlineDate = Carbon::parse($deadlineInput)->format('Y-m-d');
        
        $tasks = Task::with(['assignedUser', 'project'])
            ->whereNotNull('deadline')
            ->whereRaw('DATE(deadline) = ?', [$deadlineDate])
            ->orderBy('assignedTo')
            ->orderBy('taskID')
            ->get();
        
        $this->info("Tasks with deadline on {$deadlineDate}:");
        $this->info("=========================================");
        $this->line("Total: {$tasks->count()} tasks");
        $this->newLine();
        
        $byAdiutor = $tasks->groupBy('assignedTo');
        
        foreach ($byAdiutor as $adiutorId => $adiutorTasks) {
            $adiutorName = $adiutorTasks->first()->assignedUser->fullName ?? 'Unassigned';
            $this->info("Adiutor {$adiutorId}: {$adiutorName}");
            
            if ($adiutorTasks->count() > 1) {
                $this->error("  ⚠ CONFLICT: {$adiutorTasks->count()} tasks with same deadline!");
            }
            
            foreach ($adiutorTasks as $task) {
                $schedule = TaskSchedule::where('task_id', $task->taskID)->first();
                $scheduleStatus = $schedule ? "SCHEDULED ({$schedule->schedule_type})" : "UNSCHEDULED";
                
                $this->line("  Task {$task->taskID}: {$task->taskTitle}");
                $this->line("    Project: " . ($task->project->title ?? 'N/A'));
                $this->line("    Deadline: {$task->deadline}");
                $this->line("    Created: {$task->created_at}");
                $this->line("    Status: {$scheduleStatus}");
                
                if ($adiutorTasks->count() > 1) {
                    if ($task->taskID == $adiutorTasks->min('taskID')) {
                        $this->line("    → OLDEST task - should be scheduled");
                        if (!$schedule) {
                            $this->error("    ❌ BUG: Should be scheduled!");
                        }
                    } else {
                        $this->line("    → NEWER task - should be unscheduled");
                        if ($schedule) {
                            $this->error("    ❌ BUG: Should NOT be scheduled!");
                        }
                    }
                }
                $this->newLine();
            }
        }
        
        return 0;
    }
}
