<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;

class ProjectStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Project $project;
    protected string $oldStatus;
    protected string $newStatus;
    protected string $changedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project, string $oldStatus, string $changedBy = 'Admin')
    {
        $this->project = $project;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $project->status;
        $this->changedBy = $changedBy;
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
            'type' => 'project_status_changed',
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => $this->changedBy,
            'message' => "Project '{$this->project->title}' status changed from {$this->oldStatus} to {$this->newStatus}",
            'action_url' => route('admin.projects.show', $this->project->id),
            'icon' => $this->getStatusIcon(),
            'color' => $this->getStatusColor()
        ];
    }

    protected function getStatusIcon(): string
    {
        return match($this->newStatus) {
            'active' => 'play',
            'in_progress' => 'activity',
            'completed' => 'check-circle',
            'cancelled' => 'x-circle',
            'review' => 'eye',
            default => 'folder'
        };
    }

    protected function getStatusColor(): string
    {
        return match($this->newStatus) {
            'active' => 'info',
            'in_progress' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'review' => 'warning',
            default => 'secondary'
        };
    }
}