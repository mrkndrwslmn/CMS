<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskDeletedNotification extends Notification
{
    protected array $taskData;
    protected string $deletedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task, string $deletedBy = 'Admin')
    {
        // Store task data before deletion
        $this->taskData = [
            'id' => $task->taskID,
            'title' => $task->taskTitle,
            'project_title' => $task->project->title ?? 'Unknown Project',
            'assigned_to' => $task->assignedUser->fullName ?? 'Unassigned',
            'status' => $task->status,
            'priority' => $task->priority
        ];
        $this->deletedBy = $deletedBy;
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
            'type' => 'task_deleted',
            'task_data' => $this->taskData,
            'deleted_by' => $this->deletedBy,
            'message' => "Task '{$this->taskData['title']}' has been deleted",
            'icon' => 'minus-square',
            'color' => 'danger'
        ];
    }
}