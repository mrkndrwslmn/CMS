<?php

namespace App\Notifications;

use App\Mail\PaymentConfirmed;
use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected ServiceRequest $serviceRequest
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
    public function toMail($notifiable): PaymentConfirmed
    {
        return (new PaymentConfirmed($this->serviceRequest))
            ->onQueue('emails');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $actionUrl = '';
        if ($this->serviceRequest->project_id) {
            $actionUrl = route('admin.projects.show', $this->serviceRequest->project_id);
        }

        return [
            'type' => 'payment_confirmed',
            'title' => 'Payment Confirmed',
            'message' => "Payment confirmed for '{$this->serviceRequest->project_name}'. Project created.",
            'action_url' => $actionUrl,
            'service_request_id' => $this->serviceRequest->id,
            'project_name' => $this->serviceRequest->project_name,
        ];
    }
}
