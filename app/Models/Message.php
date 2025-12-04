<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'group_chat_id',
        'sender_id',
        'recipient_id',
        'subject',
        'message',
        'message_type',
        'project_id',
        'task_id',
        'attachments',
        'is_read',
        'status',
        'read_at',
        'delivered_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Remove appends to prevent n+1 queries and memory issues
    // protected $appends = ['is_sender', 'formatted_time'];

    /**
     * Get the sender of the message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the project this message belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the task this message belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the conversation this message belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'conversation_id');
    }

    /**
     * Get the group chat this message belongs to
     */
    public function groupChat(): BelongsTo
    {
        return $this->belongsTo(GroupChat::class, 'group_chat_id');
    }

    /**
     * Scope a query to only include messages for a specific project
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to only include unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include messages in a specific conversation
     */
    public function scopeInConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Scope a query to only include messages in a specific group chat
     */
    public function scopeForGroupChat($query, $groupChatId)
    {
        return $query->where('group_chat_id', $groupChatId);
    }

    /**
     * Scope a query to only include messages between two users
     */
    public function scopeBetween($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId1)
                     ->where('recipient_id', $userId2);
            })->orWhere(function ($subQ) use ($userId1, $userId2) {
                $subQ->where('sender_id', $userId2)
                     ->where('recipient_id', $userId1);
            });
        });
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'status' => 'read',
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered(): void
    {
        if ($this->status === 'sent') {
            $this->update([
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);
        }
    }

    /**
     * Check if the current user is the sender
     */
    public function getIsSenderAttribute(): bool
    {
        return $this->sender_id === auth()->id();
    }

    /**
     * Get the sender name with fallback for deleted users
     */
    public function getSenderNameAttribute(): string
    {
        return $this->sender?->fullName ?? 'Deleted User';
    }

    /**
     * Get the sender profile picture with fallback
     */
    public function getSenderProfilePicAttribute(): ?string
    {
        return $this->sender?->profilePic;
    }

    /**
     * Get formatted timestamp
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Generate conversation ID for a project
     */
    public static function generateConversationId(int $projectId): string
    {
        return "project_{$projectId}";
    }

    /**
     * Check if user can access this message
     */
    public function canAccess(User $user): bool
    {
        // For group chat messages, check group chat access
        if ($this->group_chat_id) {
            return $this->groupChat->canAccess($user);
        }

        // Admins can access all messages
        if ($user->isAdmin()) {
            return true;
        }

        // Users can access messages they sent or received
        if ($this->sender_id === $user->id || $this->recipient_id === $user->id) {
            return true;
        }

        // Clients can access messages for their projects
        if ($user->isClient() && $this->project_id) {
            return $this->project->client_id === $user->id;
        }

        return false;
    }
}
