<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AccountDeactivatedMail extends BaseMailable
{
    protected string $senderType = 'admin';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email.
     */
    protected function getSubject(): string
    {
        return 'Account Deactivated - Treis Adiutor';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-deactivated',
            with: [
                'user' => $this->user,
                'supportEmail' => config('mail.support_email', 'support@treisadiutor.com'),
                'loginUrl' => route('login')
            ]
        );
    }
}