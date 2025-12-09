<?php

namespace App\Notifications;

use App\Mail\BudgetChangeRequested;
use App\Models\BudgetChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetChangeRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected BudgetChangeRequest $budgetRequest
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): BudgetChangeRequested
    {
        return (new BudgetChangeRequested($this->budgetRequest))
            ->onQueue('emails');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Budget Change Request',
            'message' => $this->budgetRequest->adiutor->fullName . ' has requested a budget change for task: ' . $this->budgetRequest->task->taskTitle,
            'action_url' => route('admin.budget-requests.index'),
            'task_id' => $this->budgetRequest->task_id,
            'task_title' => $this->budgetRequest->task->taskTitle,
            'current_budget' => $this->budgetRequest->current_budget,
            'requested_budget' => $this->budgetRequest->requested_budget,
        ];
    }
}
