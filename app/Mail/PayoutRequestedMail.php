<?php

namespace App\Mail;

use App\Models\Payout;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class PayoutRequestedMail extends BaseMailable
{
    public function __construct(
        public Payout $payout,
        public User $adiutor,
        public User $admin
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

        // Set the recipient email (admin)
        if ($this->admin && $this->admin->email) {
            $envelope->to(
                new Address($this->admin->email, $this->admin->fullName ?? '')
            );
        }

        return $envelope;
    }

    protected function getSubject(): string
    {
        return 'New Payout Request - ' . $this->payout->payout_number;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payout-requested',
            with: [
                'payout' => $this->payout,
                'adiutor' => $this->adiutor,
                'admin' => $this->admin,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'finance';
    }
}
