<?php

namespace App\Mail;

use App\Models\ServiceRequest;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class PaymentConfirmed extends BaseMailable
{
    public $serviceRequest;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_BILLING;

    /**
     * Create a new message instance.
     */
    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
        
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return 'Payment Confirmed - Project Starting';
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-confirmed',
            with: [
                'serviceRequest' => $this->serviceRequest,
            ]
        );
    }
}
