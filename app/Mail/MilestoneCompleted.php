<?php

namespace App\Mail;

use App\Models\ProjectMilestone;
use Illuminate\Mail\Mailables\Content;

class MilestoneCompleted extends BaseMailable
{
    public function __construct(
        public ProjectMilestone $milestone
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        return 'Project Phase Completed - ' . $this->milestone->phase_name;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.milestone-completed',
            with: [
                'milestone' => $this->milestone,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'projects';
    }
}