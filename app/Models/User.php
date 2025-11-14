<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'firebase_uid',
        'auth_provider',
        'firebase_profile',
        'last_firebase_sync',
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
            'firebase_profile' => 'array',
            'last_firebase_sync' => 'datetime',
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
     * Check if user is authenticated via Firebase
     */
    public function isFirebaseUser(): bool
    {
        return $this->auth_provider === 'firebase' && !empty($this->firebase_uid);
    }

    /**
     * Check if user is authenticated via traditional login
     */
    public function isLocalUser(): bool
    {
        return $this->auth_provider === 'local';
    }

    /**
     * Check if user can link their account to Firebase
     */
    public function canLinkFirebase(): bool
    {
        return empty($this->firebase_uid) && $this->isLocalUser();
    }

    /**
     * Check if user can unlink their Firebase account
     */
    public function canUnlinkFirebase(): bool
    {
        return $this->isFirebaseUser() && !empty($this->password);
    }

    /**
     * Link user account to Firebase
     */
    public function linkFirebaseProfile(array $firebaseProfile): void
    {
        $this->update([
            'firebase_uid' => $firebaseProfile['uid'],
            'auth_provider' => 'firebase',
            'firebase_profile' => $firebaseProfile,
            'last_firebase_sync' => now(),
            'email_verified_at' => $firebaseProfile['email_verified'] ?? false ? now() : null,
        ]);
    }

    /**
     * Unlink user account from Firebase
     */
    public function unlinkFirebase(): void
    {
        $this->update([
            'firebase_uid' => null,
            'auth_provider' => 'local',
            'firebase_profile' => null,
            'last_firebase_sync' => null,
        ]);
    }

    /**
     * Sync user profile with Firebase data
     */
    public function syncFirebaseProfile(array $firebaseProfile): void
    {
        $updates = [
            'firebase_profile' => $firebaseProfile,
            'last_firebase_sync' => now(),
        ];

        // Optionally sync email if it changed in Firebase
        if (isset($firebaseProfile['email']) && $firebaseProfile['email'] !== $this->email) {
            $updates['email'] = $firebaseProfile['email'];
        }

        // Optionally sync name if it changed in Firebase
        if (isset($firebaseProfile['name']) && $firebaseProfile['name'] !== $this->fullName) {
            $updates['fullName'] = $firebaseProfile['name'];
        }

        // Update email verification status
        if (isset($firebaseProfile['email_verified'])) {
            $updates['email_verified_at'] = $firebaseProfile['email_verified'] ? now() : null;
        }

        $this->update($updates);
    }

    /**
     * Get Firebase profile picture URL
     */
    public function getFirebaseProfilePicture(): ?string
    {
        return $this->firebase_profile['picture'] ?? null;
    }

    /**
     * Get the best available profile picture (Firebase or local)
     */
    public function getBestProfilePicture(): ?string
    {
        return $this->getFirebaseProfilePicture() ?: $this->profilePic;
    }

    /**
     * Get the profile picture URL for display
     */
    public function getProfilePictureUrl(): string
    {
        $profilePic = $this->getBestProfilePicture();
        
        if (!$profilePic) {
            // Return default avatar from UI Avatars
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->fullName);
        }
        
        // Check if it's already a full URL (Auth0 or R2)
        if (str_starts_with($profilePic, 'https://') || str_starts_with($profilePic, 'http://')) {
            return $profilePic;
        }
        
        // Legacy local file
        return asset('storage/' . $profilePic);
    }

    /**
     * Get the adiutor profile for this user
     */
    public function adiutorProfile()
    {
        return $this->hasOne(AdiutorProfile::class, 'user_id');
    }
    
    /**
     * Get the client profile for this user
     */
    public function clientProfile()
    {
        return $this->hasOne(ClientProfile::class, 'user_id');
    }

    /**
     * Get the skills for this adiutor user
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'adiutor_skills', 'adiutor_id', 'skill_id')
                    ->withPivot('proficiency_level', 'years_experience')
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
     * Get feedback given by this user (as client)
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'client_id');
    }

    /**
     * Get feedback received by this user (for adiutors)
     */
    public function receivedFeedback()
    {
        return $this->hasMany(Feedback::class, 'adiutor_id');
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

    /**
     * Get audit logs for this user
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Get loyalty points for this user
     */
    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    /**
     * Get loyalty transactions for this user
     */
    public function loyaltyTransactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    /**
     * Get coupons created by this user (admin)
     */
    public function createdCoupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'created_by');
    }

    /**
     * Get user-specific coupons for this user
     */
    public function specificCoupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'specific_user_id');
    }

    /**
     * Get coupon usages by this user
     */
    public function couponUsages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Get all coupons available to this user (user-specific coupons)
     * This is an alias for specificCoupons for easier access
     */
    public function coupons(): HasMany
    {
        return $this->specificCoupons();
    }

    /**
     * Get or create loyalty points account
     */
    public function getOrCreateLoyaltyPoints(): LoyaltyPoint
    {
        return $this->loyaltyPoints()->firstOrCreate(
            ['user_id' => $this->id],
            [
                'total_points' => 0,
                'available_points' => 0,
                'lifetime_earned' => 0,
                'lifetime_redeemed' => 0,
                'tier' => 'bronze',
                'points_to_next_tier' => 5000,
            ]
        );
    }

    /**
     * ==========================================
     * REFERRAL SYSTEM RELATIONSHIPS
     * ==========================================
     */

    /**
     * Get the user's referral code
     */
    public function referralCode(): HasOne
    {
        return $this->hasOne(ReferralCode::class);
    }

    /**
     * Get or create user's referral code
     */
    public function getOrCreateReferralCode(): ReferralCode
    {
        return $this->referralCode()->firstOrCreate(
            ['user_id' => $this->id],
            [
                'code' => ReferralCode::generateUniqueCode($this),
                'is_active' => true,
            ]
        );
    }

    /**
     * Get referrals made by this user (as referrer)
     */
    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get the referral record (if this user was referred)
     */
    public function referralReceived(): HasOne
    {
        return $this->hasOne(Referral::class, 'referred_id');
    }

    /**
     * Get the user who referred this user
     */
    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by_user_id');
    }

    /**
     * Check if user was referred by someone
     */
    public function isReferred(): bool
    {
        return !is_null($this->referred_by_user_id);
    }

    /**
     * Get total referrals made
     */
    public function getTotalReferralsAttribute(): int
    {
        return $this->referralsMade()->count();
    }

    /**
     * Get successful referrals (completed first payment)
     */
    public function getSuccessfulReferralsAttribute(): int
    {
        return $this->referralsMade()->where('status', 'rewarded')->count();
    }
}
