<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Add these methods to ClientController.php
 */
trait ClientMessagingMethods
{
    /**
     * Show all messaging conversations for client
     */
    public function messages(Request $request)
    {
        $user = Auth::user();
        
        $query = Conversation::with(['project', 'lastMessage'])
            ->where('client_id', $user->id)
            ->active();

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('project', function($pq) use ($searchTerm) {
                    $pq->where('title', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('lastMessage', function($mq) use ($searchTerm) {
                    $mq->where('message', 'like', "%{$searchTerm}%");
                });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $status = $request->input('status');
            if ($status === 'unread') {
                $query->where('unread_count_client', '>', 0);
            } elseif ($status === 'read') {
                $query->where('unread_count_client', 0);
            }
        }

        // Project status filter
        if ($request->filled('project_status') && $request->input('project_status') !== 'all') {
            $projectStatus = $request->input('project_status');
            $query->whereHas('project', function($pq) use ($projectStatus) {
                $pq->where('status', $projectStatus);
            });
        }

        // Sorting
        $sortBy = $request->input('sort', 'latest');
        if ($sortBy === 'oldest') {
            $query->orderBy('last_message_at', 'asc');
        } elseif ($sortBy === 'unread') {
            $query->orderByDesc('unread_count_client')->orderByDesc('last_message_at');
        } else {
            $query->orderBy('last_message_at', 'desc');
        }

        $conversations = $query->paginate(20)->withQueryString();

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
