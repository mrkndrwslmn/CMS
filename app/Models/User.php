<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fullName',
        'email',
        'password',
        'role',
        'phoneNumber',
        'profilePic',
        'status',
        'auth0_id',
        'auth_provider',
        'auth0_profile',
        'last_auth0_sync',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'auth0_profile' => 'array',
            'last_auth0_sync' => 'datetime',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Check if user is adiutor
     */
    public function isAdiutor(): bool
    {
        return $this->hasRole('adiutor');
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the dashboard route for this user's role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'client' => 'client.dashboard',
            'adiutor' => 'adiutor.dashboard',
            default => 'login'
        };
    }

    /**
     * Auth0 Integration Methods
     */

    /**
     * Check if user is authenticated via Auth0
     */
    public function isAuth0User(): bool
    {
        return $this->auth_provider === 'auth0' && !empty($this->auth0_id);
    }

    /**
     * Check if user is authenticated via traditional login
     */
    public function isLocalUser(): bool
    {
        return $this->auth_provider === 'local';
    }

    /**
     * Check if user can link their account to Auth0
     */
    public function canLinkAuth0(): bool
    {
        return empty($this->auth0_id) && $this->isLocalUser();
    }

    /**
     * Check if user can unlink their Auth0 account
     */
    public function canUnlinkAuth0(): bool
    {
        return $this->isAuth0User() && !empty($this->password);
    }

    /**
     * Link user account to Auth0
     */
    public function linkAuth0Profile(array $auth0Profile): void
    {
        $this->update([
            'auth0_id' => $auth0Profile['sub'],
            'auth_provider' => 'auth0',
            'auth0_profile' => $auth0Profile,
            'last_auth0_sync' => now(),
            'email_verified_at' => $auth0Profile['email_verified'] ?? false ? now() : null,
        ]);
    }

    /**
     * Unlink user account from Auth0
     */
    public function unlinkAuth0(): void
    {
        $this->update([
            'auth0_id' => null,
            'auth_provider' => 'local',
            'auth0_profile' => null,
            'last_auth0_sync' => null,
        ]);
    }

    /**
     * Sync user profile with Auth0 data
     */
    public function syncAuth0Profile(array $auth0Profile): void
    {
        $updates = [
            'auth0_profile' => $auth0Profile,
            'last_auth0_sync' => now(),
        ];

        // Optionally sync email if it changed in Auth0
        if (isset($auth0Profile['email']) && $auth0Profile['email'] !== $this->email) {
            $updates['email'] = $auth0Profile['email'];
        }

        // Optionally sync name if it changed in Auth0
        if (isset($auth0Profile['name']) && $auth0Profile['name'] !== $this->fullName) {
            $updates['fullName'] = $auth0Profile['name'];
        }

        // Update email verification status
        if (isset($auth0Profile['email_verified'])) {
            $updates['email_verified_at'] = $auth0Profile['email_verified'] ? now() : null;
        }

        $this->update($updates);
    }

    /**
     * Get Auth0 profile picture URL
     */
    public function getAuth0ProfilePicture(): ?string
    {
        return $this->auth0_profile['picture'] ?? null;
    }

    /**
     * Get the best available profile picture (Auth0 or local)
     */
    public function getBestProfilePicture(): ?string
    {
        return $this->getAuth0ProfilePicture() ?: $this->profilePic;
    }

    /**
     * Get the adiutor profile for this user
     */
    public function adiutorProfile()
    {
        return $this->hasOne(AdiutorProfile::class);
    }
    
    /**
     * Get the client profile for this user
     */
    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class);
    }

    /**
     * Get the skills for this adiutor user
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'adiutor_skills')
                    ->withPivot('proficiency', 'years_experience')
                    ->withTimestamps();
    }

    /**
     * Get projects assigned to this adiutor
     */
    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_assignments', 'adiutor_id', 'project_id')
                    ->withPivot('agreed_rate', 'start_date', 'expected_completion', 'status', 'notes', 'progress_percentage')
                    ->withTimestamps();
    }

        /**
     * Get projects created by this user (for clients)
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Get unread notifications count
     * Uses Laravel's built-in Notifiable trait
     */
    public function unreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Get tasks assigned to this user (for adiutors)
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignedTo');
    }

    /**
     * Get tasks created for this user (for clients)
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'client_id');
    }

    /**
     * Get forms/requests submitted by this user (for clients)
     * Now returns ServiceRequest instead of deprecated Form model
     */
    public function forms(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'client_id');
    }

    /**
     * Get service requests submitted by this user (preferred method)
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'client_id');
    }

    /**
     * Get documents uploaded by this user
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    /**
     * Get documents associated with this user as client
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'client_id');
    }

    /**
     * Get feedback given by this user
     */
    public function feedbacks()
    {
        // This relationship uses either client_id (if available) or falls back to giver_id
        return $this->hasMany(Feedback::class, 'client_id');
    }

    /**
     * Get feedback received by this user (for adiutors)
     */
    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'receiver_id');
    }

    /**
     * Get notes related to this client
     */
    public function notes()
    {
        return $this->hasMany(Note::class, 'client_id');
    }

    /**
     * Get messages sent by this user
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get messages received by this user
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    /**
     * Get conversations for this client
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'client_id');
    }

    /**
     * Get total unread message count for this user
     */
    public function unreadMessagesCount(): int
    {
        if ($this->isAdmin()) {
            // Admins see all conversations' unread counts
            return Conversation::sum('unread_count_admin');
        } elseif ($this->isClient()) {
            // Clients only see their own conversations' unread counts
            return $this->conversations()->sum('unread_count_client');
        }

        return 0;
    }

    /**
     * Update FCM token for push notifications
     */
    public function updateFcmToken(?string $token): void
    {
        $this->update([
            'fcm_token' => $token,
            'fcm_token_updated_at' => $token ? now() : null,
        ]);
    }
}
