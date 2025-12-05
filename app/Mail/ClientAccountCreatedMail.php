<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Email sent to clients when their account is created by an admin.
 * 
 * Contains the temporary password and instructions on how to access the account.
 */
class ClientAccountCreatedMail extends BaseMailable
{
    protected string $senderType = 'welcome';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $temporaryPassword
    ) {
        parent::__construct();
    }

    /**
     * Get the subject line for the email.
     */
    protected function getSubject(): string
    {
        return 'Your Treis Adiutor Client Account Has Been Created';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.client-account-created',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl' => route('login'),
                'supportEmail' => config('mail.support_email', 'support@treisadiutor.com')
            ]
        );
    }
}
