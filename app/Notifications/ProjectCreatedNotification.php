<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Models\Project;

class ProjectCreatedNotification extends Notification
{
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
        $channels = ['database'];
        
        Log::info('ProjectCreatedNotification dispatched', [
            'notification_type' => 'project_created',
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'project_budget' => $this->project->budget,
            'client_id' => $this->project->client_id,
            'created_by' => $this->createdBy,
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
    public function toArray(object $notifiable): array
    {
        try {
            $data = [
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
            
            Log::info('ProjectCreatedNotification database notification created successfully', [
                'notification_type' => 'project_created',
                'project_id' => $this->project->id,
                'recipient_id' => $notifiable->id,
                'data' => $data
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('ProjectCreatedNotification database notification failed', [
                'notification_type' => 'project_created',
                'project_id' => $this->project->id,
                'recipient_id' => $notifiable->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}