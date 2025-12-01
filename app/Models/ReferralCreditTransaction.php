<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCreditTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'balance_before',
        'balance_after',
        'source',
        'description',
        'referral_id',
        'withdrawal_id',
        'performed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Get the user this transaction belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related referral (if any)
     */
    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    /**
     * Get the related withdrawal (if any)
     */
    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(ReferralCreditWithdrawal::class, 'withdrawal_id');
    }

    /**
     * Get the user who performed this transaction (for admin adjustments)
     */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Check if transaction is earnings
     */
    public function isEarned(): bool
    {
        return $this->transaction_type === 'earned';
    }

    /**
     * Check if transaction is withdrawal
     */
    public function isWithdrawn(): bool
    {
        return $this->transaction_type === 'withdrawn';
    }

    /**
     * Check if transaction is refund
     */
    public function isRefunded(): bool
    {
        return $this->transaction_type === 'refunded';
    }

    /**
     * Check if transaction is adjustment
     */
    public function isAdjusted(): bool
    {
        return $this->transaction_type === 'adjusted';
    }
}
