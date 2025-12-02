<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HourIncreaseRequest extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'hour_increase_requests';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id',
        'project_assignment_id',
        'adiutor_id',
        'project_id',
        'current_max_hours',
        'requested_max_hours',
        'hours_already_tracked',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'approved_hours',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'current_max_hours' => 'decimal:2',
        'requested_max_hours' => 'decimal:2',
        'hours_already_tracked' => 'decimal:2',
        'approved_hours' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the task associated with this request.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the project assignment associated with this request.
     */
    public function projectAssignment(): BelongsTo
    {
        return $this->belongsTo(ProjectAssignment::class, 'project_assignment_id');
    }

    /**
     * Get the adiutor who made the request.
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Get the project associated with this request.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the admin who reviewed the request.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Check if request is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if request was approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if request was rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get the hours increase requested.
     */
    public function getHoursIncreaseAttribute(): float
    {
        return $this->requested_max_hours - $this->current_max_hours;
    }

    /**
     * Get the remaining hours at time of request.
     */
    public function getRemainingHoursAttribute(): float
    {
        return max(0, $this->current_max_hours - $this->hours_already_tracked);
    }

    /**
     * Get the utilization percentage at time of request.
     */
    public function getUtilizationPercentageAttribute(): float
    {
        if ($this->current_max_hours <= 0) {
            return 0;
        }
        return round(($this->hours_already_tracked / $this->current_max_hours) * 100, 1);
    }

    /**
     * Get status label for display.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status badge CSS class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the target name (task or assignment).
     */
    public function getTargetNameAttribute(): string
    {
        if ($this->task_id && $this->task) {
            return $this->task->taskTitle;
        }
        if ($this->project_assignment_id && $this->projectAssignment) {
            return 'Project Assignment';
        }
        return 'Unknown';
    }

    /**
     * Approve the request.
     */
    public function approve(int $reviewerId, ?float $approvedHours = null, ?string $notes = null): bool
    {
        $hours = $approvedHours ?? $this->requested_max_hours;
        
        $this->update([
            'status' => self::STATUS_APPROVED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes,
            'approved_hours' => $hours,
        ]);

        // Update the actual max_hours on the assignment
        if ($this->project_assignment_id && $this->projectAssignment) {
            $this->projectAssignment->update(['max_hours' => $hours]);
        }

        return true;
    }

    /**
     * Reject the request.
     */
    public function reject(int $reviewerId, ?string $notes = null): bool
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'review_notes' => $notes,
        ]);

        return true;
    }

    /**
     * Scope to filter pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to filter by adiutor.
     */
    public function scopeForAdiutor($query, int $adiutorId)
    {
        return $query->where('adiutor_id', $adiutorId);
    }

    /**
     * Scope to filter by project.
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Create a new request for a project assignment.
     */
    public static function createForAssignment(
        ProjectAssignment $assignment,
        float $requestedHours,
        string $reason
    ): self {
        return self::create([
            'project_assignment_id' => $assignment->id,
            'adiutor_id' => $assignment->adiutor_id,
            'project_id' => $assignment->project_id,
            'current_max_hours' => $assignment->max_hours ?? 0,
            'requested_max_hours' => $requestedHours,
            'hours_already_tracked' => $assignment->total_hours_logged ?? 0,
            'reason' => $reason,
            'status' => self::STATUS_PENDING,
        ]);
    }
}
