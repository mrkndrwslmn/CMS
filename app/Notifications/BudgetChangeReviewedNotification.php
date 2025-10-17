<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BudgetChangeReviewedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $status;
    protected $newBudget;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $status, $newBudget = null, $reason = null)
    {
        $this->task = $task;
        $this->status = $status;
        $this->newBudget = $newBudget;
        $this->reason = $reason;
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
        $statusText = $this->status === 'approved' ? 'Approved' : 'Rejected';
        
        $message = (new MailMessage)
            ->subject("Budget Change Request {$statusText}")
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line("Your budget change request for task **{$this->task->taskTitle}** has been **{$statusText}**.");
        
        if ($this->status === 'approved') {
            $message->line('**New Budget:** ₱' . number_format($this->newBudget, 2))
                ->line('The new budget has been applied to your task.')
                ->action('View Task', route('adiutor.tasks.show', $this->task->taskID));
        } else {
            if ($this->reason) {
                $message->line('**Reason:** ' . $this->reason);
            }
            $message->line('Please contact your project manager if you have any questions.')
                ->action('View Task', route('adiutor.tasks.show', $this->task->taskID));
        }
        
        return $message;
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
