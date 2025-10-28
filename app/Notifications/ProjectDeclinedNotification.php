<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ProjectDeclinedNotification extends Notification
{
    use Queueable;

    protected $adiutor;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $adiutor, string $reason)
    {
        $this->adiutor = $adiutor;
        $this->reason = $reason;
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
            'type' => 'project_declined',
            'title' => 'Project Declined',
            'message' => $this->adiutor->fullName . ' has declined a project assignment. Reason: ' . $this->reason,
            'action_url' => route('admin.projects.index'),
        ];
    }
}
