<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\TaskSchedule;

class ListRecentTasks extends Command
{
    protected $signature = 'tasks:recent {limit=20}';
    protected $description = 'List recent tasks';

    public function handle()
    {
        $limit = $this->argument('limit');
        
        $tasks = Task::with(['assignedUser', 'project'])
            ->orderByDesc('taskID')
            ->limit($limit)
            ->get();
        
        $this->info("Last {$limit} tasks:");
        $this->info("==================");
        
        foreach ($tasks as $task) {
            $adiutor = $task->assignedUser ? $task->assignedUser->fullName : 'Unassigned';
            $project = $task->project ? $task->project->title : 'N/A';
            $deadline = $task->deadline ?? 'No deadline';
            
            $schedule = TaskSchedule::where('task_id', $task->taskID)->first();
            $scheduleStatus = $schedule ? "SCHEDULED ({$schedule->schedule_type})" : "UNSCHEDULED";
            
            $this->line("Task {$task->taskID}: {$task->taskTitle}");
            $this->line("  Project: {$project}");
            $this->line("  Assigned to: {$adiutor} (ID: {$task->assignedTo})");
            $this->line("  Deadline: {$deadline}");
            $this->line("  Status: {$scheduleStatus}");
            $this->line("  Created: {$task->created_at}");
            $this->newLine();
        }
        
        return 0;
    }
}
