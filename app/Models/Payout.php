<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_number',
        'adiutor_id',
        'processed_by',
        'amount',
        'currency',
        'status',
        'payout_method',
        'period_start',
        'period_end',
        'payout_details',
        'notes',
        'adiutor_notes',
        'reference_number',
        'proof_of_payment',
        'requested_at',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payout_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    /**
     * Get the adiutor receiving this payout
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Get the admin who processed this payout
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get all items in this payout
     */
    public function items(): HasMany
    {
        return $this->hasMany(PayoutItem::class);
    }

    /**
     * Get time entries included in this payout
     */
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Get fixed rate project assignments included in this payout
     */
    public function fixedRateAssignments()
    {
        return $this->hasMany(ProjectAssignment::class, 'fixed_rate_payout_id');
    }

    /**
     * Check if payout is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payout is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if payout is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payout is completed (alias for isPaid for backwards compatibility)
     */
    public function isCompleted(): bool
    {
        return $this->isPaid();
    }

    /**
     * Check if payout is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'paid' => 'bg-green-100 text-green-800',
            'completed' => 'bg-green-100 text-green-800', // backwards compatibility
            'cancelled' => 'bg-red-100 text-red-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Generate payout number
     */
    public static function generatePayoutNumber(): string
    {
        $year = date('Y');
        $latestPayout = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = $latestPayout ? (int) substr($latestPayout->payout_number, -3) + 1 : 1;

        return sprintf('PAYOUT-%s-%03d', $year, $number);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing($processedBy = null)
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now(),
            'processed_by' => $processedBy ?? auth()->id(),
        ]);
    }

    /**
     * Mark as paid
     */
    public function markAsPaid($referenceNumber = null, $proofPath = null)
    {
        $this->update([
            'status' => 'paid',
            'completed_at' => now(),
            'reference_number' => $referenceNumber ?? $this->reference_number,
            'proof_of_payment' => $proofPath ?? $this->proof_of_payment,
        ]);

        // Mark all time entries as paid
        $this->timeEntries()->update(['is_paid' => true]);
    }

    /**
     * Mark as completed (alias for markAsPaid for backwards compatibility)
     */
    public function markAsCompleted($referenceNumber = null, $proofPath = null)
    {
        return $this->markAsPaid($referenceNumber, $proofPath);
    }

    /**
     * Cancel payout
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $this->notes ? $this->notes . "\n\nCancelled: " . $reason : "Cancelled: " . $reason,
        ]);

        // Release time entries back to unpaid
        $this->timeEntries()->update(['payout_id' => null, 'is_paid' => false]);

        // Release fixed rate assignments, milestones, and referral credits
        foreach ($this->items as $item) {
            if (in_array($item->item_type, ['fixed_task', 'fixed_rate']) && $item->project_id) {
                $assignment = ProjectAssignment::where('project_id', $item->project_id)
                    ->where('adiutor_id', $this->adiutor_id)
                    ->where('payment_type', ProjectAssignment::PAYMENT_TYPE_FIXED)
                    ->first();
                if ($assignment && $assignment->fixed_rate_payout_id === $this->id) {
                    $assignment->update([
                        'fixed_rate_paid' => false,
                        'fixed_rate_payout_id' => null,
                    ]);
                }
            } elseif ($item->item_type === 'milestone' && $item->milestone_id) {
                // Reset milestone adiutor payment tracking
                $milestone = ProjectMilestone::find($item->milestone_id);
                if ($milestone && $milestone->adiutor_payout_id === $this->id) {
                    $milestone->update([
                        'adiutor_paid' => false,
                        'adiutor_paid_at' => null,
                        'adiutor_payout_id' => null,
                    ]);
                }
            }
            // Referral credits don't need restoration - they were never moved during request
            // They stay in referral_credits balance until payout is paid
        }
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return '₱' . number_format($this->amount, 2);
    }

    /**
     * Calculate total hours from items
     */
    public function getTotalHours(): float
    {
        return $this->items()
            ->whereNotNull('hours')
            ->sum('hours');
    }
}
