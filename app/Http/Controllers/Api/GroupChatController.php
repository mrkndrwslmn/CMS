<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupChat;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Services\FirebaseService;
use App\Services\MessagingService;
use App\Services\CloudflareR2Service;
use App\Traits\ValidatesDocuments;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GroupChatController extends Controller
{
    use ValidatesDocuments;
    
    protected FirebaseService $firebaseService;
    protected MessagingService $messagingService;

    public function __construct(FirebaseService $firebaseService, MessagingService $messagingService)
    {
        $this->firebaseService = $firebaseService;
        $this->messagingService = $messagingService;
    }

    /**
     * Get all group chats for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            // Clients cannot access group chats
            if ($user->isClient()) {
                return response()->json(['error' => 'Unauthorized. Group chats are only for admins and adiutors.'], 403);
            }

            if ($user->isAdmin()) {
                // Admins see all group chats
                $groupChats = GroupChat::with(['project', 'lastMessage.sender:id,fullName,profilePic'])
                    ->withCount('members')
                    ->orderBy('last_message_at', 'desc')
                    ->paginate(20);
            } else {
                // Adiutors see only group chats they're members of
                $groupChats = $user->groupChats()
                    ->with(['project', 'lastMessage.sender:id,fullName,profilePic'])
                    ->withCount('members')
                    ->orderBy('last_message_at', 'desc')
                    ->paginate(20);
            }

            // Add unread count for each chat
            $groupChats->getCollection()->transform(function ($chat) use ($user) {
                $chat->my_unread_count = $chat->getUnreadCountForMember($user);
                return $chat;
            });

            return response()->json([
                'success' => true,
                'group_chats' => $groupChats,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching group chats', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch group chats'
            ], 500);
        }
    }

    /**
     * Get messages for a specific group chat
     */
    public function show(Request $request, int $groupChatId): JsonResponse
    {
        $user = Auth::user();

        try {
            $groupChat = GroupChat::with(['project', 'members:id,fullName,profilePic,role'])
                ->findOrFail($groupChatId);

            // Check authorization
            if (!$groupChat->canAccess($user)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get messages with pagination
            $messages = Message::with(['sender:id,fullName,profilePic,role'])
                ->forGroupChat($groupChatId)
                ->orderBy('created_at', 'asc')
                ->paginate(50);

            // Mark messages as read for this user
            $groupChat->resetUnreadForMember($user);

            return response()->json([
                'success' => true,
                'group_chat' => $groupChat,
                'project' => [
                    'id' => $groupChat->project->id,
                    'title' => $groupChat->project->title,
                ],
                'members' => $groupChat->members,
                'can_send_messages' => $groupChat->canSendMessages($user),
                'messages' => $messages,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching group chat', [
                'group_chat_id' => $groupChatId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch group chat'
            ], 500);
        }
    }

    /**
     * Send a message to a group chat
     */
    public function store(Request $request, GroupChat $groupChat): JsonResponse
    {
        $user = Auth::user();

        try {
            // Validate request
            $extensions = $this->getAllowedExtensions();
            $maxSize = $this->getMaxFileSize();
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:5000',
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => "file|max:{$maxSize}|mimes:{$extensions}",
            ], [
                'attachments.max' => 'You can upload a maximum of 5 files.',
                'attachments.*.mimes' => 'Unsupported file type. ' . $this->getHumanReadableFileTypes() . ' are allowed.',
                'attachments.*.max' => 'Each file must be less than 10MB.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Use MessagingService to send the message
            $attachments = $request->hasFile('attachments') ? $request->file('attachments') : [];

            $result = $this->messagingService->sendGroupMessage(
                $user,
                $groupChat,
                $request->message,
                $attachments
            );

            if (!$result['success']) {
                $statusCode = $result['error'] === 'Unauthorized' ? 403 : 400;
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], $statusCode);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'group_chat' => $result['group_chat'],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error sending group chat message', [
                'group_chat_id' => $groupChat->id ?? null,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to send message'
            ], 500);
        }
    }

    /**
     * Archive a group chat (admin only)
     */
    public function archive(Request $request, int $groupChatId): JsonResponse
    {
        $user = Auth::user();

        try {
            if (!$user->isAdmin()) {
                return response()->json(['error' => 'Only admins can archive group chats'], 403);
            }

            $groupChat = GroupChat::findOrFail($groupChatId);

            if ($groupChat->status === 'archived') {
                return response()->json([
                    'success' => false,
                    'error' => 'This group chat is already archived'
                ], 400);
            }

            $groupChat->archive($user);

            return response()->json([
                'success' => true,
                'message' => 'Group chat archived successfully',
                'group_chat' => $groupChat,
            ]);

        } catch (\Exception $e) {
            Log::error('Error archiving group chat', [
                'group_chat_id' => $groupChatId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to archive group chat'
            ], 500);
        }
    }

    /**
     * Reopen an archived group chat (admin only)
     */
    public function reopen(Request $request, int $groupChatId): JsonResponse
    {
        $user = Auth::user();

        try {
            if (!$user->isAdmin()) {
                return response()->json(['error' => 'Only admins can reopen group chats'], 403);
            }

            $groupChat = GroupChat::findOrFail($groupChatId);

            if ($groupChat->status === 'open') {
                return response()->json([
                    'success' => false,
                    'error' => 'This group chat is already open'
                ], 400);
            }

            $groupChat->reopen($user);

            return response()->json([
                'success' => true,
                'message' => 'Group chat reopened successfully',
                'group_chat' => $groupChat,
            ]);

        } catch (\Exception $e) {
            Log::error('Error reopening group chat', [
                'group_chat_id' => $groupChatId,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to reopen group chat'
            ], 500);
        }
    }

    /**
     * Get total unread count across all group chats for the authenticated user
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            $count = $this->messagingService->getGroupMessagesUnreadCount($user);

            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting group chat unread count', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to get unread count'
            ], 500);
        }
    }

    /**
     * Mark group chat messages as read
     */
    public function markAsRead(Request $request, int $groupChatId): JsonResponse
    {
        $user = Auth::user();

        try {
            $groupChat = GroupChat::findOrFail($groupChatId);

            if (!$groupChat->canAccess($user)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $groupChat->resetUnreadForMember($user);

            return response()->json([
                'success' => true,
                'message' => 'Messages marked as read'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking group chat as read', [
                'group_chat_id' => $groupChatId,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to mark messages as read'
            ], 500);
        }
    }
}
