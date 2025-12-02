<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;

class ShowTaskDetails extends Command
{
    protected $signature = 'task:show {task_id}';
    protected $description = 'Show task details';

    public function handle()
    {
        $taskId = $this->argument('task_id');
        $task = Task::find($taskId);
        
        if (!$task) {
            $this->error("Task {$taskId} not found!");
            return 1;
        }
        
        $this->info("Task {$task->taskID}: {$task->taskTitle}");
        $this->line("Assigned to: {$task->assignedTo}");
        $this->line("Deadline: {$task->deadline}");
        $this->line("Created: {$task->created_at}");
        $this->line("Updated: {$task->updated_at}");
        
        return 0;
    }
}
