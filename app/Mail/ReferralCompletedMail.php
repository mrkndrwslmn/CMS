<?php

namespace App\Mail;

use App\Models\Referral;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class ReferralCompletedMail extends BaseMailable
{
    /**
     * The sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_DEFAULT;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Referral $referral
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return '🎁 Referral Reward Unlocked! Points & Coupon Earned';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.referral.completed',
            with: [
                'referral' => $this->referral,
                'referrer' => $this->referral->referrer,
                'referred' => $this->referral->referred,
                'pointsEarned' => $this->referral->referrer_points_earned,
                'coupon' => $this->referral->referrerCoupon,
            ],
        );
    }
}
