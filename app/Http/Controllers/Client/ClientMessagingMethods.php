<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

/**
 * Add these methods to ClientController.php
 */
trait ClientMessagingMethods
{
    /**
     * Show all messaging conversations for client
     */
    public function messages()
    {
        $user = Auth::user();
        
        $conversations = Conversation::with(['project', 'lastMessage'])
            ->where('client_id', $user->id)
            ->active()
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('client.messages.index', compact('conversations'));
    }

    /**
     * Show messages for a specific project
     */
    public function showMessages(Project $project)
    {
        $user = Auth::user();
        
        // Ensure client can only view their own project messages
        if ($project->client_id !== $user->id) {
            abort(403, 'Unauthorized access to this conversation.');
        }
        
        $conversation = Conversation::getOrCreateForProject($project->id);

        return view('client.messages.show', compact('project', 'conversation'));
    }
}
