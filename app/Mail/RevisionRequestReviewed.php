<?php

namespace App\Mail;

use App\Models\RevisionRequest;
use Illuminate\Mail\Mailables\Content;

class RevisionRequestReviewed extends BaseMailable
{
    public function __construct(
        public RevisionRequest $revisionRequest
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        $status = ucfirst($this->revisionRequest->status);
        return "Revision Request {$status} - " . $this->revisionRequest->getSourceDescription();
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.revision-request-reviewed',
            with: [
                'revisionRequest' => $this->revisionRequest,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'support';
    }
}