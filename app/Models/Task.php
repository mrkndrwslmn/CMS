<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\Auditable;

class Task extends Model
{
    use HasFactory, Auditable;

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
        'sort_order',           // Display order for drag-and-drop reordering
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
        // Earnings and time tracking fields
        'hourly_rate',
        'requires_time_tracking',
        'total_hours_tracked',
        'calculated_earnings',
        'use_fixed_budget',
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
        'hourly_rate' => 'decimal:2',
        'total_hours_tracked' => 'decimal:2',
        'calculated_earnings' => 'decimal:2',
        'progress_percentage' => 'integer',
        'sort_order' => 'integer',
        'requires_time_tracking' => 'boolean',
        'use_fixed_budget' => 'boolean',
    ];

    /**
     * Scope to order tasks by their sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('taskID');
    }

    /**
     * Scope to get tasks for a specific project, ordered
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId)->ordered();
    }

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
aaaaaaaaaaaaaaaaaaa     * Get the form this task belongs to.
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
     * Get the subtasks for this task.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class, 'task_id', 'taskID')->orderBy('sort_order');
    }

    /**
     * Get the deliverables for this task (documents marked as deliverables).
     */
    public function deliverables(): HasMany
    {
        return $this->hasMany(Document::class, 'taskID')->where('is_deliverable', true);
    }

    /**
     * Get approved deliverables.
     */
    public function approvedDeliverables(): HasMany
    {
        return $this->deliverables()->where('is_approved', true);
    }

    /**
     * Get pending approval deliverables.
     */
    public function pendingDeliverables(): HasMany
    {
        return $this->deliverables()->where('is_approved', false);
    }

    /**
     * Check if the task has any deliverables.
     */
    public function hasDeliverables(): bool
    {
        return $this->deliverables()->exists();
    }

    /**
     * Check if the task has any approved deliverables.
     */
    public function hasApprovedDeliverables(): bool
    {
        return $this->approvedDeliverables()->exists();
    }

    /**
     * Get the count of deliverables by type.
     */
    public function getDeliverablesCounts(): array
    {
        $deliverables = $this->deliverables;
        return [
            'total' => $deliverables->count(),
            'files' => $deliverables->where('deliverable_type', 'file')->count(),
            'links' => $deliverables->where('deliverable_type', 'link')->count(),
            'approved' => $deliverables->where('is_approved', true)->count(),
            'pending' => $deliverables->where('is_approved', false)->count(),
        ];
    }

    /**
     * Check if the task has subtasks.
     */
    public function hasSubtasks(): bool
    {
        return $this->subtasks()->exists();
    }

    /**
     * Get subtasks statistics.
     */
    public function getSubtasksStats(): array
    {
        $subtasks = $this->subtasks()->withTrashed(false)->get();
        $total = $subtasks->count();
        $completed = $subtasks->where('is_completed', true)->count();
        
        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $total - $completed,
            'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0,
        ];
    }

    /**
     * Update task progress based on subtask completion.
     * Called automatically when subtasks are added, removed, or toggled.
     */
    public function updateProgressFromSubtasks(): void
    {
        if (!$this->hasSubtasks()) {
            return; // Don't override manual progress if no subtasks
        }

        $stats = $this->getSubtasksStats();
        $this->update([
            'progress_percentage' => $stats['percentage'],
        ]);

        // If all subtasks are completed, mark task as completed
        if ($stats['total'] > 0 && $stats['pending'] === 0 && $this->status !== 'completed') {
            $this->update([
                'status' => 'completed',
                'completedAt' => now(),
            ]);
        }
        // If task was completed but subtasks are now incomplete, revert to in_progress
        elseif ($stats['pending'] > 0 && $this->status === 'completed') {
            $this->update([
                'status' => 'in_progress',
                'completedAt' => null,
            ]);
        }
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
     * Get time entries for this task
     */
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class, 'task_id', 'taskID');
    }

    /**
     * Get the effective hourly rate for this task
     * Priority: task rate > project assignment rate > adiutor standard rate
     */
    public function getEffectiveHourlyRate(): ?float
    {
        if ($this->hourly_rate) {
            return (float) $this->hourly_rate;
        }

        if ($this->assignedUser) {
            $assignment = ProjectAssignment::where('project_id', $this->project_id)
                ->where('adiutor_id', $this->assignedTo)
                ->first();
            
            if ($assignment) {
                return $assignment->getEffectiveHourlyRate();
            }

            $adiutorProfile = $this->assignedUser->adiutorProfile;
            return $adiutorProfile?->standard_hourly_rate ? (float) $adiutorProfile->standard_hourly_rate : null;
        }

        return null;
    }

    /**
     * Calculate and update earnings from time entries
     */
    public function updateEarnings()
    {
        if ($this->use_fixed_budget) {
            // For fixed budget tasks, earnings = allocated budget
            $this->update([
                'calculated_earnings' => $this->allocated_budget ?? 0,
            ]);
            return;
        }

        if ($this->requires_time_tracking) {
            $timeEntries = $this->timeEntries()
                ->whereNotNull('end_time')
                ->get();

            $totalHours = $timeEntries->sum('duration_minutes') / 60;
            $totalEarnings = $timeEntries->sum('calculated_amount');

            $this->update([
                'total_hours_tracked' => $totalHours,
                'calculated_earnings' => $totalEarnings,
                'actual_cost' => $totalEarnings, // Update actual cost as well
            ]);
        }
    }

    /**
     * Get formatted hourly rate
     */
    public function getFormattedHourlyRate(): string
    {
        $rate = $this->getEffectiveHourlyRate();
        return $rate ? '₱' . number_format($rate, 2) . '/hr' : 'Not Set';
    }

    /**
     * Get formatted earnings
     */
    public function getFormattedEarnings(): string
    {
        return '₱' . number_format($this->calculated_earnings ?? 0, 2);
    }

    /**
     * Get payable amount (either fixed budget or calculated earnings)
     */
    public function getPayableAmount(): float
    {
        if ($this->use_fixed_budget) {
            return (float) ($this->allocated_budget ?? 0);
        }

        return (float) ($this->calculated_earnings ?? 0);
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