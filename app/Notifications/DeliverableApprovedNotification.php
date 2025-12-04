<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliverableApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Document $document;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
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
            ->subject('Your Deliverable Has Been Approved')
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line('Your deliverable "' . $this->document->fileName . '" for the task "' . $taskTitle . '" has been approved.')
            ->line('The client can now view and download this deliverable.')
            ->action('View Task', url('/adiutor/tasks/' . $this->document->taskID))
            ->line('Thank you for your great work!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deliverable_approved',
            'document_id' => $this->document->documentID,
            'document_name' => $this->document->fileName,
            'task_id' => $this->document->taskID,
            'task_title' => $this->document->task?->taskTitle,
            'message' => 'Your deliverable "' . $this->document->fileName . '" has been approved.',
        ];
    }
}
