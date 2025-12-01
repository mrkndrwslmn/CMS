<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Conversation;
use App\Models\GroupChat;
use Illuminate\Support\Facades\Auth;

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

        // Get group chats
        $groupChats = GroupChat::with(['project', 'lastMessage.sender', 'members'])
            ->withCount('members')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Add unread count for each group chat
        $groupChats->transform(function ($chat) {
            $chat->my_unread_count = $chat->getUnreadCountForMember(Auth::user());
            return $chat;
        });

        return view('admin.messages.index', compact('conversations', 'groupChats'));
    }

    /**
     * Show messages for a specific project
     */
    public function showMessages(Project $project)
    {
        $conversation = Conversation::getOrCreateForProject($project->id);
        $groupChat = GroupChat::getOrCreateForProject($project->id);

        return view('admin.messages.show', compact('project', 'conversation', 'groupChat'));
    }
}
