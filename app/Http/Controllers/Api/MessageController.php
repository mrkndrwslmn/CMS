<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Project;
use App\Models\User;
use App\Services\FirebaseService;
use App\Services\CloudflareR2Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    protected FirebaseService $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
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
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:5000',
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|max:10240', // 10MB max per file
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get or create conversation
            $conversation = Conversation::getOrCreateForProject($projectId);

            // Determine recipient
            $recipientId = $user->isAdmin() ? $project->client_id : null;
            
            // If client is sending, find any admin to notify (you can customize this logic)
            if ($user->isClient()) {
                $recipientId = User::where('role', 'admin')->where('status', 'active')->first()?->id;
            }

            if (!$recipientId) {
                return response()->json([
                    'success' => false,
                    'error' => 'No recipient available'
                ], 400);
            }

            // Handle attachments
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                $r2Service = new CloudflareR2Service();
                
                foreach ($request->file('attachments') as $file) {
                    // Upload to R2 in message-attachments directory
                    $uploadResult = $r2Service->uploadFile($file, 'message-attachments', null, [
                        'type' => 'message_attachment',
                        'user_id' => Auth::id(),
                    ]);
                    
                    if ($uploadResult['success']) {
                        $attachmentPaths[] = [
                            'name' => $uploadResult['original_name'],
                            'path' => $uploadResult['path'],
                            'url' => $uploadResult['url'], // Include URL for direct access
                            'size' => $uploadResult['size'],
                            'mime_type' => $uploadResult['mime_type'],
                        ];
                    }
                }
            }

            // Create message
            $message = Message::create([
                'conversation_id' => $conversation->conversation_id,
                'sender_id' => $user->id,
                'recipient_id' => $recipientId,
                'project_id' => $projectId,
                'message' => $request->message,
                'message_type' => 'project',
                'attachments' => !empty($attachmentPaths) ? $attachmentPaths : null,
                'status' => 'sent',
            ]);

            // Update conversation
            $conversation->updateLastMessage($message);
            $conversation->incrementUnreadCount($user->isClient());

            // Load sender relationship
            $message->load('sender:id,fullName,profilePic,role');

            // Send push notification
            $recipient = User::find($recipientId);
            if ($recipient) {
                $this->firebaseService->sendNewMessageNotification($message);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'conversation' => $conversation,
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
            if ($user->isAdmin()) {
                $count = Conversation::sum('unread_count_admin');
            } elseif ($user->isClient()) {
                $count = Conversation::where('client_id', $user->id)
                    ->sum('unread_count_client');
            } else {
                $count = 0;
            }

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
