<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\GroupChat;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * MessagingService
 * 
 * Centralized service for handling messaging operations across
 * direct messages and group chats. Extracts common logic from
 * MessageController and GroupChatController.
 */
class MessagingService
{
    protected CloudflareR2Service $r2Service;
    protected FirebaseService $firebaseService;

    public function __construct(
        CloudflareR2Service $r2Service,
        FirebaseService $firebaseService
    ) {
        $this->r2Service = $r2Service;
        $this->firebaseService = $firebaseService;
    }

    /**
     * Send a direct message to a project conversation
     * 
     * @param User $sender
     * @param Project $project
     * @param string $content
     * @param array $attachments
     * @return array{success: bool, message?: Message, error?: string}
     */
    public function sendDirectMessage(
        User $sender,
        Project $project,
        string $content,
        array $attachments = []
    ): array {
        try {
            // Validate project is messageable
            $validation = $this->validateProjectForMessaging($project);
            if (!$validation['valid']) {
                return ['success' => false, 'error' => $validation['error']];
            }

            // Get or create conversation
            $conversation = Conversation::getOrCreateForProject($project->id);

            // Determine recipient
            $recipientId = $this->determineRecipient($sender, $project);
            if (!$recipientId) {
                return ['success' => false, 'error' => 'No recipient available'];
            }

            // Handle attachments
            $attachmentData = $this->processAttachments($attachments, 'message-attachments', [
                'type' => 'message_attachment',
                'user_id' => $sender->id,
            ]);

            // Create message
            $message = Message::create([
                'conversation_id' => $conversation->conversation_id,
                'sender_id' => $sender->id,
                'recipient_id' => $recipientId,
                'project_id' => $project->id,
                'message' => $content,
                'message_type' => 'project',
                'attachments' => !empty($attachmentData) ? $attachmentData : null,
                'status' => 'sent',
            ]);

            // Update conversation
            $conversation->updateLastMessage($message);
            $conversation->incrementUnreadCount($sender->isClient());

            // Load relationships
            $message->load('sender:id,fullName,profilePic,role');

            // Send push notification
            $this->sendDirectMessageNotification($message);

            return [
                'success' => true,
                'message' => $message,
                'conversation' => $conversation,
            ];

        } catch (\Exception $e) {
            Log::error('MessagingService::sendDirectMessage failed', [
                'sender_id' => $sender->id,
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => 'Failed to send message'];
        }
    }

    /**
     * Send a message to a group chat
     * 
     * @param User $sender
     * @param GroupChat $groupChat
     * @param string $content
     * @param array $attachments
     * @return array{success: bool, message?: Message, error?: string}
     */
    public function sendGroupMessage(
        User $sender,
        GroupChat $groupChat,
        string $content,
        array $attachments = []
    ): array {
        try {
            // Validate access
            if (!$groupChat->canSendMessages($sender)) {
                $error = $groupChat->status === 'archived'
                    ? 'This group chat has been archived. Messages cannot be sent.'
                    : 'Unauthorized';
                return ['success' => false, 'error' => $error];
            }

            // Handle attachments
            $attachmentData = $this->processAttachments($attachments, 'group-chat-attachments', [
                'type' => 'group_chat_attachment',
                'user_id' => $sender->id,
                'group_chat_id' => $groupChat->id,
            ]);

            // Create message
            $message = Message::create([
                'group_chat_id' => $groupChat->id,
                'sender_id' => $sender->id,
                'recipient_id' => null,
                'project_id' => $groupChat->project_id,
                'message' => $content,
                'message_type' => 'project',
                'attachments' => !empty($attachmentData) ? $attachmentData : null,
                'status' => 'sent',
            ]);

            // Update group chat
            $groupChat->updateLastMessage($message);
            $groupChat->incrementUnreadForMembers($sender->id);

            // Load relationships
            $message->load('sender:id,fullName,profilePic,role');

            // Send push notifications to members
            $this->sendGroupMessageNotifications($message, $groupChat, $sender);

            return [
                'success' => true,
                'message' => $message,
                'group_chat' => $groupChat,
            ];

        } catch (\Exception $e) {
            Log::error('MessagingService::sendGroupMessage failed', [
                'sender_id' => $sender->id,
                'group_chat_id' => $groupChat->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => 'Failed to send message'];
        }
    }

    /**
     * Process file attachments for upload
     * 
     * @param array<UploadedFile> $files
     * @param string $directory
     * @param array $metadata
     * @return array
     */
    public function processAttachments(array $files, string $directory, array $metadata = []): array
    {
        $attachments = [];

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            try {
                $uploadResult = $this->r2Service->uploadFile($file, $directory, null, $metadata);

                if ($uploadResult['success']) {
                    $attachments[] = [
                        'name' => $uploadResult['original_name'],
                        'path' => $uploadResult['path'],
                        'url' => $uploadResult['url'],
                        'size' => $uploadResult['size'],
                        'mime_type' => $uploadResult['mime_type'],
                    ];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to upload attachment', [
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $attachments;
    }

    /**
     * Validate that a project can receive messages
     * 
     * @param Project $project
     * @return array{valid: bool, error?: string}
     */
    public function validateProjectForMessaging(Project $project): array
    {
        $nonMessageableStatuses = ['completed', 'cancelled', 'archived'];

        if (in_array($project->status, $nonMessageableStatuses)) {
            return [
                'valid' => false,
                'error' => 'Messages cannot be sent to ' . $project->status . ' projects.',
            ];
        }

        return ['valid' => true];
    }

    /**
     * Determine the recipient for a direct message
     * 
     * @param User $sender
     * @param Project $project
     * @return int|null
     */
    public function determineRecipient(User $sender, Project $project): ?int
    {
        if ($sender->isAdmin()) {
            return $project->client_id;
        }

        if ($sender->isClient()) {
            // Find any active admin to notify
            return User::where('role', 'admin')
                ->where('status', 'active')
                ->first()
                ?->id;
        }

        return null;
    }

    /**
     * Mark direct messages as read
     * 
     * @param Conversation $conversation
     * @param User $user
     * @return void
     */
    public function markDirectMessagesAsRead(Conversation $conversation, User $user): void
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

    /**
     * Mark group chat messages as read for a user
     * 
     * @param GroupChat $groupChat
     * @param User $user
     * @return void
     */
    public function markGroupMessagesAsRead(GroupChat $groupChat, User $user): void
    {
        $groupChat->resetUnreadForMember($user);
    }

    /**
     * Get total unread count for a user across all conversations
     * 
     * @param User $user
     * @return int
     */
    public function getTotalUnreadCount(User $user): int
    {
        $directCount = $this->getDirectMessagesUnreadCount($user);
        $groupCount = $this->getGroupMessagesUnreadCount($user);

        return $directCount + $groupCount;
    }

    /**
     * Get unread count for direct messages
     * 
     * @param User $user
     * @return int
     */
    public function getDirectMessagesUnreadCount(User $user): int
    {
        if ($user->isAdmin()) {
            return (int) Conversation::sum('unread_count_admin');
        }

        if ($user->isClient()) {
            return (int) Conversation::where('client_id', $user->id)
                ->sum('unread_count_client');
        }

        return 0;
    }

    /**
     * Get unread count for group messages
     * 
     * @param User $user
     * @return int
     */
    public function getGroupMessagesUnreadCount(User $user): int
    {
        // Clients don't have group chats
        if ($user->isClient()) {
            return 0;
        }

        return (int) $user->groupChats()
            ->join('group_chat_members', 'group_chats.id', '=', 'group_chat_members.group_chat_id')
            ->where('group_chat_members.user_id', $user->id)
            ->sum('group_chat_members.unread_count');
    }

    /**
     * Search messages across user's accessible conversations
     * 
     * @param User $user
     * @param string $query
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchMessages(User $user, string $query, array $filters = [])
    {
        $messagesQuery = Message::with(['sender:id,fullName,profilePic,role', 'project:id,title'])
            ->where('message', 'LIKE', "%{$query}%");

        // Scope by user access
        if ($user->isClient()) {
            $clientProjectIds = Project::where('client_id', $user->id)->pluck('id');
            $messagesQuery->whereIn('project_id', $clientProjectIds);
        }

        // Apply filters
        if (!empty($filters['project_id'])) {
            $messagesQuery->where('project_id', $filters['project_id']);
        }

        if (!empty($filters['date_from'])) {
            $messagesQuery->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $messagesQuery->whereDate('created_at', '<=', $filters['date_to']);
        }

        $perPage = $filters['per_page'] ?? 20;

        return $messagesQuery
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Send push notification for direct message
     * 
     * @param Message $message
     * @return void
     */
    protected function sendDirectMessageNotification(Message $message): void
    {
        try {
            $this->firebaseService->sendNewMessageNotification($message);
        } catch (\Exception $e) {
            Log::warning('Failed to send direct message notification', [
                'message_id' => $message->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send push notifications for group message to all members except sender
     * 
     * @param Message $message
     * @param GroupChat $groupChat
     * @param User $sender
     * @return void
     */
    protected function sendGroupMessageNotifications(
        Message $message,
        GroupChat $groupChat,
        User $sender
    ): void {
        $members = $groupChat->members()
            ->where('user_id', '!=', $sender->id)
            ->get();

        foreach ($members as $member) {
            try {
                $this->firebaseService->sendGroupChatNotification($message, $member);
            } catch (\Exception $e) {
                Log::warning('Failed to send group message notification', [
                    'message_id' => $message->id,
                    'member_id' => $member->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Create a highlight snippet for search results
     * 
     * @param string $message
     * @param string $query
     * @param int $contextLength
     * @return string
     */
    public function createHighlightSnippet(string $message, string $query, int $contextLength = 50): string
    {
        $position = stripos($message, $query);

        if ($position === false) {
            return substr($message, 0, $contextLength * 2) . (strlen($message) > $contextLength * 2 ? '...' : '');
        }

        $start = max(0, $position - $contextLength);
        $end = min(strlen($message), $position + strlen($query) + $contextLength);

        $snippet = substr($message, $start, $end - $start);

        // Add ellipsis if truncated
        if ($start > 0) {
            $snippet = '...' . $snippet;
        }
        if ($end < strlen($message)) {
            $snippet = $snippet . '...';
        }

        // Wrap the matched text with a marker
        $snippet = preg_replace(
            '/(' . preg_quote($query, '/') . ')/i',
            '<mark>$1</mark>',
            $snippet
        );

        return $snippet;
    }
}
