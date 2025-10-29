<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;
    protected string $newStatus;
    protected string $changedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $newStatus, string $changedBy = 'Admin')
    {
        $this->user = $user;
        $this->newStatus = $newStatus;
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
            'type' => 'user_status_changed',
            'user_id' => $this->user->id,
            'user_name' => $this->user->fullName,
            'user_email' => $this->user->email,
            'new_status' => $this->newStatus,
            'changed_by' => $this->changedBy,
            'message' => "User {$this->user->fullName} has been {$this->newStatus}",
            'action_url' => route('admin.users.show', $this->user->id),
            'icon' => $this->newStatus === 'active' ? 'user-check' : 'user-x',
            'color' => $this->newStatus === 'active' ? 'success' : 'warning'
        ];
    }
}