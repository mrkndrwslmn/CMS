<?php

namespace App\Listeners;

use App\Events\ServiceRequestApproved;
use App\Models\Conversation;
use App\Models\Message;

class CreateConversationOnRequestApproval
{
    public function handle(ServiceRequestApproved $event): void
    {
        $serviceRequest = $event->serviceRequest;
        $admin = $event->approvedBy;

        // Get the project that was just created
        $project = $serviceRequest->project()->first();
        
        if (!$project) {
            return; // Exit if no project
        }

        $conversationId = 'proj_' . $project->id;

        // Check if conversation already exists
        $conversation = Conversation::where('conversation_id', $conversationId)->first();

        if (!$conversation) {
            // Create conversation
            $conversation = Conversation::create([
                'conversation_id' => $conversationId,
                'project_id' => $project->id,
                'client_id' => $serviceRequest->client_id,
                'unread_count_client' => 1,
                'unread_count_admin' => 0,
            ]);
        }

        // Create initial approval message
        Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $admin->id,
            'recipient_id' => $serviceRequest->client_id,
            'project_id' => $project->id,
            'message' => "✅ **Your request has been approved!**\n\n" .
                        "**Project:** {$serviceRequest->project_name}\n" .
                        "**Approved Budget:** PHP " . number_format($serviceRequest->approved_budget, 2) . "\n\n" .
                        "You can now proceed with payment. We're excited to work with you!",
            'message_type' => 'system',
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        // Update conversation's last message
        $conversation->update([
            'last_message_at' => now(),
        ]);
    }
}