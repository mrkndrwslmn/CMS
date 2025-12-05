<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use App\Models\User;

class TaskCreatedNotification extends Notification
{
    protected Task $task;
    protected string $createdBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, string $createdBy = 'Admin')
    {
        $this->task = $task;
        $this->createdBy = $createdBy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'task_created',
            'task_id' => $this->task->taskID,
            'task_title' => $this->task->taskTitle,
            'project_title' => $this->task->project->title ?? 'Unknown Project',
            'priority' => $this->task->priority,
            'deadline' => $this->task->deadline,
            'assigned_to' => $this->task->assignedUser->fullName ?? 'Unassigned',
            'created_by' => $this->createdBy,
            'message' => "New task '{$this->task->taskTitle}' has been created",
            'action_url' => route('admin.tasks.show', $this->task->taskID),
            'icon' => 'plus-square',
            'color' => 'success'
        ];
    }
}