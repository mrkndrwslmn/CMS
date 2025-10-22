<?php

namespace App\Mail;

use App\Models\RevisionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RevisionApproved extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $revisionRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(RevisionRequest $revisionRequest)
    {
        $this->revisionRequest = $revisionRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Revision Request Approved - Action Required',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.revision-approved',
            with: [
                'revision' => $this->revisionRequest,
                'document' => $this->revisionRequest->document,
                'client' => $this->revisionRequest->requestedBy,
                'sourceDescription' => $this->revisionRequest->getSourceDescription(),
                'task' => $this->revisionRequest->task,
                'project' => $this->revisionRequest->project,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
