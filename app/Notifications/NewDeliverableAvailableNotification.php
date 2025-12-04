<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDeliverableAvailableNotification extends Notification implements ShouldQueue
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
        $projectTitle = $this->document->task?->project?->title ?? 'Your Project';
        
        return (new MailMessage)
            ->subject('New Deliverable Available: ' . $this->document->fileName)
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line('A new deliverable is now available for you to review.')
            ->line('**Project:** ' . $projectTitle)
            ->line('**Task:** ' . $taskTitle)
            ->line('**Deliverable:** ' . $this->document->fileName)
            ->action('View Deliverable', url('/client/tasks/' . $this->document->taskID))
            ->line('Thank you for choosing our services!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_deliverable',
            'document_id' => $this->document->documentID,
            'document_name' => $this->document->fileName,
            'task_id' => $this->document->taskID,
            'task_title' => $this->document->task?->taskTitle,
            'project_title' => $this->document->task?->project?->title,
            'message' => 'New deliverable available: ' . $this->document->fileName,
        ];
    }
}
