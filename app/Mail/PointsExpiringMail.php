<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class PointsExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Collection $expiringPoints
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $totalPoints = $this->expiringPoints->sum('points');
        
        return new Envelope(
            subject: 'Reminder: ' . number_format($totalPoints) . ' Loyalty Points Expiring Soon',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $totalExpiringPoints = $this->expiringPoints->sum('points');
        $expiryDate = $this->expiringPoints->first()->expires_at;
        $currentBalance = $this->user->loyaltyPoints->available_points;
        
        return new Content(
            view: 'emails.points-expiring',
            with: [
                'userName' => $this->user->name,
                'totalExpiringPoints' => $totalExpiringPoints,
                'expiryDate' => $expiryDate,
                'daysUntilExpiry' => now()->diffInDays($expiryDate),
                'currentBalance' => $currentBalance,
                'pointsValue' => $totalExpiringPoints, // 1 point = ₱1
                'expiringBatches' => $this->expiringPoints->groupBy(function($item) {
                    return $item->expires_at->format('Y-m-d');
                }),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
