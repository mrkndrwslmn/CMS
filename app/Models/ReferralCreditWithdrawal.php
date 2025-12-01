<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReferralCreditWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'withdrawal_number',
        'user_id',
        'amount',
        'status',
        'withdrawal_method',
        'withdrawal_details',
        'processed_by',
        'requested_at',
        'processed_at',
        'completed_at',
        'reference_number',
        'proof_of_payment',
        'user_notes',
        'admin_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'withdrawal_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who requested this withdrawal
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed this withdrawal
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the credit transactions related to this withdrawal
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(ReferralCreditTransaction::class, 'withdrawal_id');
    }

    /**
     * Generate a unique withdrawal number
     */
    public static function generateWithdrawalNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'RW-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if withdrawal is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if withdrawal is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if withdrawal is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if withdrawal is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Mark withdrawal as processing
     */
    public function markProcessing(int $processedById): void
    {
        $this->update([
            'status' => 'processing',
            'processed_by' => $processedById,
            'processed_at' => now(),
        ]);
    }

    /**
     * Mark withdrawal as completed
     */
    public function markCompleted(string $referenceNumber, ?string $proofPath = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'reference_number' => $referenceNumber,
            'proof_of_payment' => $proofPath,
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Mark withdrawal as rejected
     */
    public function markRejected(string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }
}
