<?php

namespace App\Mail;

use App\Models\BudgetChangeRequest;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class BudgetChangeReviewed extends BaseMailable
{
    public function __construct(
        public BudgetChangeRequest $budgetRequest,
        public $adiutor = null
    ) {
        parent::__construct();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = $this->senderService->getSender($this->getSenderType());
        
        $envelope = new Envelope(
            from: new Address($sender['address'], $sender['name']),
            subject: $this->getSubject(),
        );

        // Set the recipient email if adiutor is available
        if ($this->adiutor && $this->adiutor->email) {
            $envelope->to(
                new Address($this->adiutor->email, $this->adiutor->fullName ?? $this->adiutor->name ?? '')
            );
        }

        return $envelope;
    }

    protected function getSubject(): string
    {
        $status = ucfirst($this->budgetRequest->status);
        return "Budget Change Request {$status} - " . $this->budgetRequest->task->taskTitle;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.budget-change-reviewed',
            with: [
                'budgetRequest' => $this->budgetRequest,
                'reviewedBy' => $this->budgetRequest->reviewer,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'billing';
    }
}