<?php

namespace App\Mail;

use App\Models\Payout;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class PayoutPaidMail extends BaseMailable
{
    public function __construct(
        public Payout $payout,
        public User $adiutor,
        public User $processedBy
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
        return 'Payment Completed - ' . $this->payout->payout_number;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payout-paid',
            with: [
                'payout' => $this->payout,
                'adiutor' => $this->adiutor,
                'processedBy' => $this->processedBy,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'finance';
    }
}
