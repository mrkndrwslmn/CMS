# 🎁 Referral System - Complete Implementation Plan

## 📊 System Analysis

### Current System Assessment

**Existing Infrastructure:**
- ✅ User management system (Admin, Client, Adiutor roles)
- ✅ Loyalty points system with 4 tiers (Bronze, Silver, Gold, Platinum)
- ✅ Coupon system with multiple types (public, user-specific, request-specific)
- ✅ Payment integration (Maya Payment Gateway)
- ✅ Service request workflow
- ✅ Email notification system
- ✅ Scheduled tasks for automation
- ✅ `awardReferralBonus()` method exists in LoyaltyService (1000 points)

**Gap Analysis:**
- ❌ No referral code generation system
- ❌ No referral tracking mechanism
- ❌ No referral-specific database tables
- ❌ No referrer-referred relationship
- ❌ No referral reward claiming system
- ❌ No referral dashboard/statistics
- ❌ No referral email notifications
- ❌ No referral coupon generation

---

## 🎯 Implementation Strategy

### Industry Best Practices Analysis

**1. Dropbox Model (Dual Reward)**
- Both referrer and referee get benefits
- Immediate reward upon signup + additional reward upon first action
- **Perfect for our system**: Reward on signup + larger reward after first payment

**2. Airbnb Model (Tiered Rewards)**
- Different rewards based on transaction value
- First-time vs repeat referrals
- **Adaptation**: Different rewards based on client tier level

**3. Uber Model (Time-Limited Campaigns)**
- Seasonal boost campaigns
- Urgency-driven referrals
- **Adaptation**: Special referral campaigns during peak seasons

### **Recommended Approach: Hybrid System**

**Primary Reward: Loyalty Points** (Immediate value, flexible redemption)
- ✅ Integrates seamlessly with existing loyalty system
- ✅ Encourages repeat usage
- ✅ Tracks lifetime value
- ✅ Supports tier progression

**Secondary Reward: Coupon Generation** (Conversion driver)
- ✅ Creates urgency with expiry dates
- ✅ Incentivizes first purchase
- ✅ Can be personalized
- ✅ Easy to track conversion

**Why Both?**
1. **Points** = Long-term engagement, loyalty building
2. **Coupons** = Short-term conversion, first purchase incentive
3. Together = Balanced reward system

---

## 📋 Implementation Plan

### Phase 1: Database Schema Design

#### 1.1 Referral Codes Table
**File**: `database/migrations/2025_11_14_000001_create_referral_codes_table.php`

```php
Schema::create('referral_codes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('code', 20)->unique(); // e.g., "JOHN2025XYZ"
    $table->integer('total_referrals')->default(0); // Total successful referrals
    $table->integer('pending_referrals')->default(0); // Signups not yet paid
    $table->integer('successful_referrals')->default(0); // Referrals who completed first payment
    $table->integer('lifetime_earnings_points')->default(0); // Total points earned
    $table->timestamp('last_used_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    // Indexes
    $table->index(['code', 'is_active']);
    $table->index('user_id');
});
```

#### 1.2 Referrals Table
**File**: `database/migrations/2025_11_14_000002_create_referrals_table.php`

```php
Schema::create('referrals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade'); // Who referred
    $table->foreignId('referred_id')->constrained('users')->onDelete('cascade'); // Who was referred
    $table->string('referral_code')->index(); // Code used
    
    // Referral Status
    $table->enum('status', ['pending', 'completed', 'rewarded'])->default('pending');
    // pending: Signed up, no payment yet
    // completed: First payment made
    // rewarded: Rewards distributed to both parties
    
    // Rewards Tracking
    $table->integer('referrer_points_pending')->default(0); // Points waiting for completion
    $table->integer('referrer_points_earned')->default(0); // Points actually awarded
    $table->foreignId('referrer_coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
    
    $table->integer('referred_points_earned')->default(0); // Welcome bonus points
    $table->foreignId('referred_coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
    
    // Completion Tracking
    $table->foreignId('first_payment_id')->nullable()->constrained('payments')->onDelete('set null');
    $table->timestamp('completed_at')->nullable(); // When first payment made
    $table->timestamp('rewarded_at')->nullable(); // When rewards distributed
    
    // Metadata
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->json('metadata')->nullable(); // Additional tracking data
    
    $table->timestamps();
    
    // Indexes
    $table->index(['referrer_id', 'status']);
    $table->index(['referred_id', 'status']);
    $table->index('status');
    $table->unique(['referrer_id', 'referred_id']); // Prevent duplicate referrals
});
```

