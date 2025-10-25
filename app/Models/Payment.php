<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'milestone_id',
        'payment_type',
        'client_id',
        'amount',
        'payment_method',
        'payment_reference',
        'status',
        'notes',
        'confirmed_at',
        'confirmed_by',
        'payment_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'payment_details' => 'array',
    ];

    /**
     * Get the service request this payment belongs to
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the milestone this payment belongs to (for milestone payments)
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'milestone_id');
    }

    /**
     * Get the client who made this payment
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the admin who confirmed this payment
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * Check if payment is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get status badge color
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'failed' => 'error',
            'refunded' => 'info',
            'cancelled' => 'neutral',
            default => 'neutral'
        };
    }

    /**
     * Check if this is a full payment
     */
    public function isFullPayment(): bool
    {
        return $this->payment_type === 'full_payment';
    }

    /**
     * Check if this is a milestone payment
     */
    public function isMilestonePayment(): bool
    {
        return $this->payment_type === 'milestone_payment';
    }

    /**
     * Check if this is a downpayment
     */
    public function isDownpayment(): bool
    {
        return $this->payment_type === 'downpayment';
    }

    /**
     * Check if this is a remaining balance payment
     */
    public function isRemainingBalance(): bool
    {
        return $this->payment_type === 'remaining_balance';
    }

    /**
     * Get payment type label
     */
    public function getPaymentTypeLabel(): string
    {
        return match($this->payment_type) {
            'full_payment' => 'Full Payment',
            'milestone_payment' => 'Milestone Payment',
            'downpayment' => 'Downpayment',
            'remaining_balance' => 'Remaining Balance',
            default => 'Not Specified'
        };
    }

    /**
     * Scope: Get milestone payments
     */
    public function scopeMilestonePayments($query)
    {
        return $query->where('payment_type', 'milestone_payment');
    }

    /**
     * Scope: Get downpayments
     */
    public function scopeDownpayments($query)
    {
        return $query->where('payment_type', 'downpayment');
    }

    /**
     * Scope: Get full payments
     */
    public function scopeFullPayments($query)
    {
        return $query->where('payment_type', 'full_payment');
    }
}
