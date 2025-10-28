<?php

namespace App\Mail;

use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class NewUserCredentials extends BaseMailable
{
    public $fullName;
    public $email;
    public $password;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_AUTH;

    /**
     * Create a new message instance.
     */
    public function __construct($fullName, $email, $password)
    {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->password = $password;
        
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return 'Your Account Credentials - Treis Adiutor';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-user-credentials',
            with: [
                'fullName' => $this->fullName,
                'email' => $this->email,
                'password' => $this->password,
            ]
        );
    }
}
