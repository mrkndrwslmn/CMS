<?php

namespace App\Mail;

use App\Models\Meeting;
use App\Services\EmailSenderService;
use Illuminate\Mail\Mailables\Content;

class MeetingRejected extends BaseMailable
{
    public Meeting $meeting;

    /**
     * Specify the sender type for this email
     */
    protected string $senderType = EmailSenderService::SENDER_PROJECTS;

    /**
     * Create a new message instance.
     */
    public function __construct(Meeting $meeting)
    {
        $this->meeting = $meeting;
        
        parent::__construct();
    }

    /**
     * Get the subject line for the email
     */
    protected function getSubject(): string
    {
        return 'Meeting Request Declined - ' . $this->meeting->title;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting-rejected',
            with: [
                'meeting' => $this->meeting,
            ]
        );
    }
}
