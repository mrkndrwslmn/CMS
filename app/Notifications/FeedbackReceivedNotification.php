<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FeedbackReceivedNotification extends Notification
{
    use Queueable;

    protected $projectId;
    protected $projectTitle;
    protected $feedbackId;
    protected $rating;
    protected $clientName;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $projectId, string $projectTitle, int $feedbackId, int $rating, string $clientName)
    {
        $this->projectId = $projectId;
        $this->projectTitle = $projectTitle;
        $this->feedbackId = $feedbackId;
        $this->rating = $rating;
        $this->clientName = $clientName;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'feedback_received',
            'title' => 'New Feedback Received',
            'message' => $this->clientName . ' left feedback for project: ' . $this->projectTitle,
            'action_url' => route('adiutor.projects.show', $this->projectId),
            'project_id' => $this->projectId,
            'feedback_id' => $this->feedbackId,
            'rating' => $this->rating,
        ];
    }
}
