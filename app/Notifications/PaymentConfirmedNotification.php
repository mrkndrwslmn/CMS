<?php

namespace App\Notifications;

use App\Mail\PaymentConfirmed;
use App\Models\Payment;
use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

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
        $channels = ['database', 'mail'];
        
        Log::info('PaymentConfirmedNotification dispatched', [
            'notification_type' => 'payment_confirmed',
            'payment_id' => $this->payment->id,
            'recipient_id' => $notifiable->id,
            'recipient_email' => $notifiable->email,
            'recipient_role' => $notifiable->role,
            'payment_amount' => $this->payment->amount,
            'payment_type' => $this->payment->payment_type,
            'service_request_id' => $this->payment->service_request_id,
            'channels' => $channels
        ]);
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): PaymentConfirmed
    {
        try {
            $mail = (new PaymentConfirmed($this->payment))->onQueue('emails');
            
            Log::info('PaymentConfirmedNotification email queued successfully', [
                'notification_type' => 'payment_confirmed',
                'payment_id' => $this->payment->id,
                'recipient_id' => $notifiable->id,
                'recipient_email' => $notifiable->email,
                'payment_amount' => $this->payment->amount
            ]);
            
            return $mail;
        } catch (\Exception $e) {
            Log::error('PaymentConfirmedNotification email failed', [
                'notification_type' => 'payment_confirmed',
                'payment_id' => $this->payment->id,
                'recipient_id' => $notifiable->id,
                'recipient_email' => $notifiable->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        try {
            $serviceRequest = $this->payment->serviceRequest;
            $actionUrl = '';
            if ($serviceRequest->project_id) {
                $actionUrl = route('admin.projects.show', $serviceRequest->project_id);
            }

            $data = [
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
            
            Log::info('PaymentConfirmedNotification database notification created successfully', [
                'notification_type' => 'payment_confirmed',
                'payment_id' => $this->payment->id,
                'recipient_id' => $notifiable->id,
                'data' => $data
            ]);
            
            return $data;
        } catch (\Exception $e) {
            Log::error('PaymentConfirmedNotification database notification failed', [
                'notification_type' => 'payment_confirmed',
                'payment_id' => $this->payment->id,
                'recipient_id' => $notifiable->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}
