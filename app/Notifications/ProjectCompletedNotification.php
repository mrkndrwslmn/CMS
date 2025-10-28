<?php

namespace App\Notifications;

use App\Mail\ProjectCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected int $projectId,
        protected string $projectTitle
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
    public function toMail($notifiable): ProjectCompleted
    {
        return (new ProjectCompleted($this->projectId, $this->projectTitle, $notifiable))
            ->onQueue('emails');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'project_completed',
            'title' => 'Project Completed',
            'message' => "Your project '{$this->projectTitle}' has been completed!",
            'action_url' => route('client.projects.show', $this->projectId),
            'project_id' => $this->projectId,
            'project_title' => $this->projectTitle,
            'completion_date' => now()->toDateTimeString(),
        ];
    }
}
