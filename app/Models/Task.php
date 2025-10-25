<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';
    protected $primaryKey = 'taskID';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',           // CORRECT: Tasks belong to PROJECTS, not requests
        'phase_id',             // Milestone phase this task belongs to
        'assignedTo',
        'taskTitle',
        'taskDescription',
        'status',
        'priority',
        'deadline',
        'completedAt',
        'notes',
        'createdBy',
        'client_id',
        'dateAssigned',
        'allocated_budget',
        'actual_cost',
        'progress_percentage',
        'completion_notes',
        // Legacy fields (keep for backward compatibility but should not be used)
        'formID',
        'service_request_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'deadline' => 'datetime',
        'completedAt' => 'datetime',
        'dateAssigned' => 'datetime',
        'allocated_budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'progress_percentage' => 'integer',
    ];

    /**
     * Get the PROJECT this task belongs to (CORRECT RELATIONSHIP).
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the milestone phase this task belongs to
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'phase_id');
    }

    /**
     * Get the user assigned to this task.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignedTo', 'id');
    }

    /**
     * Get the user who created this task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createdBy', 'id');
    }
    
    /**
     * Get the client this task belongs to (through project).
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    // LEGACY RELATIONSHIPS (keep for backward compatibility but deprecated)
    
    /**
     * @deprecated Use project() relationship instead
     * Get the form this task belongs to.
     * COMMENTED OUT: forms table no longer exists in new architecture
     */
    // public function form(): BelongsTo
    // {
    //     return $this->belongsTo(Form::class, 'formID', 'formID');
    // }

    /**
     * @deprecated Tasks should belong to projects, not service requests directly
     * Get the service request this task belongs to.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    /**
     * Get the documents associated with this task (legacy foreign key).
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'taskID');
    }

    /**
     * Get all documents for this task (polymorphic).
     */
    public function allDocuments(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Check if task is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if task is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if task is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline->isPast() && !$this->isCompleted();
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get priority badge class for UI.
     */
    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'high' => 'bg-red-100 text-red-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get formatted deadline.
     */
    public function getFormattedDeadlineAttribute(): string
    {
        return $this->deadline ? $this->deadline->format('M d, Y H:i') : 'No deadline';
    }

    /**
     * Check if task is within budget.
     */
    public function isWithinBudget(): bool
    {
        if (!$this->allocated_budget || !$this->actual_cost) {
            return true;
        }
        return $this->actual_cost <= $this->allocated_budget;
    }

    /**
     * Get budget variance.
     */
    public function getBudgetVariance(): float
    {
        if (!$this->allocated_budget || !$this->actual_cost) {
            return 0;
        }
        return $this->actual_cost - $this->allocated_budget;
    }

    /**
     * Get budget utilization percentage.
     */
    public function getBudgetUtilization(): float
    {
        if (!$this->allocated_budget) {
            return 0;
        }
        if (!$this->actual_cost) {
            return 0;
        }
        return ($this->actual_cost / $this->allocated_budget) * 100;
    }

    /**
     * Check if this task is accessible to the client based on payment status
     * 
     * @return bool
     */
    public function isAccessibleToClient(): bool
    {
        // Get the project's payment type through service request
        $serviceRequest = $this->project->serviceRequest;
        
        if (!$serviceRequest || !$serviceRequest->payment_type) {
            // Default: accessible if no payment type set
            return true;
        }

        // Full payment: All tasks are accessible if paid
        if ($serviceRequest->isFullPayment()) {
            return $serviceRequest->isPaid();
        }

        // Milestone payment: Task accessible if its phase is paid
        if ($serviceRequest->isMilestonePayment()) {
            // If task has no phase, it's accessible (edge case)
            if (!$this->phase_id) {
                return true;
            }

            // Check if the phase this task belongs to is paid
            return $this->phase && $this->phase->isPaid();
        }

        // Downpayment: Tasks accessible only after remaining balance is paid
        if ($serviceRequest->isDownpayment()) {
            return $serviceRequest->isRemainingBalancePaid();
        }

        return false;
    }

    /**
     * Check if task's associated documents are accessible to client
     */
    public function areDocumentsAccessible(): bool
    {
        return $this->isAccessibleToClient();
    }

    /**
     * Get the lock status for UI display
     */
    public function getLockStatus(): array
    {
        $isAccessible = $this->isAccessibleToClient();
        $serviceRequest = $this->project->serviceRequest;

        if ($isAccessible) {
            return [
                'locked' => false,
                'message' => 'Accessible',
                'icon' => 'unlock',
            ];
        }

        // Determine lock reason based on payment type
        if ($serviceRequest->isMilestonePayment() && $this->phase_id) {
            return [
                'locked' => true,
                'message' => "Locked: Payment required for {$this->phase->phase_name}",
                'icon' => 'lock',
                'phase' => $this->phase->phase_name,
            ];
        }

        if ($serviceRequest->isDownpayment()) {
            return [
                'locked' => true,
                'message' => 'Locked: Remaining balance payment required',
                'icon' => 'lock',
            ];
        }

        return [
            'locked' => true,
            'message' => 'Locked: Payment required',
            'icon' => 'lock',
        ];
    }
}