<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ProjectAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $adiutor;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $adiutor)
    {
        $this->adiutor = $adiutor;
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
            'type' => 'project_accepted',
            'title' => 'Project Accepted',
            'message' => $this->adiutor->fullName . ' has accepted a project assignment',
            'action_url' => route('admin.projects.index'),
        ];
    }
}
