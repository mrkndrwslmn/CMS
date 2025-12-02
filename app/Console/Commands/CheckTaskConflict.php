<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskSchedule;
use Carbon\Carbon;

class CheckTaskConflict extends Command
{
    protected $signature = 'task:check-conflict {task_id}';
    protected $description = 'Check if a specific task has deadline conflicts';

    public function handle()
    {
        $taskId = $this->argument('task_id');
        
        $task = Task::with(['assignedUser', 'project'])->find($taskId);
        
        if (!$task) {
            $this->error("Task {$taskId} not found!");
            return 1;
        }
        
        $this->info("Task Details:");
        $this->info("=============");
        $this->line("Task ID: {$task->taskID}");
        $this->line("Title: {$task->taskTitle}");
        $this->line("Project: " . ($task->project->title ?? 'N/A'));
        $this->line("Assigned to: " . ($task->assignedUser->fullName ?? 'Unassigned') . " (ID: {$task->assignedTo})");
        $this->line("Deadline: " . ($task->deadline ?? 'No deadline'));
        $this->line("Created: {$task->created_at}");
        
        $schedule = TaskSchedule::where('task_id', $task->taskID)->first();
        if ($schedule) {
            $this->info("\n✓ SCHEDULED ({$schedule->schedule_type})");
            $this->line("  Schedule ID: {$schedule->id}");
            $this->line("  Start: {$schedule->scheduled_start}");
            $this->line("  End: {$schedule->scheduled_end}");
        } else {
            $this->warn("\n✗ UNSCHEDULED");
        }
        
        if (!$task->deadline || !$task->assignedTo) {
            $this->info("\nNo deadline or assignee - cannot check for conflicts");
            return 0;
        }
        
        // Check for conflicts
        $this->info("\n\nConflict Analysis:");
        $this->info("==================");
        
        $deadlineDate = Carbon::parse($task->deadline)->format('Y-m-d');
        
        $conflictingTasks = Task::where('assignedTo', $task->assignedTo)
            ->whereNotNull('deadline')
            ->whereRaw('DATE(deadline) = ?', [$deadlineDate])
            ->where('taskID', '!=', $task->taskID)
            ->orderBy('taskID')
            ->get();
        
        if ($conflictingTasks->isEmpty()) {
            $this->info("✓ No deadline conflicts found");
            return 0;
        }
        
        $this->warn("⚠ Found {$conflictingTasks->count()} conflicting task(s) with same deadline:");
        $this->newLine();
        
        foreach ($conflictingTasks as $conflictTask) {
            $conflictSchedule = TaskSchedule::where('task_id', $conflictTask->taskID)->first();
            $scheduleStatus = $conflictSchedule ? "SCHEDULED ({$conflictSchedule->schedule_type})" : "UNSCHEDULED";
            
            $this->line("Task {$conflictTask->taskID}: {$conflictTask->taskTitle}");
            $this->line("  Project: " . ($conflictTask->project->title ?? 'N/A'));
            $this->line("  Deadline: {$conflictTask->deadline}");
            $this->line("  Created: {$conflictTask->created_at}");
            $this->line("  Status: {$scheduleStatus}");
            
            if ($conflictTask->taskID < $task->taskID) {
                $this->line("  → This is OLDER (created first) - should stay scheduled");
            } else {
                $this->line("  → This is NEWER - should be unscheduled");
            }
            $this->newLine();
        }
        
        // Determine expected behavior
        $this->info("Expected Behavior:");
        $this->info("==================");
        
        $olderTasks = $conflictingTasks->filter(fn($t) => $t->taskID < $task->taskID);
        
        if ($olderTasks->isNotEmpty()) {
            $this->error("Task {$task->taskID} is NEWER - should be UNSCHEDULED");
            if ($schedule) {
                $this->error("❌ BUG: Task is currently scheduled but should be unscheduled!");
            } else {
                $this->info("✓ Correct: Task is unscheduled");
            }
        } else {
            $this->info("Task {$task->taskID} is OLDEST - should be SCHEDULED");
            if ($schedule) {
                $this->info("✓ Correct: Task is scheduled");
            } else {
                $this->warn("⚠ Task should be auto-scheduled");
            }
        }
        
        return 0;
    }
}
