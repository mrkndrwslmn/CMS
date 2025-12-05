<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FileUploadedNotification extends Notification
{
    protected string $fileName;
    protected string $taskTitle;
    protected int $taskId;
    protected string $uploadedBy;
    protected bool $isDeliverable;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $fileName, string $taskTitle, int $taskId, string $uploadedBy, bool $isDeliverable = false)
    {
        $this->fileName = $fileName;
        $this->taskTitle = $taskTitle;
        $this->taskId = $taskId;
        $this->uploadedBy = $uploadedBy;
        $this->isDeliverable = $isDeliverable;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'file_uploaded',
            'file_name' => $this->fileName,
            'task_title' => $this->taskTitle,
            'task_id' => $this->taskId,
            'uploaded_by' => $this->uploadedBy,
            'is_deliverable' => $this->isDeliverable,
            'message' => $this->isDeliverable 
                ? "New deliverable '{$this->fileName}' uploaded for task '{$this->taskTitle}'"
                : "New file '{$this->fileName}' uploaded for task '{$this->taskTitle}'",
            'action_url' => route('adiutor.tasks.show', $this->taskId),
            'icon' => $this->isDeliverable ? 'package' : 'file',
            'color' => $this->isDeliverable ? 'success' : 'info'
        ];
    }
}