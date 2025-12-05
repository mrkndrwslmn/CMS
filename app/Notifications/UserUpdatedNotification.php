<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserUpdatedNotification extends Notification
{
    protected User $user;
    protected array $changes;
    protected string $updatedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, array $changes, string $updatedBy = 'Admin')
    {
        $this->user = $user;
        $this->changes = $changes;
        $this->updatedBy = $updatedBy;
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
        $changesText = $this->formatChanges();
        
        return [
            'type' => 'user_updated',
            'user_id' => $this->user->id,
            'user_name' => $this->user->fullName,
            'user_email' => $this->user->email,
            'user_role' => $this->user->role,
            'changes' => $this->changes,
            'changes_text' => $changesText,
            'updated_by' => $this->updatedBy,
            'message' => "User {$this->user->fullName} has been updated: {$changesText}",
            'action_url' => route('admin.users.show', $this->user->id),
            'icon' => 'edit',
            'color' => 'info'
        ];
    }

    protected function formatChanges(): string
    {
        $formatted = [];
        
        foreach ($this->changes as $field => $change) {
            $fieldName = str_replace('_', ' ', $field);
            $fieldName = ucwords($fieldName);
            
            if (is_array($change) && isset($change['old'], $change['new'])) {
                $formatted[] = "{$fieldName}: {$change['old']} → {$change['new']}";
            } else {
                $formatted[] = "{$fieldName} updated";
            }
        }
        
        return implode(', ', $formatted);
    }
}