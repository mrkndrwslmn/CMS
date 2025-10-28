<?php

namespace App\Mail;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;

class NewServiceRequest extends BaseMailable
{
    public function __construct(
        public ServiceRequest $serviceRequest,
        public User $admin,
        public bool $isNewUser = false
    ) {
        parent::__construct();
    }

    protected function getSubject(): string
    {
        return 'New Service Request Submitted - ' . $this->serviceRequest->project_name;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service-request',
            with: [
                'serviceRequest' => $this->serviceRequest,
                'admin' => $this->admin,
                'isNewUser' => $this->isNewUser,
            ]
        );
    }

    public function getSenderType(): string
    {
        return 'requests';
    }
}
