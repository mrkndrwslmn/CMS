<?php

namespace App\Notifications;

use App\Models\RevisionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RevisionCompletedNotification extends Notification
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
        // Determine the source name
        $sourceName = '';
        if ($this->revisionRequest->document) {
            $sourceName = $this->revisionRequest->document->fileName;
        } elseif ($this->revisionRequest->task) {
            $sourceName = $this->revisionRequest->task->taskTitle;
        } elseif ($this->revisionRequest->project) {
            $sourceName = $this->revisionRequest->project->title;
        }

        $message = 'Your revision request has been completed: ' . $sourceName;
        
        // Determine action URL based on source type
        if ($this->revisionRequest->document_id) {
            $actionUrl = route('client.documents.show', $this->revisionRequest->document_id);
        } elseif ($this->revisionRequest->project_id) {
            $actionUrl = route('client.projects.show', $this->revisionRequest->project_id);
        } else {
            $actionUrl = route('client.revisions.show', $this->revisionRequest->id);
        }

        return [
            'type' => 'revision_completed',
            'revision_request_id' => $this->revisionRequest->id,
            'document_id' => $this->revisionRequest->document_id,
            'task_id' => $this->revisionRequest->task_id,
            'project_id' => $this->revisionRequest->project_id,
            'source_name' => $sourceName,
            'source_type' => $this->revisionRequest->source_type,
            'completed_by' => $this->revisionRequest->completedBy?->fullName,
            'completed_at' => $this->revisionRequest->completed_at?->format('Y-m-d H:i'),
            'message' => $message,
            'action_url' => $actionUrl
        ];
    }
}
