<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class TaskCompleted extends BaseMailable
{
    public function __construct(
        public Task $task,
        public User $completedBy,
        public ?User $recipient = null
    ) {
        parent::__construct();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = $this->senderService->getSender($this->senderType);
        
        $envelope = new Envelope(
            from: new Address($sender['address'], $sender['name']),
            subject: $this->getSubject(),
        );

        // Set the recipient email if available
        if ($this->recipient && $this->recipient->email) {
            $envelope->to(
                new Address($this->recipient->email, $this->recipient->name ?? '')
            );
        }

        return $envelope;
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