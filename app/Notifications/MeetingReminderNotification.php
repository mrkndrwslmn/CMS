<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class MeetingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Meeting $meeting;
    protected string $reminderType;

    /**
     * Create a new notification instance.
     * 
     * @param Meeting $meeting The meeting to remind about
     * @param string $reminderType Type of reminder: '1_hour' or '15_minutes'
     */
    public function __construct(Meeting $meeting, string $reminderType = '1_hour')
    {
        $this->meeting = $meeting;
        $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $scheduledDateTime = $this->meeting->getScheduledDateTime();
        $timeLabel = $this->reminderType === '1_hour' ? 'in 1 hour' : 'in 15 minutes';
        
        $mailMessage = (new MailMessage)
            ->subject("Meeting Reminder: {$this->meeting->title} - Starting {$timeLabel}")
            ->greeting("Hello {$notifiable->fullName}!")
            ->line("This is a reminder that your meeting is starting {$timeLabel}.")
            ->line("**Meeting:** {$this->meeting->title}")
            ->line("**Project:** {$this->meeting->project->title}")
            ->line("**Scheduled:** " . $scheduledDateTime->format('F j, Y \a\t g:i A'));
        
        if ($this->meeting->zoom_join_url) {
            $mailMessage->action('Join Zoom Meeting', $this->meeting->zoom_join_url);
            
            if ($this->meeting->zoom_password) {
                $mailMessage->line("**Zoom Password:** {$this->meeting->zoom_password}");
            }
        }
        
        return $mailMessage->line('Thank you for using our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $scheduledDateTime = $this->meeting->getScheduledDateTime();
        
        return [
            'type' => 'meeting_reminder',
            'reminder_type' => $this->reminderType,
            'meeting_id' => $this->meeting->id,
            'meeting_title' => $this->meeting->title,
            'project_id' => $this->meeting->project_id,
            'project_title' => $this->meeting->project->title,
            'scheduled_at' => $scheduledDateTime?->toIso8601String(),
            'zoom_join_url' => $this->meeting->zoom_join_url,
            'message' => $this->reminderType === '1_hour' 
                ? "Your meeting \"{$this->meeting->title}\" starts in 1 hour"
                : "Your meeting \"{$this->meeting->title}\" starts in 15 minutes",
        ];
    }
}

