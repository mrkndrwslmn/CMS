<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Conversation;
use App\Models\GroupChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Add these methods to AdminController.php
 */
trait AdminMessagingMethods
{
    /**
     * Show all messaging conversations
     */
    public function messages(Request $request)
    {
        $searchTerm = $request->input('search');
        $statusFilter = $request->input('status', 'all');
        $projectStatusFilter = $request->input('project_status', 'all');
        $sortBy = $request->input('sort', 'latest');

        // Build conversations query
        $conversationsQuery = Conversation::with(['project', 'client', 'lastMessage'])
            ->active();

        // Build group chats query
        $groupChatsQuery = GroupChat::with(['project', 'lastMessage.sender', 'members'])
            ->withCount('members');

        // Search filter
        if ($searchTerm) {
            $conversationsQuery->where(function($q) use ($searchTerm) {
                $q->whereHas('project', function($pq) use ($searchTerm) {
                    $pq->where('title', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('client', function($cq) use ($searchTerm) {
                    $cq->where('fullName', 'like', "%{$searchTerm}%")
                       ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('lastMessage', function($mq) use ($searchTerm) {
                    $mq->where('message', 'like', "%{$searchTerm}%");
                });
            });

            $groupChatsQuery->where(function($q) use ($searchTerm) {
                $q->whereHas('project', function($pq) use ($searchTerm) {
                    $pq->where('title', 'like', "%{$searchTerm}%");
                })
                ->orWhere('name', 'like', "%{$searchTerm}%")
                ->orWhereHas('lastMessage', function($mq) use ($searchTerm) {
                    $mq->where('message', 'like', "%{$searchTerm}%");
                });
            });
        }

        // Status filter (unread/read)
        if ($statusFilter === 'unread') {
            $conversationsQuery->where('unread_count_admin', '>', 0);
        } elseif ($statusFilter === 'read') {
            $conversationsQuery->where('unread_count_admin', 0);
        }

        // Project status filter
        if ($projectStatusFilter !== 'all') {
            $conversationsQuery->whereHas('project', function($pq) use ($projectStatusFilter) {
                $pq->where('status', $projectStatusFilter);
            });
            $groupChatsQuery->whereHas('project', function($pq) use ($projectStatusFilter) {
                $pq->where('status', $projectStatusFilter);
            });
        }

        // Sorting
        if ($sortBy === 'oldest') {
            $conversationsQuery->orderBy('last_message_at', 'asc');
            $groupChatsQuery->orderBy('last_message_at', 'asc');
        } elseif ($sortBy === 'unread') {
            $conversationsQuery->orderByDesc('unread_count_admin')->orderByDesc('last_message_at');
            $groupChatsQuery->orderByDesc('last_message_at');
        } else {
            $conversationsQuery->orderBy('last_message_at', 'desc');
            $groupChatsQuery->orderBy('last_message_at', 'desc');
        }

        $conversations = $conversationsQuery->paginate(20)->withQueryString();

        // Get group chats
        $groupChats = $groupChatsQuery->get();

        // Add unread count for each group chat and filter by unread if needed
        $groupChats->transform(function ($chat) {
            $chat->my_unread_count = $chat->getUnreadCountForMember(Auth::user());
            return $chat;
        });

        // Filter group chats by unread status after adding the count
        if ($statusFilter === 'unread') {
            $groupChats = $groupChats->filter(fn($chat) => $chat->my_unread_count > 0);
        } elseif ($statusFilter === 'read') {
            $groupChats = $groupChats->filter(fn($chat) => $chat->my_unread_count === 0);
        }

        return view('admin.messages.index', compact('conversations', 'groupChats'));
    }

    /**
     * Show messages for a specific project
     */
    public function showMessages(Project $project)
    {
        $conversation = Conversation::getOrCreateForProject($project->id);
        $groupChat = GroupChat::getOrCreateForProject($project->id);
        
        // Eager load members for group chat
        if ($groupChat) {
            $groupChat->load('members:id,fullName,profilePic,role');
        }

        return view('admin.messages.show', compact('project', 'conversation', 'groupChat'));
    }
}
