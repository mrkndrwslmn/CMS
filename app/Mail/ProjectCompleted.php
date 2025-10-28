<?php

namespace App\Mail;

use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class ProjectCompleted extends BaseMailable
{
    public $project;
    public $client;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_PROJECTS;

    /**
     * Create a new message instance.
     */
    public function __construct($project = null, $client = null)
    {
        $this->project = $project;
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
                new Address($this->client->email, $this->client->name ?? '')
            );
        }

        return $envelope;
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        $projectName = $this->project ? $this->project->name : 'Your Project';
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
                'project' => $this->project,
                'client' => $this->client,
            ]
        );
    }
}
