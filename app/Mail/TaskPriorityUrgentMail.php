<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\Task;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TaskPriorityUrgentMail extends BaseMailable
{
    protected string $senderType = 'admin';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Task $task
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email.
     */
    protected function getSubject(): string
    {
        return "URGENT: Task Priority Updated - {$this->task->taskTitle}";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-priority-urgent',
            with: [
                'task' => $this->task,
                'taskUrl' => route('adiutor.tasks.show', $this->task->taskID),
                'projectTitle' => $this->task->project->title ?? 'Unknown Project'
            ]
        );
    }
}