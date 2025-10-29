<?php

namespace App\Notifications;

use App\Mail\PaymentConfirmed;
use App\Models\Payment;
use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Payment $payment
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
        return (new PaymentConfirmed($this->payment))
            ->onQueue('emails');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $serviceRequest = $this->payment->serviceRequest;
        $actionUrl = '';
        if ($serviceRequest->project_id) {
            $actionUrl = route('admin.projects.show', $serviceRequest->project_id);
        }

        return [
            'type' => 'payment_confirmed',
            'title' => 'Payment Confirmed',
            'message' => "Payment of ₱" . number_format($this->payment->amount, 2) . " confirmed for '{$serviceRequest->project_name}'.",
            'action_url' => $actionUrl,
            'payment_id' => $this->payment->id,
            'service_request_id' => $serviceRequest->id,
            'amount' => $this->payment->amount,
            'payment_type' => $this->payment->getPaymentTypeLabel(),
            'project_name' => $serviceRequest->project_name,
        ];
    }
}
