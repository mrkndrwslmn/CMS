<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PayoutPaidNotification extends Notification
{
    use Queueable;

    protected $payout;
    protected $processedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($payout, $processedBy)
    {
        $this->payout = $payout;
        $this->processedBy = $processedBy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'payout_paid',
            'title' => 'Payment Completed',
            'message' => 'Your payment of ₱' . number_format($this->payout->amount, 2) . ' has been successfully processed. Reference: ' . ($this->payout->reference_number ?? 'N/A'),
            'action_url' => route('adiutor.earnings.payouts.show', $this->payout->id),
            'payout_id' => $this->payout->id,
            'payout_number' => $this->payout->payout_number,
            'amount' => $this->payout->amount,
            'status' => $this->payout->status,
            'reference_number' => $this->payout->reference_number,
            'processed_by' => $this->processedBy->fullName,
            'icon' => 'payment',
        ];
    }

    /**
     * Get Firebase push notification data.
     */
    public function toFirebase($notifiable): array
    {
        return [
            'notification' => [
                'title' => '💰 Payment Completed!',
                'body' => '₱' . number_format($this->payout->amount, 2) . ' has been sent to your account. Ref: ' . ($this->payout->reference_number ?? 'N/A'),
            ],
            'data' => [
                'type' => 'payout_paid',
                'payout_id' => (string) $this->payout->id,
                'payout_number' => $this->payout->payout_number,
                'amount' => (string) $this->payout->amount,
                'reference_number' => $this->payout->reference_number ?? '',
                'click_action' => '/adiutor/earnings/payouts/' . $this->payout->id,
                'role' => 'adiutor',
            ],
        ];
    }
}
