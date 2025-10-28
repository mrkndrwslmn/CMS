<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;

class ProjectAssigned extends BaseMailable
{
    public function __construct(
        public Project $project,
        public User $adiutor,
        public User $assignedBy
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        return 'New Project Assignment - ' . $this->project->title;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-assigned',
            with: [
                'project' => $this->project,
                'adiutor' => $this->adiutor,
                'assignedBy' => $this->assignedBy,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'projects';
    }
}