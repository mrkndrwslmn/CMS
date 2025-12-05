<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliverableRejectedNotification extends Notification
{
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
            ->subject('Your Deliverable Needs Revision')
            ->greeting('Hello ' . $notifiable->fullName . ',')
            ->line('Your deliverable "' . $this->document->fileName . '" for the task "' . $taskTitle . '" has been reviewed.')
            ->line('**Feedback:** ' . $this->reason)
            ->line('Please review the feedback and upload a revised version.')
            ->action('View Task', url('/adiutor/tasks/' . $this->document->taskID))
            ->line('If you have any questions, please contact the administrator.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deliverable_rejected',
            'document_id' => $this->document->documentID,
            'document_name' => $this->document->fileName,
            'task_id' => $this->document->taskID,
            'task_title' => $this->document->task?->taskTitle,
            'rejection_reason' => $this->reason,
            'message' => 'Your deliverable "' . $this->document->fileName . '" needs revision.',
        ];
    }
}
