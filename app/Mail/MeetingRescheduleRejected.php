<?php

namespace App\Mail;

use App\Models\Meeting;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class MeetingRescheduleRejected extends BaseMailable
{
    public Meeting $meeting;
    public string $reason;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_PROJECTS;

    /**
     * Create a new message instance.
     */
    public function __construct(Meeting $meeting, string $reason = '')
    {
        $this->meeting = $meeting;
        $this->reason = $reason;
        
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return 'Meeting Time Declined by Client - ' . $this->meeting->title;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting-reschedule-rejected',
            with: [
                'meeting' => $this->meeting,
                'reason' => $this->reason,
            ]
        );
    }
}
