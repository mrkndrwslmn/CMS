<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class ProjectAssigned extends BaseMailable
{
    public function __construct(
        public Project $project,
        public User $adiutor,
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
        if ($this->adiutor && $this->adiutor->email) {
            $envelope->to(
                new Address($this->adiutor->email, $this->adiutor->name ?? '')
            );
        }

        return $envelope;
    }

    protected function getSubject(): string
    {
        return 'New Project Assignment - ' . $this->project->title;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-assigned',
            with: [
                'project' => $this->project,
                'adiutor' => $this->adiutor,
                'assignedBy' => $this->assignedBy,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'projects';
    }
}