#### 1.3 Users Table Update
**File**: `database/migrations/2025_11_14_000003_add_referral_fields_to_users.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->foreignId('referred_by_user_id')->nullable()->after('role')
        ->constrained('users')->onDelete('set null');
    $table->string('referred_by_code', 20)->nullable()->after('referred_by_user_id');
    $table->timestamp('referral_registered_at')->nullable()->after('referred_by_code');
    $table->boolean('is_referral_eligible')->default(true)->after('referral_registered_at');
    
    // Index
    $table->index('referred_by_user_id');
    $table->index('referred_by_code');
});
```

#### 1.4 Referral Campaigns Table (Optional - for future)
**File**: `database/migrations/2025_11_14_000004_create_referral_campaigns_table.php`

```php
Schema::create('referral_campaigns', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // "Holiday 2x Referral Campaign"
    $table->string('code')->unique(); // "HOLIDAY2X"
    $table->text('description')->nullable();
    
    // Campaign Settings
    $table->float('points_multiplier')->default(1.0); // 2.0 = double points
    $table->integer('bonus_points_referrer')->default(0); // Extra bonus for referrer
    $table->integer('bonus_points_referred')->default(0); // Extra bonus for new user
    
    // Coupon Settings
    $table->boolean('auto_generate_coupon')->default(false);
    $table->string('coupon_prefix', 10)->nullable(); // "REFER"
    $table->enum('coupon_discount_type', ['percentage', 'fixed_amount'])->nullable();
    $table->decimal('coupon_discount_value', 10, 2)->nullable();
    
    // Validity
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('ends_at')->nullable();
    $table->boolean('is_active')->default(true);
    
    // Limits
    $table->integer('max_referrals')->nullable(); // Max total referrals
    $table->integer('max_per_user')->nullable(); // Max referrals per user
    
    $table->timestamps();
});
```

---

### Phase 2: Models & Relationships

#### 2.1 ReferralCode Model
**File**: `app/Models/ReferralCode.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'total_referrals',
        'pending_referrals',
        'successful_referrals',
        'lifetime_earnings_points',
        'last_used_at',
        'is_active',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who owns this referral code
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all referrals made with this code
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referral_code', 'code');
    }

    /**
     * Generate unique referral code
     */
    public static function generateUniqueCode(User $user): string
    {
        do {
            // Format: FirstName + Year + 3 random chars
            // Example: JOHN2025ABC
            $firstName = strtoupper(substr($user->fullName, 0, 4));
            $year = date('Y');
            $random = strtoupper(substr(md5(uniqid()), 0, 3));
            $code = $firstName . $year . $random;
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Increment referral counters
     */
    public function incrementReferral(string $status = 'pending'): void
    {
        $this->increment('total_referrals');
        
        if ($status === 'pending') {
            $this->increment('pending_referrals');
        } elseif ($status === 'completed') {
            $this->decrement('pending_referrals');
            $this->increment('successful_referrals');
        }
        
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Add points to lifetime earnings
     */
    public function addEarnings(int $points): void
    {
        $this->increment('lifetime_earnings_points', $points);
    }

    /**
     * Get conversion rate (successful / total)
     */
    public function getConversionRate(): float
    {
        if ($this->total_referrals === 0) {
            return 0;
        }
        
        return ($this->successful_referrals / $this->total_referrals) * 100;
    }
}
```

