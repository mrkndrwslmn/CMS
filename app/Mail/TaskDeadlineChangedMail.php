<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\Task;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TaskDeadlineChangedMail extends BaseMailable
{
    protected string $senderType = 'admin';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Task $task,
        public string $oldDeadline,
        public string $newDeadline
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email.
     */
    protected function getSubject(): string
    {
        return "Task Deadline Updated: {$this->task->taskTitle}";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-deadline-changed',
            with: [
                'task' => $this->task,
                'oldDeadline' => $this->oldDeadline,
                'newDeadline' => $this->newDeadline,
                'taskUrl' => route('adiutor.tasks.show', $this->task->taskID)
            ]
        );
    }
}