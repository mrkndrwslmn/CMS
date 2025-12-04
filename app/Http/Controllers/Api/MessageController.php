<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
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
     * Get all conversations for the authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        try {
            if ($user->isAdmin()) {
                // Admins see all conversations
                $conversations = Conversation::with(['project', 'client', 'lastMessage'])
                    ->active()
                    ->orderBy('last_message_at', 'desc')
                    ->paginate(20);
            } elseif ($user->isClient()) {
                // Clients see only their project conversations
                $conversations = Conversation::with(['project', 'lastMessage'])
                    ->where('client_id', $user->id)
                    ->active()
                    ->orderBy('last_message_at', 'desc')
                    ->paginate(20);
            } else {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->json([
                'success' => true,
                'conversations' => $conversations,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching conversations', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch conversations'
            ], 500);
        }
    }

    /**
     * Get messages for a specific project conversation
     */
    public function show(Request $request, int $projectId): JsonResponse
    {
        $user = Auth::user();

        try {
            $project = Project::findOrFail($projectId);

            // Check authorization
            if (!$user->isAdmin() && $project->client_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get or create conversation
            $conversation = Conversation::getOrCreateForProject($projectId);

            // Get messages with pagination
            $messages = Message::with(['sender:id,fullName,profilePic,role'])
                ->forProject($projectId)
                ->orderBy('created_at', 'asc')
                ->paginate(50);

            // Mark messages as read
            $this->markConversationAsRead($conversation, $user);

            return response()->json([
                'success' => true,
                'conversation' => $conversation,
                'project' => [
                    'id' => $project->id,
                    'title' => $project->title,
                    'client' => [
                        'id' => $project->client->id,
                        'name' => $project->client->fullName,
                    ],
                ],
                'messages' => $messages,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching conversation', [
                'project_id' => $projectId,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch conversation'
            ], 500);
        }
    }

    /**
     * Send a new message
     */
    public function store(Request $request, int $projectId): JsonResponse
    {
        $user = Auth::user();

        try {
            $project = Project::findOrFail($projectId);

            // Check authorization
            if (!$user->isAdmin() && $project->client_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

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
            
            $result = $this->messagingService->sendDirectMessage(
                $user,
                $project,
                $request->message,
                $attachments
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], $result['error'] === 'Unauthorized' ? 403 : 400);
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'conversation' => $result['conversation'],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error sending message', [
                'project_id' => $projectId,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message'
            ], 500);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request, int $projectId): JsonResponse
    {
        $user = Auth::user();

        try {
            $project = Project::findOrFail($projectId);

            // Check authorization
            if (!$user->isAdmin() && $project->client_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $conversation = Conversation::getOrCreateForProject($projectId);
            
            // Mark all unread messages in this conversation as read
            Message::forProject($projectId)
                ->where('recipient_id', $user->id)
                ->unread()
                ->update([
                    'is_read' => true,
                    'status' => 'read',
                    'read_at' => now(),
                ]);

            // Reset unread count
            $conversation->resetUnreadCount($user);

            return response()->json([
                'success' => true,
                'message' => 'Messages marked as read'
            ]);

        } catch (\Exception $e) {
            Log::error('Error marking messages as read', [
                'project_id' => $projectId,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark messages as read'
            ], 500);
        }
    }

    /**
     * Update FCM token for push notifications
     */
    public function updateFcmToken(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->updateFcmToken($request->token);

            return response()->json([
                'success' => true,
                'message' => 'FCM token updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating FCM token', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to update FCM token'
            ], 500);
        }
    }

    /**
     * Get unread message count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            $count = $this->messagingService->getDirectMessagesUnreadCount($user);

            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting unread count', [
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
     * Delete a message (soft delete)
     */
    public function destroy(Request $request, int $messageId): JsonResponse
    {
        $user = Auth::user();

        try {
            $message = Message::findOrFail($messageId);

            // Only sender or admin can delete
            if ($message->sender_id !== $user->id && !$user->isAdmin()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting message', [
                'message_id' => $messageId,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete message'
            ], 500);
        }
    }

    /**
     * Search messages across conversations
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $user = Auth::user();

        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|min:2|max:100',
                'project_id' => 'nullable|integer|exists:projects,id',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'per_page' => 'nullable|integer|min:5|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $request->input('query');
            $projectId = $request->input('project_id');

            // Verify project access if filtering by project
            if ($projectId) {
                $project = Project::find($projectId);
                if (!$project || (!$user->isAdmin() && $project->client_id !== $user->id)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'You do not have access to this project'
                    ], 403);
                }
            }

            // Use MessagingService to search
            $filters = [
                'project_id' => $projectId,
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
                'per_page' => $request->input('per_page', 20),
            ];

            $messages = $this->messagingService->searchMessages($user, $query, $filters);

            // Add highlight snippets to results
            $messages->getCollection()->transform(function ($message) use ($query) {
                $message->highlight = $this->messagingService->createHighlightSnippet($message->message, $query);
                return $message;
            });

            return response()->json([
                'success' => true,
                'query' => $query,
                'messages' => $messages,
                'total' => $messages->total(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching messages', [
                'user_id' => $user->id,
                'query' => $request->input('query'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to search messages'
            ], 500);
        }
    }

    /**
     * Helper method to mark conversation as read
     */
    protected function markConversationAsRead(Conversation $conversation, User $user): void
    {
        Message::where('conversation_id', $conversation->conversation_id)
            ->where('recipient_id', $user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'status' => 'read',
                'read_at' => now(),
            ]);

        $conversation->resetUnreadCount($user);
    }
}
