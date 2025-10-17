<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ProjectProgressUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $progress;
    protected $projectId;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $projectId, int $progress)
    {
        $this->projectId = $projectId;
        $this->progress = $progress;
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
            'type' => 'project_progress_update',
            'title' => 'Project Progress Updated',
            'message' => 'Your project progress has been updated to ' . $this->progress . '%',
            'action_url' => route('client.projects.show', $this->projectId),
        ];
    }
}
