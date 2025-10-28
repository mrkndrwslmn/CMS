<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class TaskAssigned extends BaseMailable
{
    public function __construct(
        public Task $task,
        public User $assignee,
        public User $assignedBy
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
        if ($this->assignee && $this->assignee->email) {
            $envelope->to(
                new Address($this->assignee->email, $this->assignee->name ?? '')
            );
        }

        return $envelope;
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