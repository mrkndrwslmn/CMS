<?php

namespace App\Mail;

use App\Services\EmailSenderService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

abstract class BaseMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The email sender service
     */
    protected EmailSenderService $senderService;

    /**
     * Override this in child classes to specify sender type
     */
    protected string $senderType = EmailSenderService::SENDER_DEFAULT;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->senderService = app(EmailSenderService::class);
        
        // Auto-detect sender type if not explicitly set
        if ($this->senderType === EmailSenderService::SENDER_DEFAULT) {
            $this->senderType = $this->senderService->autoDetectSender(static::class);
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = $this->senderService->getSender($this->senderType);
        
        return new Envelope(
            from: new Address($sender['address'], $sender['name']),
            subject: $this->getSubject(),
        );
    }

    /**
     * Get the subject line for the email (to be implemented by child classes)
     */
    abstract protected function getSubject(): string;

    /**
     * Get the message content definition (to be implemented by child classes)
     */
    abstract public function content(): Content;

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Manually set the sender type
     */
    public function setSenderType(string $senderType): self
    {
        $this->senderType = $senderType;
        return $this;
    }

    /**
     * Get the current sender type
     */
    public function getSenderType(): string
    {
        return $this->senderType;
    }

    /**
     * Get sender information for debugging
     */
    public function getSenderInfo(): array
    {
        return [
            'type' => $this->senderType,
            'config' => $this->senderService->getSender($this->senderType),
            'class' => static::class,
        ];
    }
}