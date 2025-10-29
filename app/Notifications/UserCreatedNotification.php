<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;
    protected string $createdBy;
    protected bool $isManualCreation;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $createdBy = 'Admin', bool $isManualCreation = true)
    {
        $this->user = $user;
        $this->createdBy = $createdBy;
        $this->isManualCreation = $isManualCreation;
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
            'type' => 'user_created',
            'user_id' => $this->user->id,
            'user_name' => $this->user->fullName,
            'user_email' => $this->user->email,
            'user_role' => $this->user->role,
            'created_by' => $this->createdBy,
            'is_manual_creation' => $this->isManualCreation,
            'message' => "New {$this->user->role} user {$this->user->fullName} has been created",
            'action_url' => route('admin.users.show', $this->user->id),
            'icon' => 'user-plus',
            'color' => 'success'
        ];
    }
}