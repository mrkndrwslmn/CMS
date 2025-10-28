<?php

namespace App\Mail;

use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

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
