<?php

namespace App\Mail;

use App\Models\RevisionRequest;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class RevisionRequestSubmitted extends BaseMailable
{
    public function __construct(
        public RevisionRequest $revisionRequest,
        public ?User $recipient = null
    ) {
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
        if ($this->recipient && $this->recipient->email) {
            $envelope->to(
                new Address($this->recipient->email, $this->recipient->name ?? '')
            );
        }

        return $envelope;
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
        return 'projects';
    }
}