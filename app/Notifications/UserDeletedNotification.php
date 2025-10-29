<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $userData;
    protected string $deletedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $deletedBy = 'Admin')
    {
        // Store user data before deletion
        $this->userData = [
            'id' => $user->id,
            'name' => $user->fullName,
            'email' => $user->email,
            'role' => $user->role
        ];
        $this->deletedBy = $deletedBy;
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
            'type' => 'user_deleted',
            'user_data' => $this->userData,
            'deleted_by' => $this->deletedBy,
            'message' => "User {$this->userData['name']} ({$this->userData['role']}) has been deleted",
            'icon' => 'user-minus',
            'color' => 'danger'
        ];
    }
}