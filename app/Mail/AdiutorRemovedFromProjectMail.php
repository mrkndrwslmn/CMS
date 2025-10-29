<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AdiutorRemovedFromProjectMail extends BaseMailable
{
    protected string $senderType = 'admin';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $projectTitle,
        public string $clientName,
        public string $reason = ''
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email.
     */
    protected function getSubject(): string
    {
        return "Removed from Project: {$this->projectTitle}";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.adiutor-removed-from-project',
            with: [
                'projectTitle' => $this->projectTitle,
                'clientName' => $this->clientName,
                'reason' => $this->reason,
                'dashboardUrl' => route('adiutor.dashboard'),
                'supportEmail' => config('mail.support_email', 'support@treisadiutor.com')
            ]
        );
    }
}