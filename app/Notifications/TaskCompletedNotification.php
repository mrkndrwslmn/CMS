<?php

namespace App\Notifications;

use App\Mail\TaskCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected $task,
        protected $completedBy
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
    public function toMail($notifiable): TaskCompleted
    {
        return (new TaskCompleted($this->task, $this->completedBy, $notifiable))
            ->onQueue('emails');
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
