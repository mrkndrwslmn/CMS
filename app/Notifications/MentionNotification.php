<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\GroupChat;
use App\Models\Message;

class MentionNotification extends Notification
{
    protected User $sender;
    protected GroupChat $groupChat;
    protected Message $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $sender, GroupChat $groupChat, Message $message)
    {
        $this->sender = $sender;
        $this->groupChat = $groupChat;
        $this->message = $message;
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
        $role = $notifiable->role;
        $actionUrl = "/{$role}/group-chats/{$this->groupChat->id}";

        // Truncate message for preview
        $messagePreview = strlen($this->message->message) > 100 
            ? substr($this->message->message, 0, 100) . '...' 
            : $this->message->message;

        return [
            'type' => 'mention',
            'title' => "{$this->sender->fullName} mentioned you",
            'message' => "in {$this->groupChat->getDisplayName()}: \"{$messagePreview}\"",
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->fullName,
            'group_chat_id' => $this->groupChat->id,
            'group_chat_name' => $this->groupChat->getDisplayName(),
            'project_id' => $this->groupChat->project_id,
            'message_id' => $this->message->id,
            'action_url' => $actionUrl,
            'icon' => 'at-sign',
            'color' => 'primary'
        ];
    }
}
