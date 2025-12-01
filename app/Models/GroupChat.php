<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupChat extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'status',
        'archived_at',
        'archived_by',
        'last_message_id',
        'last_message_at',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the project this group chat belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who archived this chat
     */
    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Get the last message in this group chat
     */
    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'last_message_id')->withoutGlobalScopes();
    }

    /**
     * Get all messages in this group chat
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'group_chat_id')
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get all members of this group chat
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_chat_members', 'group_chat_id', 'user_id')
                    ->withPivot('unread_count', 'last_read_at')
                    ->withTimestamps();
    }

    /**
     * Scope to only include open (non-archived) group chats
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope to only include archived group chats
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Check if user can access this group chat
     */
    public function canAccess(User $user): bool
    {
        // Admins can access all group chats
        if ($user->isAdmin()) {
            return true;
        }

        // Adiutors can access if they're members
        if ($user->isAdiutor()) {
            return $this->members()->where('user_id', $user->id)->exists();
        }

        // Clients cannot access group chats
        return false;
    }

    /**
     * Check if user can send messages (chat must be open)
     */
    public function canSendMessages(User $user): bool
    {
        return $this->status === 'open' && $this->canAccess($user);
    }

    /**
     * Archive this group chat (admin only)
     */
    public function archive(User $admin): bool
    {
        if (!$admin->isAdmin()) {
            return false;
        }

        return $this->update([
            'status' => 'archived',
            'archived_at' => now(),
            'archived_by' => $admin->id,
        ]);
    }

    /**
     * Reopen an archived group chat (admin only)
     */
    public function reopen(User $admin): bool
    {
        if (!$admin->isAdmin()) {
            return false;
        }

        return $this->update([
            'status' => 'open',
            'archived_at' => null,
            'archived_by' => null,
        ]);
    }

    /**
     * Update with latest message info
     */
    public function updateLastMessage(Message $message): void
    {
        $this->update([
            'last_message_id' => $message->id,
            'last_message_at' => $message->created_at,
        ]);
    }

    /**
     * Increment unread count for all members except the sender
     */
    public function incrementUnreadForMembers(int $senderId): void
    {
        $this->members()
            ->where('user_id', '!=', $senderId)
            ->increment('group_chat_members.unread_count');
    }

    /**
     * Reset unread count for a specific member
     */
    public function resetUnreadForMember(User $user): void
    {
        $this->members()
            ->where('user_id', $user->id)
            ->update([
                'group_chat_members.unread_count' => 0,
                'group_chat_members.last_read_at' => now(),
            ]);
    }

    /**
     * Get unread count for a specific member
     */
    public function getUnreadCountForMember(User $user): int
    {
        $member = $this->members()->where('user_id', $user->id)->first();
        return $member ? $member->pivot->unread_count : 0;
    }

    /**
     * Add a member to this group chat
     */
    public function addMember(int $userId): void
    {
        $this->members()->syncWithoutDetaching([$userId => [
            'unread_count' => 0,
            'last_read_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]]);
    }

    /**
     * Add multiple members to this group chat
     */
    public function addMembers(array $userIds): void
    {
        $syncData = [];
        foreach ($userIds as $userId) {
            $syncData[$userId] = [
                'unread_count' => 0,
                'last_read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        $this->members()->syncWithoutDetaching($syncData);
    }

    /**
     * Get or create group chat for a project
     */
    public static function getOrCreateForProject(int $projectId): self
    {
        $project = Project::findOrFail($projectId);

        $groupChat = self::firstOrCreate(
            ['project_id' => $projectId],
            [
                'name' => $project->title,
                'status' => 'open',
            ]
        );

        // If newly created, add all admins as members
        if ($groupChat->wasRecentlyCreated) {
            $adminIds = User::where('role', 'admin')
                           ->where('status', 'active')
                           ->pluck('id')
                           ->toArray();
            
            if (!empty($adminIds)) {
                $groupChat->addMembers($adminIds);
            }
        }

        return $groupChat;
    }

    /**
     * Sync members based on project assignments
     * Adds all admins + assigned adiutors
     */
    public function syncMembersFromProject(): void
    {
        $project = $this->project;

        // Get all active admins
        $adminIds = User::where('role', 'admin')
                       ->where('status', 'active')
                       ->pluck('id')
                       ->toArray();

        // Get all assigned adiutors
        $adiutorIds = $project->adiutors()
                             ->where('status', 'active')
                             ->pluck('users.id')
                             ->toArray();

        // Combine and add all members
        $allMemberIds = array_unique(array_merge($adminIds, $adiutorIds));
        
        if (!empty($allMemberIds)) {
            $this->addMembers($allMemberIds);
        }
    }

    /**
     * Get display name for the group chat
     */
    public function getDisplayName(): string
    {
        return $this->name ?? $this->project->title;
    }
}
