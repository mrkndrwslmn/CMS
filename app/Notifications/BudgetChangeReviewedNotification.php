<?php

namespace App\Notifications;

use App\Mail\BudgetChangeReviewed;
use App\Models\BudgetChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetChangeReviewedNotification extends Notification
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
    public function toMail($notifiable): BudgetChangeReviewed
    {
        return (new BudgetChangeReviewed($this->budgetRequest))
            ->onQueue('emails');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $task = $this->budgetRequest->task;
        $status = $this->budgetRequest->status;
        
        $message = $status === 'approved'
            ? 'Your budget change request for task "' . $task->taskTitle . '" has been approved. New budget: ₱' . number_format($this->budgetRequest->requested_budget, 2)
            : 'Your budget change request for task "' . $task->taskTitle . '" has been rejected. Reason: ' . $this->budgetRequest->review_notes;

        return [
            'title' => $status === 'approved' ? 'Budget Change Request Approved' : 'Budget Change Request Rejected',
            'message' => $message,
            'action_url' => route('adiutor.tasks.show', $task->taskID),
            'task_id' => $task->taskID,
            'task_title' => $task->taskTitle,
            'status' => $status,
            'new_budget' => $this->budgetRequest->requested_budget,
        ];
    }
}
