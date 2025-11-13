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
