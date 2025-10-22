<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Conversation;

/**
 * Add these methods to AdminController.php
 */
trait AdminMessagingMethods
{
    /**
     * Show all messaging conversations
     */
    public function messages()
    {
        $conversations = Conversation::with(['project', 'client', 'lastMessage'])
            ->active()
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return view('admin.messages.index', compact('conversations'));
    }

    /**
     * Show messages for a specific project
     */
    public function showMessages(Project $project)
    {
        $conversation = Conversation::getOrCreateForProject($project->id);

        return view('admin.messages.show', compact('project', 'conversation'));
    }
}
