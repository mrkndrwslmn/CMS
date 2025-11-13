<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Referral;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class ReferralSignupMail extends BaseMailable
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
        public User $referred,
        public Referral $referral
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return '🎉 Good News! Your Referral Just Signed Up';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.referral.signup',
            with: [
                'referrer' => $this->referrer,
                'referred' => $this->referred,
                'referral' => $this->referral,
                'referralCode' => $this->referrer->referralCode,
                'pendingPoints' => $this->referral->referrer_points_pending,
            ],
        );
    }
}
