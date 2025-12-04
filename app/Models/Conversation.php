<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Conversation extends Model
{
    protected $fillable = [
        'conversation_id',
        'project_id',
        'client_id',
        'last_message_id',
        'last_message_at',
        'unread_count_client',
        'unread_count_admin',
        'is_archived',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'unread_count_client' => 'integer',
        'unread_count_admin' => 'integer',
        'is_archived' => 'boolean',
    ];

    /**
     * Get the project this conversation belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the client in this conversation
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the last message in this conversation
     */
    public function lastMessage(): BelongsTo
    {
        // Prevent circular reference by not loading conversation on lastMessage
        return $this->belongsTo(Message::class, 'last_message_id')->withoutGlobalScopes();
    }

    /**
     * Get all messages in this conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id', 'conversation_id')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Scope a query to only include active conversations
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope a query to only include archived conversations
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Scope a query to only include conversations with unread messages
     */
    public function scopeWithUnread($query, User $user)
    {
        $column = $user->isAdmin() ? 'unread_count_admin' : 'unread_count_client';
        return $query->where($column, '>', 0);
    }

    /**
     * Increment unread count for specific user type
     * Uses atomic DB operation to prevent race conditions
     */
    public function incrementUnreadCount(bool $isClientMessage): void
    {
        $column = $isClientMessage ? 'unread_count_admin' : 'unread_count_client';
        
        DB::table('conversations')
            ->where('id', $this->id)
            ->increment($column);
        
        // Refresh the model to reflect the change
        $this->refresh();
    }

    /**
     * Reset unread count for specific user type
     */
    public function resetUnreadCount(User $user): void
    {
        if ($user->isAdmin()) {
            $this->update(['unread_count_admin' => 0]);
        } elseif ($user->isClient() && $user->id === $this->client_id) {
            $this->update(['unread_count_client' => 0]);
        }
    }

    /**
     * Update conversation with latest message info
     */
    public function updateLastMessage(Message $message): void
    {
        $this->update([
            'last_message_id' => $message->id,
            'last_message_at' => $message->created_at,
        ]);
    }

    /**
     * Get or create conversation for a project
     */
    public static function getOrCreateForProject(int $projectId): self
    {
        $project = Project::findOrFail($projectId);
        $conversationId = Message::generateConversationId($projectId);

        return self::firstOrCreate(
            ['conversation_id' => $conversationId],
            [
                'project_id' => $projectId,
                'client_id' => $project->client_id,
            ]
        );
    }

    /**
     * Check if user can access this conversation
     */
    public function canAccess(User $user): bool
    {
        // Admins can access all conversations
        if ($user->isAdmin()) {
            return true;
        }

        // Clients can only access their own project conversations
        if ($user->isClient()) {
            return $this->client_id === $user->id;
        }

        return false;
    }

    /**
     * Get unread count for specific user
     */
    public function getUnreadCountForUser(User $user): int
    {
        if ($user->isAdmin()) {
            return $this->unread_count_admin;
        } elseif ($user->isClient() && $user->id === $this->client_id) {
            return $this->unread_count_client;
        }

        return 0;
    }
}
