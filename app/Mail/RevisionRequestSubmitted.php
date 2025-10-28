<?php

namespace App\Mail;

use App\Models\RevisionRequest;
use Illuminate\Mail\Mailables\Content;

class RevisionRequestSubmitted extends BaseMailable
{
    public function __construct(
        public RevisionRequest $revisionRequest
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        return 'New Revision Request - ' . $this->revisionRequest->getSourceDescription();
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.revision-request-submitted',
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