#### 2.2 Referral Model
**File**: `app/Models/Referral.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'status',
        'referrer_points_pending',
        'referrer_points_earned',
        'referrer_coupon_id',
        'referred_points_earned',
        'referred_coupon_id',
        'first_payment_id',
        'completed_at',
        'rewarded_at',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'rewarded_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the referrer (who invited)
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user (who was invited)
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Get the first payment that completed the referral
     */
    public function firstPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'first_payment_id');
    }

    /**
     * Get the referrer's reward coupon
     */
    public function referrerCoupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'referrer_coupon_id');
    }

    /**
     * Get the referred user's welcome coupon
     */
    public function referredCoupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'referred_coupon_id');
    }

    /**
     * Mark referral as completed
     */
    public function markCompleted(Payment $payment): void
    {
        $this->update([
            'status' => 'completed',
            'first_payment_id' => $payment->id,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark referral as rewarded
     */
    public function markRewarded(): void
    {
        $this->update([
            'status' => 'rewarded',
            'rewarded_at' => now(),
        ]);
    }

    /**
     * Check if referral is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if referral is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if referral is rewarded
     */
    public function isRewarded(): bool
    {
        return $this->status === 'rewarded';
    }
}
```

#### 2.3 ReferralCampaign Model (Optional)
**File**: `app/Models/ReferralCampaign.php`

```php
// Basic model structure - will implement if needed in future
```

#### 2.4 Update User Model
**File**: `app/Models/User.php` (add relationships)

```php
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
```

---

### Phase 3: Service Layer - ReferralService

**File**: `app/Services/ReferralService.php`

