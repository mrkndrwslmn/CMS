<?php

namespace App\Notifications;

use App\Mail\RevisionRequestSubmitted;
use App\Models\RevisionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RevisionRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected RevisionRequest $revisionRequest
    ) {
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
    public function toMail(object $notifiable): RevisionRequestSubmitted
    {
        return (new RevisionRequestSubmitted($this->revisionRequest, $notifiable))
            ->onQueue('emails');
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
            'document_name' => $this->revisionRequest->document ? $this->revisionRequest->document->fileName : 'N/A',
            'client_name' => $this->revisionRequest->requestedBy->fullName,
            'source_type' => $this->revisionRequest->source_type,
            'source_description' => $this->revisionRequest->getSourceDescription(),
            'reason' => substr($this->revisionRequest->reason, 0, 100),
            'message' => 'New revision request from ' . $this->revisionRequest->requestedBy->fullName,
            'action_url' => route('admin.revisions.show', $this->revisionRequest->id)
        ];
    }
}
