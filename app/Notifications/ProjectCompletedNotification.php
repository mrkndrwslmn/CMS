<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $projectId;
    protected $projectTitle;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $projectId, string $projectTitle)
    {
        $this->projectId = $projectId;
        $this->projectTitle = $projectTitle;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Project Has Been Completed!')
            ->greeting('Hello ' . $notifiable->fullName . '!')
            ->line("Great news! Your project **{$this->projectTitle}** has been completed.")
            ->line('Our team has finished working on your project and it is now ready for your review.')
            ->action('View Project', route('client.projects.show', $this->projectId))
            ->line('If you have any questions or feedback, please don\'t hesitate to contact us.')
            ->line('Thank you for choosing our services!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'project_completed',
            'title' => 'Project Completed',
            'message' => "Your project '{$this->projectTitle}' has been completed!",
            'action_url' => route('client.projects.show', $this->projectId),
            'project_id' => $this->projectId,
            'project_title' => $this->projectTitle,
            'completion_date' => now()->toDateTimeString(),
        ];
    }
}
