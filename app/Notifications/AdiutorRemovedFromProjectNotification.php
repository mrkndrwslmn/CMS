<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class AdiutorRemovedFromProjectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $projectTitle;
    protected int $projectId;
    protected string $clientName;
    protected string $removedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $projectTitle, int $projectId, string $clientName, string $removedBy = 'Admin')
    {
        $this->projectTitle = $projectTitle;
        $this->projectId = $projectId;
        $this->clientName = $clientName;
        $this->removedBy = $removedBy;
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
            'type' => 'adiutor_removed_from_project',
            'project_id' => $this->projectId,
            'project_title' => $this->projectTitle,
            'client_name' => $this->clientName,
            'removed_by' => $this->removedBy,
            'message' => "You have been removed from project '{$this->projectTitle}'",
            'icon' => 'user-minus',
            'color' => 'warning'
        ];
    }
}