<?php

namespace App\Notifications;

use App\Models\RevisionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RevisionRequestedNotification extends Notification
{
    use Queueable;

    protected $revisionRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(RevisionRequest $revisionRequest)
    {
        $this->revisionRequest = $revisionRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $document = $this->revisionRequest->document;
        $client = $this->revisionRequest->requestedBy;
        
        $sourceDescription = $this->revisionRequest->getSourceDescription();

        return (new MailMessage)
            ->subject('New Revision Request Submitted')
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line('A client has requested a revision for a document.')
            ->line('**Client:** ' . $client->fullName)
            ->line('**Document:** ' . $document->fileName)
            ->line('**Source:** ' . $sourceDescription)
            ->line('**Reason:** ' . $this->revisionRequest->reason)
            ->when($this->revisionRequest->requested_due_date, function($mail) {
                return $mail->line('**Requested Due Date:** ' . $this->revisionRequest->requested_due_date->format('M d, Y'));
            })
            ->action('Review Revision Request', route('admin.revisions.show', $this->revisionRequest->id))
            ->line('Please review and take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'revision_requested',
            'revision_request_id' => $this->revisionRequest->id,
            'document_id' => $this->revisionRequest->document_id,
            'document_name' => $this->revisionRequest->document->fileName,
            'client_name' => $this->revisionRequest->requestedBy->fullName,
            'source_type' => $this->revisionRequest->source_type,
            'source_description' => $this->revisionRequest->getSourceDescription(),
            'reason' => substr($this->revisionRequest->reason, 0, 100),
            'message' => 'New revision request from ' . $this->revisionRequest->requestedBy->fullName,
            'action_url' => route('admin.revisions.show', $this->revisionRequest->id)
        ];
    }
}
