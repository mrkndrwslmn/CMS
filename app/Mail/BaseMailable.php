<?php

namespace App\Mail;

use App\Services\EmailSenderService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

        // Log mail creation
        Log::info('Mail instance created', [
            'mail_class' => static::class,
            'sender_type' => $this->senderType,
            'queue' => $this->queue ?? 'default'
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        try {
            $sender = $this->senderService->getSender($this->senderType);
            
            $envelope = new Envelope(
                from: new Address($sender['address'], $sender['name']),
                subject: $this->getSubject(),
            );

            Log::info('Mail envelope created successfully', [
                'mail_class' => static::class,
                'sender_type' => $this->senderType,
                'from_address' => $sender['address'],
                'from_name' => $sender['name'],
                'subject' => $this->getSubject()
            ]);

            return $envelope;
        } catch (\Exception $e) {
            Log::error('Mail envelope creation failed', [
                'mail_class' => static::class,
                'sender_type' => $this->senderType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Build the message (called by Laravel when sending email)
     */
    public function build()
    {
        try {
            // For modern Laravel mailables that use content() method, we don't call parent::build()
            // Instead, we return $this to allow the Laravel framework to handle the build process
            $result = $this;
            
            Log::info('Mail built successfully', [
                'mail_class' => static::class,
                'sender_type' => $this->senderType,
                'to' => $this->to,
                'cc' => $this->cc,
                'bcc' => $this->bcc,
                'subject' => $this->subject
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Mail build failed', [
                'mail_class' => static::class,
                'sender_type' => $this->senderType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
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
        
        Log::debug('Mail sender type changed', [
            'mail_class' => static::class,
            'new_sender_type' => $senderType
        ]);
        
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