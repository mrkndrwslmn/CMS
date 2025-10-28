<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;

class TaskCompleted extends BaseMailable
{
    public function __construct(
        public Task $task,
        public User $completedBy
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        return 'Task Completed - ' . $this->task->taskTitle;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task-completed',
            with: [
                'task' => $this->task,
                'completedBy' => $this->completedBy,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'projects';
    }
}