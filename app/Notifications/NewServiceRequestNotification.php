<?php

namespace App\Notifications;

use App\Mail\NewServiceRequest;
use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewServiceRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected ServiceRequest $serviceRequest,
        protected bool $isNewUser = false
    ) {
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
    public function toMail($notifiable): NewServiceRequest
    {
        return (new NewServiceRequest($this->serviceRequest, $notifiable, $this->isNewUser))
            ->onQueue('emails');
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
            'message' => "New service request '{$this->serviceRequest->project_name}' submitted by {$this->serviceRequest->user->fullName}{$newUserTag}",
            'action_url' => route('admin.requests.show', $this->serviceRequest->id),
            'service_request_id' => $this->serviceRequest->id,
            'project_name' => $this->serviceRequest->project_name,
            'user_name' => $this->serviceRequest->user->fullName,
            'is_new_user' => $this->isNewUser,
        ];
    }
}
