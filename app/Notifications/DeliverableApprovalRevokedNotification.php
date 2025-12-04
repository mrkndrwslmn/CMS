<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliverableApprovalRevokedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Document $document;
    protected string $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, string $reason)
    {
        $this->document = $document;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $taskTitle = $this->document->task?->taskTitle ?? 'Unknown Task';
        
        return (new MailMessage)
            ->subject('Deliverable Approval Revoked')
            ->greeting('Hello ' . $notifiable->fullName . ',')
            ->line('The approval for your deliverable "' . $this->document->fileName . '" for the task "' . $taskTitle . '" has been revoked.')
            ->line('**Reason:** ' . $this->reason)
            ->line('The deliverable is no longer visible to the client. Please address the issue and resubmit if needed.')
            ->action('View Task', url('/adiutor/tasks/' . $this->document->taskID))
            ->line('If you have any questions, please contact the administrator.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deliverable_approval_revoked',
            'document_id' => $this->document->documentID,
            'document_name' => $this->document->fileName,
            'task_id' => $this->document->taskID,
            'task_title' => $this->document->task?->taskTitle,
            'revocation_reason' => $this->reason,
            'message' => 'Approval revoked for deliverable: ' . $this->document->fileName,
        ];
    }
}
