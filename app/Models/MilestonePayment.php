<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilestonePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_id',
        'payment_id',
        'service_request_id',
        'client_id',
        'amount_due',
        'amount_paid',
        'status',
        'due_date',
        'paid_at',
        'notes',
        'confirmed_by',
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the milestone this payment belongs to
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'milestone_id');
    }

    /**
     * Get the payment record (if payment has been made)
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the service request this payment is for
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the client making this payment
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
     * Scope: Get only paid milestone payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: Get only pending milestone payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get only overdue milestone payments
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    /**
     * Scope: Get milestone payments for a specific client
     */
    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Check if this milestone payment is fully paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid' && $this->amount_paid >= $this->amount_due;
    }

    /**
     * Check if this milestone payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if this milestone payment is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->status === 'overdue') {
            return true;
        }

        return $this->status === 'pending' && 
               $this->due_date && 
               $this->due_date < now();
    }

    /**
     * Get the remaining balance to be paid
     */
    public function getRemainingBalance(): float
    {
        return max(0, $this->amount_due - $this->amount_paid);
    }

    /**
     * Mark this milestone payment as paid
     */
    public function markAsPaid(int $paymentId, ?int $confirmedBy = null): void
    {
        $this->update([
            'payment_id' => $paymentId,
            'amount_paid' => $this->amount_due,
            'status' => 'paid',
            'paid_at' => now(),
            'confirmed_by' => $confirmedBy,
        ]);

        // Also mark the milestone as paid
        $this->milestone->markAsPaid();
    }

    /**
     * Record a partial payment
     */
    public function recordPartialPayment(float $amount): void
    {
        $newAmountPaid = $this->amount_paid + $amount;
        
        $this->update([
            'amount_paid' => $newAmountPaid,
            'status' => $newAmountPaid >= $this->amount_due ? 'paid' : 'partial',
        ]);

        // If now fully paid, mark milestone as paid
        if ($newAmountPaid >= $this->amount_due) {
            $this->milestone->markAsPaid();
        }
    }

    /**
     * Update the status to overdue if past due date
     */
    public function checkAndUpdateOverdueStatus(): void
    {
        if ($this->isOverdue() && $this->status !== 'overdue') {
            $this->update(['status' => 'overdue']);
        }
    }
}
