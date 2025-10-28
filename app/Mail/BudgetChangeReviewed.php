<?php

namespace App\Mail;

use App\Models\BudgetChangeRequest;
use Illuminate\Mail\Mailables\Content;

class BudgetChangeReviewed extends BaseMailable
{
    public function __construct(
        public BudgetChangeRequest $budgetRequest
    ) {
        parent::__construct();
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
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'billing';
    }
}