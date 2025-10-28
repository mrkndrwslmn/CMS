<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentConfirmedNotification extends Notification
{
    use Queueable;

    protected $serviceRequestId;
    protected $projectId;
    protected $paymentId;
    protected $projectName;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $serviceRequestId, int $projectId, int $paymentId, string $projectName)
    {
        $this->serviceRequestId = $serviceRequestId;
        $this->projectId = $projectId;
        $this->paymentId = $paymentId;
        $this->projectName = $projectName;
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
        return (new MailMessage)
            ->subject('Payment Confirmed - Project Created')
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line("Payment has been confirmed for the project: **{$this->projectName}**")
            ->line('The project has been successfully created and is ready to begin.')
            ->action('View Project', route('admin.projects.show', $this->projectId))
            ->line('Payment ID: ' . $this->paymentId)
            ->line('Thank you for managing this project!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'payment_confirmed',
            'title' => 'Payment Confirmed',
            'message' => "Payment confirmed for '{$this->projectName}'. Project created.",
            'action_url' => route('admin.projects.show', $this->projectId),
            'service_request_id' => $this->serviceRequestId,
            'project_id' => $this->projectId,
            'payment_id' => $this->paymentId,
            'project_name' => $this->projectName,
        ];
    }
}
