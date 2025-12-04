<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FeedbackResponseNotification extends Notification
{
    use Queueable;

    protected Feedback $feedback;
    protected string $response;

    /**
     * Create a new notification instance.
     */
    public function __construct(Feedback $feedback, string $response)
    {
        $this->feedback = $feedback;
        $this->response = $response;
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
        $projectTitle = $this->feedback->project?->title ?? 'your project';
        
        return [
            'type' => 'feedback_response',
            'title' => 'Response to Your Feedback',
            'message' => "An admin has responded to your feedback for \"{$projectTitle}\".",
            'action_url' => route('client.feedback'),
            'feedback_id' => $this->feedback->id,
            'project_id' => $this->feedback->project_id,
            'response_preview' => \Str::limit($this->response, 100),
        ];
    }
}
