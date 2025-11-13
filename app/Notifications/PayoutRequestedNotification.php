<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PayoutRequestedNotification extends Notification
{
    use Queueable;

    protected $payout;
    protected $adiutor;

    /**
     * Create a new notification instance.
     */
    public function __construct($payout, $adiutor)
    {
        $this->payout = $payout;
        $this->adiutor = $adiutor;
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
            'type' => 'payout_requested',
            'title' => 'New Payout Request',
            'message' => $this->adiutor->fullName . ' has submitted a payout request for ₱' . number_format($this->payout->amount, 2),
            'action_url' => route('admin.payouts.show', $this->payout->id),
            'payout_id' => $this->payout->id,
            'payout_number' => $this->payout->payout_number,
            'adiutor_id' => $this->adiutor->id,
            'adiutor_name' => $this->adiutor->fullName,
            'amount' => $this->payout->amount,
            'status' => $this->payout->status,
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
                'title' => 'New Payout Request',
                'body' => $this->adiutor->fullName . ' has requested a payout of ₱' . number_format($this->payout->amount, 2),
            ],
            'data' => [
                'type' => 'payout_requested',
                'payout_id' => (string) $this->payout->id,
                'payout_number' => $this->payout->payout_number,
                'adiutor_id' => (string) $this->adiutor->id,
                'adiutor_name' => $this->adiutor->fullName,
                'amount' => (string) $this->payout->amount,
                'click_action' => '/admin/payouts/' . $this->payout->id,
                'role' => 'admin',
            ],
        ];
    }
}
