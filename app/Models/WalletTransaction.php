<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'transaction_type',
        'source_type',
        'source_id',
        'amount',
        'balance_before',
        'balance_after',
        'wallet_type',
        'description',
        'metadata',
        'performed_by',
        'payout_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Transaction type constants
     */
    const TYPE_WORK_EARNED = 'work_earned';
    const TYPE_REFERRAL_EARNED = 'referral_earned';
    const TYPE_WITHDRAWN = 'withdrawn';
    const TYPE_ADJUSTED = 'adjusted';
    const TYPE_REFUNDED = 'refunded';
    const TYPE_PENDING = 'pending';
    const TYPE_CANCELLED = 'cancelled';

    /**
     * Source type constants
     */
    const SOURCE_TIME_ENTRY = 'time_entry';
    const SOURCE_FIXED_RATE = 'project_fixed_rate';
    const SOURCE_REFERRAL = 'referral_completion';
    const SOURCE_WITHDRAWAL = 'withdrawal_request';
    const SOURCE_ADMIN_ADJUSTMENT = 'admin_adjustment';
    const SOURCE_REVERSAL = 'reversal';

    /**
     * Wallet type constants
     */
    const WALLET_WORK_EARNINGS = 'work_earnings';
    const WALLET_REFERRAL_CREDITS = 'referral_credits';

    /**
     * Get the user who owns this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who performed this transaction.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Check if this is a credit transaction (adds money).
     */
    public function isCredit(): bool
    {
        return $this->amount > 0;
    }

    /**
     * Check if this is a debit transaction (removes money).
     */
    public function isDebit(): bool
    {
        return $this->amount < 0;
    }

    /**
     * Get absolute amount for display.
     */
    public function getAbsoluteAmountAttribute(): float
    {
        return abs($this->amount);
    }

    /**
     * Get formatted amount with currency symbol.
     */
    public function getFormattedAmountAttribute(): string
    {
        $prefix = $this->isCredit() ? '+' : '';
        return $prefix . '₱' . number_format($this->amount, 2);
    }

    /**
     * Get transaction type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->transaction_type) {
            self::TYPE_WORK_EARNED => 'Work Earned',
            self::TYPE_REFERRAL_EARNED => 'Referral Bonus',
            self::TYPE_WITHDRAWN => 'Withdrawn',
            self::TYPE_ADJUSTED => 'Adjustment',
            self::TYPE_REFUNDED => 'Refunded',
            self::TYPE_PENDING => 'Pending Withdrawal',
            self::TYPE_CANCELLED => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->transaction_type)),
        };
    }

    /**
     * Get transaction icon for UI.
     */
    public function getIconAttribute(): string
    {
        return match($this->transaction_type) {
            self::TYPE_WORK_EARNED => '✅',
            self::TYPE_REFERRAL_EARNED => '💰',
            self::TYPE_WITHDRAWN => '⬇️',
            self::TYPE_ADJUSTED => '🔧',
            self::TYPE_REFUNDED => '↩️',
            self::TYPE_PENDING => '⏳',
            self::TYPE_CANCELLED => '❌',
            default => '📝',
        };
    }

    /**
     * Get CSS class for transaction display.
     */
    public function getCssClassAttribute(): string
    {
        return match($this->transaction_type) {
            self::TYPE_WORK_EARNED, self::TYPE_REFERRAL_EARNED, self::TYPE_REFUNDED => 'text-green-600',
            self::TYPE_WITHDRAWN, self::TYPE_PENDING => 'text-red-600',
            self::TYPE_ADJUSTED => 'text-yellow-600',
            self::TYPE_CANCELLED => 'text-gray-500',
            default => 'text-gray-700',
        };
    }

    /**
     * Scope to filter by wallet type.
     */
    public function scopeForWallet($query, string $walletType)
    {
        return $query->where('wallet_type', $walletType);
    }

    /**
     * Scope to filter work earnings transactions.
     */
    public function scopeWorkEarnings($query)
    {
        return $query->forWallet(self::WALLET_WORK_EARNINGS);
    }

    /**
     * Scope to filter referral credit transactions.
     */
    public function scopeReferralCredits($query)
    {
        return $query->forWallet(self::WALLET_REFERRAL_CREDITS);
    }

    /**
     * Scope to filter by transaction type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Scope to get credit transactions only.
     */
    public function scopeCredits($query)
    {
        return $query->where('amount', '>', 0);
    }

    /**
     * Scope to get debit transactions only.
     */
    public function scopeDebits($query)
    {
        return $query->where('amount', '<', 0);
    }

    /**
     * Create a work earned transaction from time entry approval.
     */
    public static function createFromTimeEntry(User $adiutor, TimeEntry $timeEntry, ?int $performedBy = null): self
    {
        $balanceBefore = $adiutor->work_earnings_balance;
        
        return self::create([
            'user_id' => $adiutor->id,
            'transaction_type' => self::TYPE_WORK_EARNED,
            'source_type' => self::SOURCE_TIME_ENTRY,
            'source_id' => $timeEntry->id,
            'amount' => $timeEntry->calculated_amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $timeEntry->calculated_amount,
            'wallet_type' => self::WALLET_WORK_EARNINGS,
            'description' => "Time entry approved for: {$timeEntry->description}",
            'metadata' => [
                'project_id' => $timeEntry->project_id,
                'task_id' => $timeEntry->task_id,
                'hours' => round($timeEntry->duration_minutes / 60, 2),
                'rate' => $timeEntry->hourly_rate,
            ],
            'performed_by' => $performedBy,
        ]);
    }

    /**
     * Create a work earned transaction from fixed rate approval.
     */
    public static function createFromFixedRate(User $adiutor, ProjectAssignment $assignment, float $amount, ?int $performedBy = null): self
    {
        $balanceBefore = $adiutor->work_earnings_balance;
        
        return self::create([
            'user_id' => $adiutor->id,
            'transaction_type' => self::TYPE_WORK_EARNED,
            'source_type' => self::SOURCE_FIXED_RATE,
            'source_id' => $assignment->id,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $amount,
            'wallet_type' => self::WALLET_WORK_EARNINGS,
            'description' => "Fixed rate payment for project assignment",
            'metadata' => [
                'project_id' => $assignment->project_id,
                'agreed_rate' => $assignment->agreed_rate,
            ],
            'performed_by' => $performedBy,
        ]);
    }

    /**
     * Create an admin adjustment transaction.
     */
    public static function createAdjustment(User $adiutor, float $amount, string $reason, string $walletType, int $performedBy): self
    {
        $balanceField = $walletType === self::WALLET_WORK_EARNINGS 
            ? 'work_earnings_balance' 
            : 'referral_credits';
        $balanceBefore = $adiutor->$balanceField;
        
        return self::create([
            'user_id' => $adiutor->id,
            'transaction_type' => self::TYPE_ADJUSTED,
            'source_type' => self::SOURCE_ADMIN_ADJUSTMENT,
            'source_id' => null,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $amount,
            'wallet_type' => $walletType,
            'description' => "Admin adjustment: {$reason}",
            'metadata' => [
                'reason' => $reason,
            ],
            'performed_by' => $performedBy,
        ]);
    }
}
