<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\Project;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ProjectCancelledMail extends BaseMailable
{
    protected string $senderType = 'admin';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Project $project,
        public string $reason = ''
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email.
     */
    protected function getSubject(): string
    {
        return "Project Cancelled: {$this->project->title}";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.project-cancelled',
            with: [
                'project' => $this->project,
                'reason' => $this->reason,
                'supportEmail' => config('mail.support_email', 'support@treisadiutor.com'),
                'dashboardUrl' => route('client.dashboard')
            ]
        );
    }
}