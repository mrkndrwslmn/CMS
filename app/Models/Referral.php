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
