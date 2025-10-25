<?php

namespace App\Mail;

use App\Models\RevisionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RevisionRejected extends Mailable implements ShouldQueue
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
            subject: 'Revision Request Not Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.revision-rejected',
            with: [
                'revision' => $this->revisionRequest,
                'document' => $this->revisionRequest->document,
                'sourceDescription' => $this->revisionRequest->getSourceDescription(),
                'adminNotes' => $this->revisionRequest->admin_notes,
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
