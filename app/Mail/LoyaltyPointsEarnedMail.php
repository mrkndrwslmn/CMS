<?php

namespace App\Mail;

use App\Models\LoyaltyTransaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoyaltyPointsEarnedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public LoyaltyTransaction $transaction
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'ve Earned Loyalty Points! 🎉',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $loyaltyPoint = $this->user->loyaltyPoints;
        
        return new Content(
            view: 'emails.loyalty-points-earned',
            with: [
                'userName' => $this->user->name,
                'pointsEarned' => $this->transaction->points,
                'reason' => $this->transaction->description,
                'newBalance' => $this->transaction->balance_after,
                'currentTier' => ucfirst($loyaltyPoint->tier),
                'tierDiscount' => $this->getTierDiscount($loyaltyPoint->tier),
                'pointsToNextTier' => $this->getPointsToNextTier($loyaltyPoint),
                'nextTier' => $this->getNextTier($loyaltyPoint->tier),
            ],
        );
    }

    /**
     * Get tier discount percentage.
     */
    private function getTierDiscount(string $tier): int
    {
        $discounts = [
            'bronze' => 0,
            'silver' => 5,
            'gold' => 10,
            'platinum' => 15,
        ];

        return $discounts[$tier] ?? 0;
    }

    /**
     * Get points needed to reach next tier.
     */
    private function getPointsToNextTier($loyaltyPoint): ?int
    {
        $tiers = [
            'bronze' => 5000,
            'silver' => 15000,
            'gold' => 50000,
        ];

        if ($loyaltyPoint->tier === 'platinum') {
            return null; // Already at highest tier
        }

        $nextTierThreshold = $tiers[$loyaltyPoint->tier];
        return max(0, $nextTierThreshold - $loyaltyPoint->lifetime_earned);
    }

    /**
     * Get next tier name.
     */
    private function getNextTier(string $currentTier): ?string
    {
        $tierOrder = ['bronze', 'silver', 'gold', 'platinum'];
        $currentIndex = array_search($currentTier, $tierOrder);

        if ($currentIndex === false || $currentIndex >= count($tierOrder) - 1) {
            return null;
        }

        return ucfirst($tierOrder[$currentIndex + 1]);
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
