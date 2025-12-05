<?php

namespace App\Notifications;

use App\Models\HourIncreaseRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HourIncreaseReviewedNotification extends Notification
{
    protected HourIncreaseRequest $hourRequest;
    protected string $status;

    /**
     * Create a new notification instance.
     *
     * @param HourIncreaseRequest $hourRequest
     * @param string $status 'approved' or 'rejected'
     */
    public function __construct(HourIncreaseRequest $hourRequest, string $status)
    {
        $this->hourRequest = $hourRequest;
        $this->status = $status;
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
        $projectName = $this->hourRequest->project->title ?? 'Unknown Project';
        $isApproved = $this->status === 'approved';
        
        $message = (new MailMessage)
            ->subject('Hour Increase Request ' . ucfirst($this->status) . ' - ' . $projectName)
            ->greeting('Hello ' . $notifiable->fullName . ',');

        if ($isApproved) {
            $message->line('Great news! Your hour increase request has been approved.')
                ->line("**Project:** {$projectName}")
                ->line("**Approved Hours:** {$this->hourRequest->approved_max_hours}")
                ->line('You can now continue tracking time on this project with the updated hour limit.');
        } else {
            $message->line('Your hour increase request has been reviewed.')
                ->line("**Project:** {$projectName}")
                ->line("**Status:** Rejected")
                ->line("**Review Notes:** " . ($this->hourRequest->review_notes ?? 'No notes provided.'))
                ->line('If you have questions, please contact your project administrator.');
        }

        return $message->action('View Request', route('adiutor.hour-requests.show', $this->hourRequest->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $isApproved = $this->status === 'approved';
        $projectName = $this->hourRequest->project->title ?? 'Unknown Project';

        return [
            'title' => 'Hour Increase Request ' . ucfirst($this->status),
            'message' => $isApproved
                ? "Your hour increase request for {$projectName} has been approved. New max hours: {$this->hourRequest->approved_max_hours}"
                : "Your hour increase request for {$projectName} has been rejected.",
            'action_url' => route('adiutor.hour-requests.show', $this->hourRequest->id),
            'hour_request_id' => $this->hourRequest->id,
            'project_id' => $this->hourRequest->project_id,
            'status' => $this->status,
            'approved_hours' => $this->hourRequest->approved_max_hours,
            'review_notes' => $this->hourRequest->review_notes,
        ];
    }
}
