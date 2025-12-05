<?php

namespace App\Notifications;

use App\Models\TimeEntry;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbandonedTimerNotification extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public TimeEntry $timeEntry
    ) {}

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
        $runningHours = round($this->timeEntry->start_time->diffInMinutes(now()) / 60, 2);
        $taskName = $this->timeEntry->task?->taskName ?? 'Unknown Task';
        $projectTitle = $this->timeEntry->project?->title ?? 'Unknown Project';

        return (new MailMessage)
            ->subject('⏰ Timer Running - Action Required')
            ->greeting("Hello {$notifiable->fullName}!")
            ->line("You have a timer that has been running for **{$runningHours} hours**.")
            ->line("**Task:** {$taskName}")
            ->line("**Project:** {$projectTitle}")
            ->line("**Started:** {$this->timeEntry->start_time->format('M d, Y \\a\\t g:i A')}")
            ->line('If you forgot to stop your timer, please do so now to ensure accurate time tracking.')
            ->action('View Timer', url('/adiutor/time-tracking'))
            ->line('If this timer is intentional, you can ignore this notification.')
            ->salutation('Best regards, Treis Adiutor');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $runningHours = round($this->timeEntry->start_time->diffInMinutes(now()) / 60, 2);

        return [
            'type' => 'abandoned_timer',
            'title' => 'Timer Running Too Long',
            'message' => "Your timer for \"{$this->timeEntry->task?->taskName}\" has been running for {$runningHours} hours.",
            'time_entry_id' => $this->timeEntry->id,
            'task_id' => $this->timeEntry->task_id,
            'project_id' => $this->timeEntry->project_id,
            'running_hours' => $runningHours,
            'started_at' => $this->timeEntry->start_time->toIso8601String(),
            'action_url' => '/adiutor/time-tracking',
        ];
    }

    /**
     * Get Firebase notification payload.
     */
    public function toFirebase(object $notifiable): array
    {
        $runningHours = round($this->timeEntry->start_time->diffInMinutes(now()) / 60, 2);

        return [
            'notification' => [
                'title' => '⏰ Timer Running',
                'body' => "Your timer has been running for {$runningHours} hours. Did you forget to stop it?",
            ],
            'data' => [
                'type' => 'abandoned_timer',
                'time_entry_id' => (string) $this->timeEntry->id,
                'click_action' => '/adiutor/time-tracking',
            ],
        ];
    }
}
