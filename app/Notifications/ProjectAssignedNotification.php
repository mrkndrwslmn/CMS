<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectAssignedNotification extends Notification
{
    use Queueable;

    protected $project;
    protected $assignment;

    /**
     * Create a new notification instance.
     */
    public function __construct($project, $assignment = null)
    {
        $this->project = $project;
        $this->assignment = $assignment;
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
            'title' => 'New Project Assignment',
            'message' => 'You have been assigned to project: ' . $this->project->title,
            'action_url' => route('adiutor.tasks.show', $this->project->id),
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'agreed_rate' => $this->assignment ? $this->assignment->agreed_rate : null,
        ];
    }
}
