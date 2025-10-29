<?php

namespace App\Mail;

use App\Mail\BaseMailable;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeNewUserMail extends BaseMailable
{
    protected string $senderType = 'welcome';

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
        return 'Welcome to Treis Adiutor!';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-new-user',
            with: [
                'user' => $this->user,
                'loginUrl' => route('login'),
                'dashboardUrl' => route($this->user->getDashboardRoute()),
                'supportEmail' => config('mail.support_email', 'support@treisadiutor.com')
            ]
        );
    }
}