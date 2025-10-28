<?php

namespace App\Mail;

use App\Models\BudgetChangeRequest;
use Illuminate\Mail\Mailables\Content;

class BudgetChangeRequested extends BaseMailable
{
    public function __construct(
        public BudgetChangeRequest $budgetRequest
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        return 'Budget Change Request - ' . $this->budgetRequest->task->taskTitle;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.budget-change-requested',
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