<?php

namespace App\Mail;

use App\Models\Payout;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class PayoutApprovedMail extends BaseMailable
{
    public function __construct(
        public Payout $payout,
        public User $adiutor,
        public User $approvedBy
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

        // Set the recipient email (adiutor)
        if ($this->adiutor && $this->adiutor->email) {
            $envelope->to(
                new Address($this->adiutor->email, $this->adiutor->fullName ?? '')
            );
        }

        return $envelope;
    }

    protected function getSubject(): string
    {
        return 'Payout Approved - ' . $this->payout->payout_number;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payout-approved',
            with: [
                'payout' => $this->payout,
                'adiutor' => $this->adiutor,
                'approvedBy' => $this->approvedBy,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'finance';
    }
}
