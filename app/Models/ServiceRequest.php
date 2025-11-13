<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'contact_method',
        'contact_details',
        'service_type',
        'project_name',
        'request_description',
        'deadline',
        'expectations',
        'additional_notes',
        'status',
        'priority',
        'estimated_budget',
        'approved_budget',
        'requirements',
        'approved_at',
        'approved_by',
        'payment_method',
        'payment_due_date',
        'payment_confirmed_at',
        'payment_reference',
        'payment_instructions',
        'admin_notes',
        'rejection_reason',
        'reviewed_at',
        // Milestone payment fields
        'payment_type',
        'downpayment_percentage',
        'downpayment_amount',
        'remaining_balance',
        'downpayment_paid',
        'downpayment_paid_at',
        'remaining_balance_paid',
        'remaining_balance_paid_at',
        'total_milestones',
        // Coupon fields
        'applied_coupon_id',
        'original_approved_budget',
        'coupon_discount_amount',
        'coupon_applied_at',
        'coupon_auto_applied',
        // Loyalty fields
        'loyalty_points_used',
        'loyalty_discount_amount',
        'loyalty_applied_at',
        'loyalty_points_earned',
        'loyalty_points_awarded',
        'loyalty_points_awarded_at',
        'total_discount_amount',
        'discount_percentage',
    ];

    protected $casts = [
        'deadline' => 'date',
        'requirements' => 'array',
        'approved_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'payment_due_date' => 'datetime',
        'payment_confirmed_at' => 'datetime',
        'estimated_budget' => 'decimal:2',
        'approved_budget' => 'decimal:2',
        // Milestone payment casts
        'downpayment_percentage' => 'decimal:2',
        'downpayment_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'downpayment_paid' => 'boolean',
        'downpayment_paid_at' => 'datetime',
        'remaining_balance_paid' => 'boolean',
        'remaining_balance_paid_at' => 'datetime',
        // Coupon casts
        'original_approved_budget' => 'decimal:2',
        'coupon_discount_amount' => 'decimal:2',
        'coupon_applied_at' => 'datetime',
        'coupon_auto_applied' => 'boolean',
        // Loyalty casts
        'loyalty_discount_amount' => 'decimal:2',
        'loyalty_applied_at' => 'datetime',
        'loyalty_points_awarded' => 'boolean',
        'loyalty_points_awarded_at' => 'datetime',
        'total_discount_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
    ];

    /**
     * Get the client that owns the service request
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the user (client) that owns the service request
     * Alias for client() relationship for backward compatibility
     */
    public function user(): BelongsTo
    {
        return $this->client();
    }

    /**
     * Get the admin who approved the request
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the attachments for this request
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class);
    }

    /**
     * Get the applied coupon for this request
     */
    public function appliedCoupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'applied_coupon_id');
    }

    /**
     * Get coupon usage record for this request
     */
    public function couponUsage(): HasOne
    {
        return $this->hasOne(CouponUsage::class);
    }

    /**
     * Get the project created from this request 
     * When admin approves + client pays → ServiceRequest becomes Project
     */
    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'service_request_id');
    }

    /**
     * Get all documents for this service request (polymorphic).
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Get documents using legacy foreign key.
     */
    public function directDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'service_request_id');
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is pending payment
     */
    public function isPendingPayment(): bool
    {
        return $this->status === 'pending_payment';
    }

    /**
     * Check if request is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get tasks associated with this service request
     * @deprecated Tasks should belong to PROJECTS, not service requests directly
     * Use $serviceRequest->project->tasks instead
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'service_request_id');
    }

    /**
     * Get payments for this service request
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get milestone payments for this service request
     */
    public function milestonePayments(): HasMany
    {
        return $this->hasMany(MilestonePayment::class);
    }

    /**
     * Check if this request uses full payment type
     */
    public function isFullPayment(): bool
    {
        return $this->payment_type === 'full_payment';
    }

    /**
     * Check if this request uses milestone payment type
     */
    public function isMilestonePayment(): bool
    {
        return $this->payment_type === 'milestone_payment';
    }

    /**
     * Check if this request uses downpayment type
     */
    public function isDownpayment(): bool
    {
        return $this->payment_type === 'downpayment';
    }

    /**
     * Check if downpayment has been paid
     */
    public function isDownpaymentPaid(): bool
    {
        return $this->downpayment_paid;
    }

    /**
     * Check if remaining balance has been paid (for downpayment type)
     */
    public function isRemainingBalancePaid(): bool
    {
        return $this->remaining_balance_paid;
    }

    /**
     * Calculate downpayment amount based on percentage
     */
    public function calculateDownpaymentAmount(): float
    {
        if (!$this->approved_budget || !$this->downpayment_percentage) {
            return 0;
        }
        return round(($this->approved_budget * $this->downpayment_percentage) / 100, 2);
    }

    /**
     * Calculate remaining balance after downpayment
     */
    public function calculateRemainingBalance(): float
    {
        if (!$this->approved_budget || !$this->downpayment_amount) {
            return $this->approved_budget ?? 0;
        }
        return round($this->approved_budget - $this->downpayment_amount, 2);
    }

    /**
     * Get the payment type label
     */
    public function getPaymentTypeLabel(): string
    {
        return match($this->payment_type) {
            'full_payment' => 'Full Payment',
            'milestone_payment' => 'Milestone Payment',
            'downpayment' => 'Downpayment',
            default => 'Not Set'
        };
    }

    /**
     * Check if initial payment has been made
     * For full_payment: check if full amount paid
     * For milestone_payment: check if phase 1 paid
     * For downpayment: check if downpayment paid
     */
    public function hasInitialPayment(): bool
    {
        if ($this->isFullPayment()) {
            return $this->isPaid();
        }

        if ($this->isMilestonePayment()) {
            // Check if first milestone is paid
            $firstMilestone = $this->project?->milestones()->ordered()->first();
            return $firstMilestone?->isPaid() ?? false;
        }

        if ($this->isDownpayment()) {
            return $this->isDownpaymentPaid();
        }

        return false;
    }

    /**
     * Get total allocated budget for all tasks
     */
    public function getTotalAllocatedBudget(): float
    {
        return $this->tasks()->sum('allocated_budget') ?? 0;
    }

    /**
     * Get remaining budget
     */
    public function getRemainingBudget(): float
    {
        if (!$this->approved_budget) {
            return 0;
        }
        return $this->approved_budget - $this->getTotalAllocatedBudget();
    }

    /**
     * Check if budget is exceeded
     */
    public function isBudgetExceeded(): bool
    {
        return $this->getRemainingBudget() < 0;
    }

    /**
     * Get formatted deadline
     */
    public function getFormattedDeadline(): string
    {
        return $this->deadline ? \Carbon\Carbon::parse($this->deadline)->format('M d, Y') : 'No deadline set';
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'error',
            'pending_payment' => 'info',
            'paid' => 'primary',
            'in_progress' => 'primary',
            'completed' => 'success',
            default => 'neutral'
        };
    }

    /**
     * Get human readable status
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'pending_payment' => 'Pending Payment',
            'paid' => 'Payment Confirmed',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            default => 'Unknown'
        };
    }

    /**
     * Get the current payment amount due for the client
     * This varies based on payment type:
     * - full_payment: full approved budget
     * - milestone_payment: amount for current/next unpaid milestone phase
     * - downpayment: downpayment amount (if not paid) or remaining balance
     */
    public function getCurrentPaymentAmountDue(): float
    {
        if (!$this->approved_budget) {
            return 0;
        }

        // Full payment: return full amount
        if ($this->isFullPayment() || !$this->payment_type) {
            return (float) $this->approved_budget;
        }

        // Downpayment: return downpayment if not paid, else remaining balance
        if ($this->isDownpayment()) {
            if (!$this->downpayment_paid) {
                return (float) ($this->downpayment_amount ?? $this->calculateDownpaymentAmount());
            }
            
            if (!$this->remaining_balance_paid) {
                return (float) ($this->remaining_balance ?? $this->calculateRemainingBalance());
            }
            
            // Both paid - return 0
            return 0;
        }

        // Milestone payment: get next unpaid milestone amount
        if ($this->isMilestonePayment() && $this->project) {
            $nextUnpaidMilestone = $this->project->milestones()
                ->where('is_paid', false)
                ->orderBy('phase_order', 'asc')
                ->first();

            if ($nextUnpaidMilestone) {
                return (float) $nextUnpaidMilestone->amount;
            }

            // All milestones paid
            return 0;
        }

        // Fallback: return full budget
        return (float) $this->approved_budget;
    }

    /**
     * Get the description of what the current payment is for
     */
    public function getCurrentPaymentDescription(): string
    {
        if ($this->isFullPayment() || !$this->payment_type) {
            return 'Full Project Payment';
        }

        if ($this->isDownpayment()) {
            if (!$this->downpayment_paid) {
                return 'Downpayment (' . number_format($this->downpayment_percentage, 0) . '%)';
            }
            
            if (!$this->remaining_balance_paid) {
                return 'Remaining Balance';
            }
            
            return 'Payment Complete';
        }

        if ($this->isMilestonePayment() && $this->project) {
            $nextUnpaidMilestone = $this->project->milestones()
                ->where('is_paid', false)
                ->orderBy('phase_order', 'asc')
                ->first();

            if ($nextUnpaidMilestone) {
                return 'Phase ' . $nextUnpaidMilestone->phase_order . ': ' . $nextUnpaidMilestone->phase_name;
            }

            return 'All Milestones Paid';
        }

        return 'Payment';
    }

    /**
     * Get total amount paid so far
     */
    public function getTotalPaid(): float
    {
        // Sum all confirmed payments for this service request
        $totalPaid = $this->payments()
            ->where('status', 'confirmed')
            ->sum('amount');

        return (float) $totalPaid;
    }

    /**
     * Get remaining balance to be paid
     */
    public function getRemainingPaymentBalance(): float
    {
        if (!$this->approved_budget) {
            return 0;
        }

        $totalPaid = $this->getTotalPaid();
        $remainingBalance = $this->approved_budget - $totalPaid;

        return max(0, $remainingBalance);
    }

    /**
     * Check if there are any outstanding payments
     */
    public function hasOutstandingPayments(): bool
    {
        return $this->getRemainingPaymentBalance() > 0;
    }

    /**
     * Get payment progress percentage
     */
    public function getPaymentProgress(): float
    {
        if (!$this->approved_budget || $this->approved_budget == 0) {
            return 0;
        }

        $totalPaid = $this->getTotalPaid();
        $progress = ($totalPaid / $this->approved_budget) * 100;

        return min(100, max(0, $progress));
    }

    /**
     * Check if fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->getRemainingPaymentBalance() <= 0 && $this->approved_budget > 0;
    }

    /**
     * Check if coupon is applied
     */
    public function hasCoupon(): bool
    {
        return $this->applied_coupon_id !== null;
    }

    /**
     * Check if loyalty points were used
     */
    public function hasLoyaltyDiscount(): bool
    {
        return $this->loyalty_points_used > 0;
    }

    /**
     * Get total discount amount from all sources
     */
    public function getTotalDiscount(): float
    {
        return ($this->coupon_discount_amount ?? 0) + ($this->loyalty_discount_amount ?? 0);
    }

    /**
     * Get final amount after all discounts
     */
    public function getFinalAmount(): float
    {
        $original = $this->original_approved_budget ?? $this->approved_budget ?? 0;
        return max(0, $original - $this->getTotalDiscount());
    }

    /**
     * Check if loyalty points have been awarded
     */
    public function loyaltyPointsAwarded(): bool
    {
        return $this->loyalty_points_awarded === true;
    }
}
