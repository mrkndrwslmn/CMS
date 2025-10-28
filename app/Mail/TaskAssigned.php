<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;

class TaskAssigned extends BaseMailable
{
    public function __construct(
        public Task $task,
        public User $assignee,
        public User $assignedBy
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        return 'New Task Assigned - ' . $this->task->taskTitle;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.task-assigned',
            with: [
                'task' => $this->task,
                'assignee' => $this->assignee,
                'assignedBy' => $this->assignedBy,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'projects';
    }
}