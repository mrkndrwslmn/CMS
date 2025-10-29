<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TaskAssignedNotification extends Notification
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
        $channels = ['database'];
        
        Log::info('TaskAssignedNotification dispatched', [
            'notification_type' => 'task_assigned',
            'task_id' => $this->task->taskID,
            'task_title' => $this->task->taskTitle,
            'project_id' => $this->project?->id,
            'project_title' => $this->project?->title,
            'recipient_id' => $notifiable->id,
            'recipient_email' => $notifiable->email,
            'recipient_role' => $notifiable->role,
            'channels' => $channels
        ]);
        
        return $channels;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        try {
            $data = [
                'title' => 'New Task Assigned',
                'message' => 'You have been assigned to task: ' . $this->task->taskTitle,
                'action_url' => route('adiutor.tasks.show', $this->task->taskID),
                'task_id' => $this->task->taskID,
                'task_title' => $this->task->taskTitle,
                'project_name' => $this->project ? $this->project->title : null,
            ];
            
            Log::info('TaskAssignedNotification database notification created successfully', [
                'notification_type' => 'task_assigned',
                'task_id' => $this->task->taskID,
                'recipient_id' => $notifiable->id,
                'data' => $data
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('TaskAssignedNotification database notification failed', [
                'notification_type' => 'task_assigned',
                'task_id' => $this->task->taskID,
                'recipient_id' => $notifiable->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}
