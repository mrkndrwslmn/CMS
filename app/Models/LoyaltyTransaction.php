<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_type',
        'points',
        'balance_before',
        'balance_after',
        'source',
        'description',
        'service_request_id',
        'payment_id',
        'coupon_id',
        'performed_by',
        'expires_at',
        'expired',
        'expiry_warning_sent_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'expired' => 'boolean',
        'expiry_warning_sent_at' => 'datetime',
    ];

    /**
     * Get the user who owns this transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service request related to this transaction
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the payment related to this transaction
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the coupon related to this transaction
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the admin who performed manual adjustment
     */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Check if transaction is an earning
     */
    public function isEarned(): bool
    {
        return $this->transaction_type === 'earned';
    }

    /**
     * Check if transaction is a redemption
     */
    public function isRedeemed(): bool
    {
        return $this->transaction_type === 'redeemed';
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        return $this->expired || ($this->expires_at && now()->gt($this->expires_at));
    }

    /**
     * Check if transaction is expiring soon
     */
    public function isExpiringSoon(int $days = 30): bool
    {
        if (!$this->expires_at || $this->expired) {
            return false;
        }

        return now()->diffInDays($this->expires_at, false) <= $days;
    }

    /**
     * Get absolute points value
     */
    public function getAbsolutePoints(): int
    {
        return abs($this->points);
    }

    /**
     * Get transaction type label
     */
    public function getTypeLabel(): string
    {
        return match($this->transaction_type) {
            'earned' => 'Points Earned',
            'redeemed' => 'Points Redeemed',
            'expired' => 'Points Expired',
            'adjusted' => 'Manual Adjustment',
            'refunded' => 'Points Refunded',
            default => 'Unknown'
        };
    }

    /**
     * Get transaction type color
     */
    public function getTypeColor(): string
    {
        return match($this->transaction_type) {
            'earned' => 'success',
            'redeemed' => 'warning',
            'expired' => 'error',
            'adjusted' => 'info',
            'refunded' => 'primary',
            default => 'neutral'
        };
    }

    /**
     * Get transaction icon
     */
    public function getTypeIcon(): string
    {
        return match($this->transaction_type) {
            'earned' => 'fa-plus-circle',
            'redeemed' => 'fa-minus-circle',
            'expired' => 'fa-clock',
            'adjusted' => 'fa-edit',
            'refunded' => 'fa-undo',
            default => 'fa-circle'
        };
    }

    /**
     * Scope: Get earned transactions
     */
    public function scopeEarned($query)
    {
        return $query->where('transaction_type', 'earned');
    }

    /**
     * Scope: Get redeemed transactions
     */
    public function scopeRedeemed($query)
    {
        return $query->where('transaction_type', 'redeemed');
    }

    /**
     * Scope: Get expired transactions
     */
    public function scopeExpired($query)
    {
        return $query->where('transaction_type', 'expired')
            ->orWhere('expired', true);
    }

    /**
     * Scope: Get non-expired earned points
     */
    public function scopeActiveEarned($query)
    {
        return $query->where('transaction_type', 'earned')
            ->where('expired', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope: Get transactions expiring soon
     */
    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('transaction_type', 'earned')
            ->where('expired', false)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    /**
     * Scope: Get transactions for a user
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope: Get recent transactions
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
