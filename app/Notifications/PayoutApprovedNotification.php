<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PayoutApprovedNotification extends Notification
{
    use Queueable;

    protected $payout;
    protected $approvedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct($payout, $approvedBy)
    {
        $this->payout = $payout;
        $this->approvedBy = $approvedBy;
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
            'type' => 'payout_approved',
            'title' => 'Payout Approved',
            'message' => 'Your payout request (' . $this->payout->payout_number . ') for ₱' . number_format($this->payout->amount, 2) . ' has been approved and is being processed.',
            'action_url' => route('adiutor.earnings.payouts.show', $this->payout->id),
            'payout_id' => $this->payout->id,
            'payout_number' => $this->payout->payout_number,
            'amount' => $this->payout->amount,
            'status' => $this->payout->status,
            'approved_by' => $this->approvedBy->fullName,
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
                'title' => '🎉 Payout Approved!',
                'body' => 'Your payout of ₱' . number_format($this->payout->amount, 2) . ' has been approved and is being processed.',
            ],
            'data' => [
                'type' => 'payout_approved',
                'payout_id' => (string) $this->payout->id,
                'payout_number' => $this->payout->payout_number,
                'amount' => (string) $this->payout->amount,
                'click_action' => '/adiutor/earnings/payouts/' . $this->payout->id,
                'role' => 'adiutor',
            ],
        ];
    }
}
