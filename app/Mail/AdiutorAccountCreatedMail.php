<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;

/**
 * Email sent to adiutors when their account is created by an admin.
 * 
 * Contains the temporary password and instructions on how to access the account.
 */
class AdiutorAccountCreatedMail extends BaseMailable
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
        return 'Welcome to Treis Adiutor - Your Account Has Been Created';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.adiutor-account-created',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl' => route('login'),
                'supportEmail' => config('mail.support_email', 'support@treisadiutor.com')
            ]
        );
    }
}
