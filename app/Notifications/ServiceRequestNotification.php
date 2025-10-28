<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ServiceRequestNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $actionUrl;
    protected $additionalData;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, string $actionUrl = null, array $additionalData = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
        $this->additionalData = $additionalData;
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
        return array_merge([
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->actionUrl,
        ], $this->additionalData);
    }
}
