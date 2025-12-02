<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskSchedule;
use Carbon\Carbon;

class CheckAdiutorConflicts extends Command
{
    protected $signature = 'adiutor:check-conflicts {adiutor_id}';
    protected $description = 'Check for deadline conflicts for an adiutor';

    public function handle()
    {
        $adiutorId = $this->argument('adiutor_id');
        
        $tasks = Task::with(['project'])
            ->where('assignedTo', $adiutorId)
            ->whereNotNull('deadline')
            ->orderBy('deadline')
            ->orderBy('taskID')
            ->get();
        
        $this->info("Tasks assigned to Adiutor {$adiutorId}:");
        $this->info("========================================");
        $this->line("Total tasks with deadlines: {$tasks->count()}");
        $this->newLine();
        
        // Group by deadline date
        $byDeadline = $tasks->groupBy(fn($t) => Carbon::parse($t->deadline)->format('Y-m-d'));
        
        foreach ($byDeadline as $deadline => $group) {
            if ($group->count() > 1) {
                $this->error("⚠ CONFLICT: {$group->count()} tasks on {$deadline}");
            } else {
                $this->info("✓ {$deadline}: 1 task");
            }
            
            foreach ($group as $task) {
                $schedule = TaskSchedule::where('task_id', $task->taskID)->first();
                $scheduleStatus = $schedule ? "SCHEDULED ({$schedule->schedule_type})" : "UNSCHEDULED";
                
                $this->line("  Task {$task->taskID}: {$task->taskTitle}");
                $this->line("    Project: " . ($task->project->title ?? 'N/A'));
                $this->line("    Created: {$task->created_at}");
                $this->line("    Status: {$scheduleStatus}");
                
                if ($group->count() > 1) {
                    $sorted = $group->sortByDesc('taskID')->values();
                    $position = $sorted->search(fn($t) => $t->taskID === $task->taskID);
                    if ($position < $sorted->count() - 1) {
                        $this->line("    → NEWER task - SHOULD BE UNSCHEDULED");
                        if ($schedule) {
                            $this->error("    ❌ BUG: Currently scheduled!");
                        }
                    } else {
                        $this->line("    → OLDEST task - should stay scheduled");
                        if (!$schedule) {
                            $this->warn("    ⚠ Should be auto-scheduled");
                        }
                    }
                }
            }
            $this->newLine();
        }
        
        return 0;
    }
}
