<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Project;

class ProjectCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Project $project;
    protected string $createdBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Project $project, string $createdBy = 'Admin')
    {
        $this->project = $project;
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
            'type' => 'project_created',
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'project_budget' => $this->project->budget,
            'project_priority' => $this->project->priority,
            'client_name' => $this->project->client->fullName ?? 'Unknown',
            'created_by' => $this->createdBy,
            'message' => "New project '{$this->project->title}' has been created",
            'action_url' => route('admin.projects.show', $this->project->id),
            'icon' => 'folder-plus',
            'color' => 'success'
        ];
    }
}