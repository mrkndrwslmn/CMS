<?php

namespace App\Notifications;

use App\Models\RevisionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RevisionRejectedNotification extends Notification implements ShouldQueue
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
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $document = $this->revisionRequest->document;
        
        return [
            'type' => 'revision_rejected',
            'revision_request_id' => $this->revisionRequest->id,
            'document_id' => $this->revisionRequest->document_id,
            'document_name' => $document->fileName,
            'source_description' => $this->revisionRequest->getSourceDescription(),
            'admin_notes' => $this->revisionRequest->admin_notes,
            'message' => 'Your revision request was not approved for: ' . $document->fileName,
            'action_url' => route('client.revisions.show', $this->revisionRequest->id)
        ];
    }
}
