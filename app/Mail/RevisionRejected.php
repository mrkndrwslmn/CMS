<?php

namespace App\Mail;

use App\Models\RevisionRequest;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class RevisionRejected extends BaseMailable
{
    public $revisionRequest;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_PROJECTS;

    /**
     * Create a new message instance.
     */
    public function __construct(RevisionRequest $revisionRequest)
    {
        $this->revisionRequest = $revisionRequest;
        
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return 'Revision Request Not Approved';
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
}
