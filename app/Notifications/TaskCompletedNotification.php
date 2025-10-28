<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TaskCompletedNotification extends Notification
{
    use Queueable;

    protected $task;
    protected $completedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $completedBy)
    {
        $this->task = $task;
        $this->completedBy = $completedBy;
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
        return (new MailMessage)
            ->subject('Task Completed')
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line("**{$this->completedBy->fullName}** has marked a task as completed.")
            ->line("**Task:** {$this->task->taskTitle}")
            ->line('Please review the completed task and take any necessary action.')
            ->action('View Task', route('admin.tasks.show', $this->task->taskID))
            ->line('Thank you for managing this project!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Task Completed',
            'message' => $this->completedBy->fullName . ' has completed task: ' . $this->task->taskTitle,
            'action_url' => route('admin.tasks.show', $this->task->taskID),
            'task_id' => $this->task->taskID,
            'task_title' => $this->task->taskTitle,
            'completed_by' => $this->completedBy->fullName,
        ];
    }
}
