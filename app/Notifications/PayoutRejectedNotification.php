<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PayoutRejectedNotification extends Notification
{
    use Queueable;

    protected $payout;
    protected $rejectedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($payout, $rejectedBy)
    {
        $this->payout = $payout;
        $this->rejectedBy = $rejectedBy;
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
            'type' => 'payout_rejected',
            'title' => 'Payout Request Declined',
            'message' => 'Your payout request (' . $this->payout->payout_number . ') for ₱' . number_format($this->payout->amount, 2) . ' has been declined. Please review the reason and take appropriate action.',
            'action_url' => route('adiutor.earnings.payouts.show', $this->payout->id),
            'payout_id' => $this->payout->id,
            'payout_number' => $this->payout->payout_number,
            'amount' => $this->payout->amount,
            'status' => $this->payout->status,
            'rejection_reason' => $this->payout->rejection_reason,
            'rejected_by' => $this->rejectedBy->fullName,
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
                'title' => 'Payout Request Declined',
                'body' => 'Your payout request of ₱' . number_format($this->payout->amount, 2) . ' has been declined. Tap to view details.',
            ],
            'data' => [
                'type' => 'payout_rejected',
                'payout_id' => (string) $this->payout->id,
                'payout_number' => $this->payout->payout_number,
                'amount' => (string) $this->payout->amount,
                'rejection_reason' => $this->payout->rejection_reason ?? '',
                'click_action' => '/adiutor/earnings/payouts/' . $this->payout->id,
                'role' => 'adiutor',
            ],
        ];
    }
}
