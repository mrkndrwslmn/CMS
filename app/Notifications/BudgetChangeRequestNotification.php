<?php

namespace App\Notifications;

use App\Mail\BudgetChangeRequested;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BudgetChangeRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected $task,
        protected $adiutor,
        protected $currentBudget,
        protected $requestedBudget
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
        return (new BudgetChangeRequested($this->task, $this->adiutor, $this->currentBudget, $this->requestedBudget, $notifiable))
            ->onQueue('emails');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Budget Change Request',
            'message' => $this->adiutor->fullName . ' has requested a budget change for task: ' . $this->task->taskTitle,
            'action_url' => route('admin.budget-requests.index'),
            'task_id' => $this->task->taskID,
            'task_title' => $this->task->taskTitle,
            'current_budget' => $this->currentBudget,
            'requested_budget' => $this->requestedBudget,
        ];
    }
}
