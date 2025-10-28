<?php

namespace App\Notifications;

use App\Mail\BudgetChangeReviewed;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetChangeReviewedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected $task,
        protected $status,
        protected $newBudget = null,
        protected $reason = null
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
        return (new BudgetChangeReviewed($this->task, $this->status, $this->newBudget, $this->reason, $notifiable))
            ->onQueue('emails');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $message = $this->status === 'approved'
            ? 'Your budget change request for task "' . $this->task->taskTitle . '" has been approved. New budget: ₱' . number_format($this->newBudget, 2)
            : 'Your budget change request for task "' . $this->task->taskTitle . '" has been rejected. Reason: ' . $this->reason;

        return [
            'title' => $this->status === 'approved' ? 'Budget Change Request Approved' : 'Budget Change Request Rejected',
            'message' => $message,
            'action_url' => route('adiutor.tasks.show', $this->task->taskID),
            'task_id' => $this->task->taskID,
            'task_title' => $this->task->taskTitle,
            'status' => $this->status,
            'new_budget' => $this->newBudget,
        ];
    }
}
