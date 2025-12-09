<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;

class ProjectReopenedNotification extends Notification
{
    protected Project $project;
    protected string $previousStatus;
    protected string $reason;
    protected string $reopenedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project, string $previousStatus, string $reason, string $reopenedBy = 'Admin')
    {
        $this->project = $project;
        $this->previousStatus = $previousStatus;
        $this->reason = $reason;
        $this->reopenedBy = $reopenedBy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucfirst(str_replace('_', ' ', $this->project->status));
        
        return (new MailMessage)
            ->subject("Project Reopened: {$this->project->title}")
            ->greeting("Hello {$notifiable->fullName}!")
            ->line("The project **{$this->project->title}** has been reopened.")
            ->line("**Previous Status:** " . ucfirst($this->previousStatus))
            ->line("**New Status:** {$statusLabel}")
            ->line("**Reason:** {$this->reason}")
            ->line("**Reopened by:** {$this->reopenedBy}")
            ->action('View Project', $this->getActionUrl($notifiable))
            ->line('You may now continue working on this project or add new tasks and deliverables.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'project_reopened',
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->project->status,
            'reason' => $this->reason,
            'reopened_by' => $this->reopenedBy,
            'message' => "Project '{$this->project->title}' has been reopened",
            'action_url' => $this->getActionUrl($notifiable),
            'icon' => 'rotate-ccw',
            'color' => 'warning'
        ];
    }

    /**
     * Get the appropriate action URL based on the notifiable's role
     */
    protected function getActionUrl(object $notifiable): string
    {
        // Check if user is an adiutor (staff member)
        if ($notifiable->role === 'adiutor') {
            return route('adiutor.projects.show', $this->project->id);
        }
        
        // Check if user is a client
        if ($notifiable->role === 'client') {
            return route('client.projects.show', $this->project->id);
        }
        
        // Default to admin view
        return route('admin.projects.show', $this->project->id);
    }
}
