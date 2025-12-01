<?php

namespace App\Mail;

use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class ProjectCompleted extends BaseMailable
{
    public $projectId;
    public $projectTitle;
    public $client;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_PROJECTS;

    /**
     * Create a new message instance.
     */
    public function __construct($projectId = null, $projectTitle = null, $client = null)
    {
        $this->projectId = $projectId;
        $this->projectTitle = $projectTitle;
        $this->client = $client;
        
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
        if ($this->client && $this->client->email) {
            $envelope->to(
                new Address($this->client->email, $this->client->fullName ?? $this->client->name ?? '')
            );
        }

        return $envelope;
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        $projectName = $this->projectTitle ?: 'Your Project';
        return "Project Completed - {$projectName}";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.project-completed',
            with: [
                'projectId' => $this->projectId,
                'projectTitle' => $this->projectTitle,
                'client' => $this->client,
            ]
        );
    }
}
