<?php

namespace App\Notifications;

use App\Models\HourIncreaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HourIncreaseRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected HourIncreaseRequest $hourRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(HourIncreaseRequest $hourRequest)
    {
        $this->hourRequest = $hourRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $adiutorName = $this->hourRequest->adiutor->fullName ?? 'An adiutor';
        $projectName = $this->hourRequest->project->title ?? 'Unknown Project';
        $currentHours = $this->hourRequest->current_max_hours ?? 0;
        $requestedHours = $this->hourRequest->requested_max_hours ?? 0;

        return (new MailMessage)
            ->subject('New Hour Increase Request - ' . $projectName)
            ->greeting('Hello ' . $notifiable->fullName . ',')
            ->line("{$adiutorName} has submitted an hour increase request.")
            ->line("**Project:** {$projectName}")
            ->line("**Current Max Hours:** {$currentHours}")
            ->line("**Requested Hours:** {$requestedHours}")
            ->line("**Reason:** " . \Illuminate\Support\Str::limit($this->hourRequest->reason, 200))
            ->action('Review Request', route('admin.hour-requests.show', $this->hourRequest->id))
            ->line('Please review this request at your earliest convenience.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Hour Increase Request',
            'message' => ($this->hourRequest->adiutor->fullName ?? 'An adiutor') . 
                ' has requested additional hours for project: ' . 
                ($this->hourRequest->project->title ?? 'Unknown Project'),
            'action_url' => route('admin.hour-requests.show', $this->hourRequest->id),
            'hour_request_id' => $this->hourRequest->id,
            'adiutor_id' => $this->hourRequest->adiutor_id,
            'project_id' => $this->hourRequest->project_id,
            'current_hours' => $this->hourRequest->current_max_hours,
            'requested_hours' => $this->hourRequest->requested_max_hours,
        ];
    }
}