```php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\Coupon;
use App\Models\Payment;
use App\Mail\ReferralSignupMail;
use App\Mail\ReferralCompletedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReferralService
{
    protected CouponService $couponService;
    protected LoyaltyService $loyaltyService;

    public function __construct(
        CouponService $couponService,
        LoyaltyService $loyaltyService
    ) {
        $this->couponService = $couponService;
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Process referral during user registration
     * 
     * @param User $newUser
     * @param string|null $referralCode
     * @param array $metadata (ip_address, user_agent, etc.)
     * @return Referral|null
     */
    public function processRegistrationReferral(
        User $newUser,
        ?string $referralCode,
        array $metadata = []
    ): ?Referral {
        if (empty($referralCode)) {
            return null;
        }

        try {
            DB::beginTransaction();

            // Find referrer by code
            $referrerCode = ReferralCode::where('code', $referralCode)
                ->where('is_active', true)
                ->first();

            if (!$referrerCode) {
                Log::warning('Invalid referral code used', [
                    'code' => $referralCode,
                    'new_user_id' => $newUser->id,
                ]);
                DB::rollBack();
                return null;
            }

            $referrer = $referrerCode->user;

            // Prevent self-referral
            if ($referrer->id === $newUser->id) {
                Log::warning('Self-referral attempted', [
                    'user_id' => $newUser->id,
                ]);
                DB::rollBack();
                return null;
            }

            // Check if already referred
            if ($newUser->isReferred()) {
                Log::warning('User already referred', [
                    'user_id' => $newUser->id,
                    'previous_referrer' => $newUser->referred_by_user_id,
                ]);
                DB::rollBack();
                return null;
            }

            // Calculate rewards
            $referrerPendingPoints = config('referral.rewards.referrer.completion_points', 1000);
            $referredWelcomePoints = config('referral.rewards.referred.welcome_points', 500);

            // Create referral record
            $referral = Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $newUser->id,
                'referral_code' => $referralCode,
                'status' => 'pending',
                'referrer_points_pending' => $referrerPendingPoints,
                'referred_points_earned' => $referredWelcomePoints,
                'ip_address' => $metadata['ip_address'] ?? null,
                'user_agent' => $metadata['user_agent'] ?? null,
                'metadata' => $metadata,
            ]);

            // Update new user record
            $newUser->update([
                'referred_by_user_id' => $referrer->id,
                'referred_by_code' => $referralCode,
                'referral_registered_at' => now(),
            ]);

            // Update referral code stats
            $referrerCode->incrementReferral('pending');

            // Award welcome bonus points to new user
            $this->awardWelcomeBonus($newUser, $referredWelcomePoints);

            // Generate welcome coupon for new user
            $welcomeCoupon = $this->generateWelcomeCoupon($newUser);
            if ($welcomeCoupon) {
                $referral->update(['referred_coupon_id' => $welcomeCoupon->id]);
            }

            DB::commit();

            // Send email notifications
            $this->sendReferralSignupNotifications($referrer, $newUser, $referral);

            Log::info('Referral processed successfully', [
                'referrer_id' => $referrer->id,
                'referred_id' => $newUser->id,
                'referral_id' => $referral->id,
            ]);

            return $referral;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process registration referral', [
                'error' => $e->getMessage(),
                'new_user_id' => $newUser->id,
                'referral_code' => $referralCode,
            ]);
            return null;
        }
    }

    /**
     * Process referral completion after first payment
     * 
     * @param Payment $payment
     * @return bool
     */
    public function processReferralCompletion(Payment $payment): bool
    {
        try {
            $user = $payment->client;

            // Check if user was referred
            if (!$user->isReferred()) {
                return false;
            }

            // Find the referral record
            $referral = Referral::where('referred_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$referral) {
                return false;
            }

            DB::beginTransaction();

            // Mark referral as completed
            $referral->markCompleted($payment);

            // Award points to referrer
            $this->awardReferrerCompletionReward($referral);

            // Generate reward coupon for referrer
            $referrerCoupon = $this->generateReferrerRewardCoupon($referral->referrer);
            if ($referrerCoupon) {
                $referral->update(['referrer_coupon_id' => $referrerCoupon->id]);
            }

            // Update referral code stats
            $referrerCode = $referral->referrer->referralCode;
            if ($referrerCode) {
                $referrerCode->incrementReferral('completed');
                $referrerCode->addEarnings($referral->referrer_points_earned);
            }

            // Mark as rewarded
            $referral->markRewarded();

            DB::commit();

            // Send completion notifications
            $this->sendReferralCompletionNotifications($referral);

            Log::info('Referral completed successfully', [
                'referral_id' => $referral->id,
                'payment_id' => $payment->id,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process referral completion', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
            return false;
        }
    }

    /**
     * Award welcome bonus to new referred user
     */
    protected function awardWelcomeBonus(User $user, int $points): void
    {
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $loyaltyPoint->earnPoints(
            $points,
            'referral_welcome',
            "Welcome bonus for joining via referral",
            null
        );
    }

    /**
     * Award completion reward to referrer
     */
    protected function awardReferrerCompletionReward(Referral $referral): void
    {
        $loyaltyPoint = $referral->referrer->getOrCreateLoyaltyPoints();
        $points = $referral->referrer_points_pending;

        $loyaltyPoint->earnPoints(
            $points,
            'referral_completion',
            "Referral bonus for {$referral->referred->fullName}'s first payment",
            $referral
        );

        $referral->update(['referrer_points_earned' => $points]);
    }

    /**
     * Generate welcome coupon for new user
     */
    protected function generateWelcomeCoupon(User $user): ?Coupon
    {
        try {
            $discountValue = config('referral.rewards.referred.coupon_discount', 15);
            $validDays = config('referral.rewards.referred.coupon_validity_days', 30);

            $couponData = [
                'code' => 'WELCOME' . strtoupper(substr($user->fullName, 0, 4)) . rand(100, 999),
                'name' => 'Welcome Referral Discount',
                'description' => 'Special discount for joining via referral!',
                'discount_type' => 'percentage',
                'discount_value' => $discountValue,
                'min_purchase_amount' => 1000,
                'valid_until' => now()->addDays($validDays),
                'stackable_with_loyalty_tier' => true,
                'stackable_with_points' => true,
            ];

            return $this->couponService->createUserSpecificCoupon(
                $couponData,
                $user,
                $user // Created by the user themselves (auto-generated)
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate welcome coupon', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return null;
        }
    }

    /**
     * Generate reward coupon for referrer
     */
    protected function generateReferrerRewardCoupon(User $referrer): ?Coupon
    {
        try {
            $discountValue = config('referral.rewards.referrer.coupon_discount', 20);
            $validDays = config('referral.rewards.referrer.coupon_validity_days', 60);

            $couponData = [
                'code' => 'REFERRAL' . strtoupper(substr($referrer->fullName, 0, 4)) . rand(100, 999),
                'name' => 'Referral Reward Discount',
                'description' => 'Thank you for referring new clients!',
                'discount_type' => 'percentage',
                'discount_value' => $discountValue,
                'min_purchase_amount' => 2000,
                'valid_until' => now()->addDays($validDays),
                'stackable_with_loyalty_tier' => true,
                'stackable_with_points' => false, // Higher value, exclusive
            ];

            return $this->couponService->createUserSpecificCoupon(
                $couponData,
                $referrer,
                $referrer // Auto-generated
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate referrer reward coupon', [
                'error' => $e->getMessage(),
                'referrer_id' => $referrer->id,
            ]);
            return null;
        }
    }

    /**
     * Get referral statistics for a user
     */
    public function getReferralStats(User $user): array
    {
        $referralCode = $user->referralCode;
        
        if (!$referralCode) {
            return [
                'code' => null,
                'total_referrals' => 0,
                'successful_referrals' => 0,
                'pending_referrals' => 0,
                'lifetime_earnings' => 0,
                'conversion_rate' => 0,
            ];
        }

        return [
            'code' => $referralCode->code,
            'total_referrals' => $referralCode->total_referrals,
            'successful_referrals' => $referralCode->successful_referrals,
            'pending_referrals' => $referralCode->pending_referrals,
            'lifetime_earnings' => $referralCode->lifetime_earnings_points,
            'conversion_rate' => $referralCode->getConversionRate(),
            'last_used' => $referralCode->last_used_at,
        ];
    }

    /**
     * Validate referral code
     */
    public function validateReferralCode(string $code): array
    {
        $referralCode = ReferralCode::where('code', $code)
            ->where('is_active', true)
            ->with('user')
            ->first();

        if (!$referralCode) {
            return [
                'valid' => false,
                'message' => 'Invalid or inactive referral code.',
            ];
        }

        return [
            'valid' => true,
            'referrer_name' => $referralCode->user->fullName,
            'message' => 'Valid referral code!',
        ];
    }

    /**
     * Send signup notifications
     */
    protected function sendReferralSignupNotifications(
        User $referrer,
        User $referred,
        Referral $referral
    ): void {
        try {
            // Email to referrer
            Mail::to($referrer->email)
                ->queue(new ReferralSignupMail($referrer, $referred, $referral));

            // Email to referred user (welcome)
            // Handled by registration flow
        } catch (\Exception $e) {
            Log::error('Failed to send referral signup notifications', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send completion notifications
     */
    protected function sendReferralCompletionNotifications(Referral $referral): void
    {
        try {
            // Email to referrer
            Mail::to($referral->referrer->email)
                ->queue(new ReferralCompletedMail($referral));
        } catch (\Exception $e) {
            Log::error('Failed to send referral completion notifications', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
```

