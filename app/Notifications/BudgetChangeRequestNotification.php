<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BudgetChangeRequestNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $adiutor;
    protected $currentBudget;
    protected $requestedBudget;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $adiutor, $currentBudget, $requestedBudget)
    {
        $this->task = $task;
        $this->adiutor = $adiutor;
        $this->currentBudget = $currentBudget;
        $this->requestedBudget = $requestedBudget;
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
    public function toMail($notifiable): MailMessage
    {
        $change = $this->requestedBudget - $this->currentBudget;
        $changePercent = $this->currentBudget > 0 
            ? round(($change / $this->currentBudget) * 100, 2) 
            : 0;
        $changeText = $change > 0 ? "increase" : "decrease";
        
        return (new MailMessage)
            ->subject('Budget Change Request - Action Required')
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line("**{$this->adiutor->fullName}** has requested a budget change for a task.")
            ->line("**Task:** {$this->task->taskTitle}")
            ->line("**Current Budget:** ₱" . number_format($this->currentBudget, 2))
            ->line("**Requested Budget:** ₱" . number_format($this->requestedBudget, 2))
            ->line("**Change:** ₱" . number_format(abs($change), 2) . " {$changeText} ({$changePercent}%)")
            ->line('Please review and approve or reject this request.')
            ->action('Review Request', route('admin.budget-requests.index'))
            ->line('Thank you for your prompt attention!');
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
