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
}