<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;

class PaymentConfirmed extends BaseMailable
{
    public $payment;
    public $serviceRequest;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_BILLING;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->serviceRequest = $payment->serviceRequest;
        
        parent::__construct();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = $this->senderService->getSender($this->senderType);
        $client = $this->serviceRequest->client;
        
        $envelope = new Envelope(
            from: new Address($sender['address'], $sender['name']),
            subject: $this->getSubject(),
        );

        // Set the recipient email if available
        if ($client && $client->email) {
            $envelope->to(
                new Address($client->email, $client->name ?? '')
            );
        }

        return $envelope;
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        $paymentType = $this->payment->getPaymentTypeLabel();
        return "Payment Confirmed - {$paymentType}";
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-confirmed',
            with: [
                'payment' => $this->payment,
                'serviceRequest' => $this->serviceRequest,
            ]
        );
    }
}
