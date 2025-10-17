<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewServiceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $serviceRequestId;
    protected $projectName;
    protected $userName;
    protected $isNewUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $serviceRequestId, string $projectName, string $userName, bool $isNewUser = false)
    {
        $this->serviceRequestId = $serviceRequestId;
        $this->projectName = $projectName;
        $this->userName = $userName;
        $this->isNewUser = $isNewUser;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $newUserTag = $this->isNewUser ? ' (New User)' : '';
        
        return (new MailMessage)
            ->subject('New Service Request Submitted')
            ->greeting('Hello Admin!')
            ->line("A new service request has been submitted by **{$this->userName}**{$newUserTag}")
            ->line("**Project Name:** {$this->projectName}")
            ->line('Please review the request and take appropriate action.')
            ->action('View Request', route('admin.requests.show', $this->serviceRequestId))
            ->line('Thank you for your attention to this matter!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $newUserTag = $this->isNewUser ? ' (new user)' : '';
        
        return [
            'type' => 'new_service_request',
            'title' => 'New Service Request',
            'message' => "New service request '{$this->projectName}' submitted by {$this->userName}{$newUserTag}",
            'action_url' => route('admin.requests.show', $this->serviceRequestId),
            'service_request_id' => $this->serviceRequestId,
            'project_name' => $this->projectName,
            'user_name' => $this->userName,
            'is_new_user' => $this->isNewUser,
        ];
    }
}
