<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $project;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $project = null)
    {
        $this->task = $task;
        $this->project = $project;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Task Assigned',
            'message' => 'You have been assigned to task: ' . $this->task->taskTitle,
            'action_url' => route('adiutor.tasks.show', $this->task->taskID),
            'task_id' => $this->task->taskID,
            'task_title' => $this->task->taskTitle,
            'project_name' => $this->project ? $this->project->title : null,
        ];
    }
}
