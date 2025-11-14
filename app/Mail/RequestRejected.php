<?php

namespace App\Mail;

use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class RequestRejected extends BaseMailable
{
    public $serviceRequest;
    public $rejectionReason;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_PROJECTS;

    /**
     * Create a new message instance.
     */
    public function __construct($serviceRequest, string $rejectionReason)
    {
        $this->serviceRequest = $serviceRequest;
        $this->rejectionReason = $rejectionReason;
        
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return 'Project Request Update - Unable to Proceed';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.request-rejected',
            with: [
                'serviceRequest' => $this->serviceRequest,
                'rejectionReason' => $this->rejectionReason,
            ]
        );
    }
}
