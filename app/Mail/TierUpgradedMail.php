<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TierUpgradedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $oldTier,
        public string $newTier
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! You\'ve Been Upgraded to ' . ucfirst($this->newTier) . ' Tier! 🏆',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tier-upgraded',
            with: [
                'userName' => $this->user->name,
                'oldTier' => ucfirst($this->oldTier),
                'newTier' => ucfirst($this->newTier),
                'benefits' => $this->getTierBenefits($this->newTier),
                'earningRate' => $this->getEarningRate($this->newTier),
                'discount' => $this->getTierDiscount($this->newTier),
                'totalPoints' => $this->user->loyaltyPoints->lifetime_earned,
            ],
        );
    }

    /**
     * Get tier-specific benefits.
     */
    private function getTierBenefits(string $tier): array
    {
        $benefits = [
            'silver' => [
                '2% points earning rate on all purchases',
                '5% automatic discount on all services',
                'Priority support with 24-hour response time',
                'Early access to new services and features',
            ],
            'gold' => [
                '3% points earning rate on all purchases',
                '10% automatic discount on all services',
                'Priority support with 12-hour response time',
                'One free minor revision per project',
                'Special birthday month coupon',
                'Early access to exclusive deals',
            ],
            'platinum' => [
                '5% points earning rate on all purchases',
                '15% automatic discount on all services',
                'VIP support with 6-hour response time',
                'Two free minor revisions per project',
                'Quarterly exclusive coupons',
                'Dedicated account manager',
                'Free consultation sessions',
                'Invitation to exclusive events',
            ],
        ];

        return $benefits[$tier] ?? [];
    }

    /**
     * Get earning rate for tier.
     */
    private function getEarningRate(string $tier): string
    {
        $rates = [
            'bronze' => '1%',
            'silver' => '2%',
            'gold' => '3%',
            'platinum' => '5%',
        ];

        return $rates[$tier] ?? '1%';
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
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
