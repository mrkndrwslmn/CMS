<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_purchase_amount',
        'coupon_type',
        'specific_user_id',
        'specific_request_id',
        'max_total_uses',
        'max_uses_per_user',
        'current_uses',
        'valid_from',
        'valid_until',
        'status',
        'created_by',
        'admin_notes',
        'stackable_with_loyalty_tier',
        'stackable_with_points',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'stackable_with_loyalty_tier' => 'boolean',
        'stackable_with_points' => 'boolean',
    ];

    /**
     * Get the admin who created this coupon
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the specific user this coupon is for (if user-specific)
     */
    public function specificUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'specific_user_id');
    }

    /**
     * Get the specific request this coupon is for (if request-specific)
     */
    public function specificRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'specific_request_id');
    }

    /**
     * Get all usage records for this coupon
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Get service requests that used this coupon
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'applied_coupon_id');
    }

    /**
     * Check if coupon is currently valid (all conditions met)
     */
    public function isValid(): bool
    {
        // Check status
        if ($this->status !== 'active') {
            return false;
        }

        // Check validity period
        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        // Check usage limits
        if (!$this->hasUsageLeft()) {
            return false;
        }

        return true;
    }

    /**
     * Check if coupon has usage left
     */
    public function hasUsageLeft(): bool
    {
        if ($this->max_total_uses === null) {
            return true; // Unlimited
        }

        return $this->current_uses < $this->max_total_uses;
    }

    /**
     * Check if coupon is expired based on date
     */
    public function isExpired(): bool
    {
        if (!$this->valid_until) {
            return false;
        }

        return now()->gt($this->valid_until);
    }

    /**
     * Check if a specific user can use this coupon
     */
    public function canBeUsedBy(User $user): bool
    {
        // Check basic validity first
        if (!$this->isValid()) {
            return false;
        }

        // Check coupon type visibility
        if ($this->coupon_type === 'user_specific' && $this->specific_user_id !== $user->id) {
            return false;
        }

        if ($this->coupon_type === 'request_specific') {
            return false; // Request-specific coupons are auto-applied, not manually usable
        }

        // Check per-user usage limit
        $userUsageCount = $this->usages()
            ->where('user_id', $user->id)
            ->where('payment_status', '!=', 'failed')
            ->count();

        if ($userUsageCount >= $this->max_uses_per_user) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the discount amount for a given purchase amount
     */
    public function calculateDiscount(float $amount): float
    {
        if ($amount < $this->min_purchase_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->discount_type === 'percentage') {
            $discount = ($amount * $this->discount_value) / 100;

            // Apply max discount cap if set
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = (float) $this->max_discount_amount;
            }
        } else {
            // Fixed amount
            $discount = (float) $this->discount_value;
        }

        // Discount cannot exceed the purchase amount
        return min($discount, $amount);
    }

    /**
     * Increment the usage counter
     */
    public function incrementUsage(): void
    {
        $this->increment('current_uses');

        // Auto-expire if max uses reached
        if ($this->max_total_uses && $this->current_uses >= $this->max_total_uses) {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Decrement the usage counter (e.g., on payment failure)
     */
    public function decrementUsage(): void
    {
        if ($this->current_uses > 0) {
            $this->decrement('current_uses');

            // Reactivate if was expired due to usage
            if ($this->status === 'expired' && !$this->isExpired()) {
                $this->update(['status' => 'active']);
            }
        }
    }

    /**
     * Get discount type label
     */
    public function getDiscountLabel(): string
    {
        if ($this->discount_type === 'percentage') {
            return number_format($this->discount_value, 0) . '% OFF';
        }

        return '₱' . number_format($this->discount_value, 0) . ' OFF';
    }

    /**
     * Get coupon type label
     */
    public function getCouponTypeLabel(): string
    {
        return match($this->coupon_type) {
            'public' => 'Public Coupon',
            'user_specific' => 'User-Specific',
            'request_specific' => 'Request-Specific',
            default => 'Unknown'
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'warning',
            'expired' => 'error',
            default => 'neutral'
        };
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpiration(): ?int
    {
        if (!$this->valid_until) {
            return null;
        }

        $days = now()->diffInDays($this->valid_until, false);
        return $days > 0 ? (int) $days : 0;
    }

    /**
     * Check if coupon is expiring soon (within 7 days)
     */
    public function isExpiringSoon(): bool
    {
        $days = $this->getDaysUntilExpiration();
        return $days !== null && $days <= 7 && $days > 0;
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentage(): float
    {
        if (!$this->max_total_uses) {
            return 0; // Unlimited
        }

        return min(100, ($this->current_uses / $this->max_total_uses) * 100);
    }

    /**
     * Scope: Get active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('valid_from')
                  ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', now());
            });
    }

    /**
     * Scope: Get public coupons
     */
    public function scopePublic($query)
    {
        return $query->where('coupon_type', 'public');
    }

    /**
     * Scope: Get coupons visible to a specific user
     */
    public function scopeVisibleToUser($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            // Public coupons
            $q->where('coupon_type', 'public')
              // User-specific coupons for this user
              ->orWhere(function ($subQ) use ($user) {
                  $subQ->where('coupon_type', 'user_specific')
                       ->where('specific_user_id', $user->id);
              });
        });
    }

    /**
     * Scope: Get coupons expiring soon
     */
    public function scopeExpiringSoon($query, int $days = 7)
    {
        return $query->where('status', 'active')
            ->whereNotNull('valid_until')
            ->whereBetween('valid_until', [now(), now()->addDays($days)]);
    }

    /**
     * Static: Get available coupons for a user
     */
    public static function getAvailableForUser(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return self::active()
            ->visibleToUser($user)
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(fn($coupon) => $coupon->canBeUsedBy($user));
    }
}