---

## 📝 Configuration File

**File**: `config/referral.php`

```php
<?php

return [
    // Reward System
    'rewards' => [
        'referrer' => [
            'completion_points' => 1000,  // Points when referred user completes first payment
            'coupon_discount' => 20,       // 20% discount coupon
            'coupon_validity_days' => 60,  // Valid for 60 days
        ],
        'referred' => [
            'welcome_points' => 500,       // Immediate signup bonus
            'coupon_discount' => 15,       // 15% welcome discount
            'coupon_validity_days' => 30,  // Valid for 30 days
        ],
    ],

    // Code Generation
    'code' => [
        'format' => 'name_year_random', // FirstName + Year + Random
        'length' => 11,                 // Total length
        'uppercase' => true,            // Force uppercase
    ],

    // Eligibility
    'eligibility' => [
        'min_account_age_days' => 0,    // Minimum days before can refer
        'allowed_roles' => ['client'],  // Who can participate
        'one_referral_per_user' => true, // Each user can only be referred once
    ],

    // Tracking
    'tracking' => [
        'cookie_duration_days' => 30,   // How long to track referral in cookies
        'require_first_payment' => true, // Must complete payment to count
    ],
];
```

---

**This is Part 1 of the Implementation Plan. Shall I continue with the remaining phases?**

**Remaining sections:**
- Phase 4: Controllers (Admin & Client)
- Phase 5: Routes
- Phase 6: Frontend Views
- Phase 7: Email Notifications
- Phase 8: Integration Points
- Phase 9: Analytics & Reporting
- Phase 10: Testing Strategy

**Total estimated implementation: 2-3 days**

Would you like me to continue with the complete plan?
