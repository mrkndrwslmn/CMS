<?php

namespace App\Mail;

use App\Models\User;
use App\Models\ReferralCode;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class ReferralInvitationMail extends BaseMailable
{
    /**
     * The sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_DEFAULT;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $referrer,
        public ReferralCode $referralCode,
        public ?string $personalMessage = null
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return $this->referrer->fullName . ' invited you to join TREIS ADIUTOR! 🎁';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.referral.invitation',
            with: [
                'referrer' => $this->referrer,
                'referralCode' => $this->referralCode,
                'personalMessage' => $this->personalMessage,
                'referralUrl' => route('register', ['ref' => $this->referralCode->code]),
                'referredBonus' => config('referral.rewards.referred.signup_points', 500),
                'referredDiscount' => config('referral.rewards.referred.coupon_discount', 15),
            ],
        );
    }
}
