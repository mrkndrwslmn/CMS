<?php

namespace App\Notifications;

use App\Models\RevisionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RevisionApprovedNotification extends Notification
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
        $sourceDescription = $this->revisionRequest->getSourceDescription();
        
        // Different messages for adiutor vs client
        if ($notifiable->role === 'adiutor') {
            $message = 'Revision request approved for: ' . $sourceDescription;
            $actionUrl = route('adiutor.revisions.show', $this->revisionRequest->id);
        } else {
            $message = 'Your revision request has been approved for: ' . $sourceDescription;
            $actionUrl = route('client.revisions.show', $this->revisionRequest->id);
        }

        return [
            'type' => 'revision_approved',
            'revision_request_id' => $this->revisionRequest->id,
            'document_id' => $this->revisionRequest->document_id,
            'document_name' => $document ? $document->fileName : 'N/A',
            'source_type' => $this->revisionRequest->source_type,
            'source_description' => $sourceDescription,
            'task_id' => $this->revisionRequest->task_id,
            'task_reopened' => $this->revisionRequest->isTaskBased(),
            'requested_due_date' => $this->revisionRequest->requested_due_date?->format('Y-m-d'),
            'admin_notes' => $this->revisionRequest->admin_notes,
            'message' => $message,
            'action_url' => $actionUrl
        ];
    }
}
