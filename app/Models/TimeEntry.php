<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class TimeEntry extends Model
{
    use Auditable, HasFactory;

    protected $fillable = [
        'adiutor_id',
        'task_id',
        'project_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'original_duration_minutes',
        'billable_minutes',
        'non_billable_minutes',
        'is_capped',
        'hourly_rate',
        'calculated_amount',
        'original_calculated_amount',
        'description',
        'is_approved',
        'admin_adjusted',
        'adjustment_reason',
        'adjusted_by',
        'adjusted_at',
        'is_paid',
        'payout_id',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approved_at' => 'datetime',
        'adjusted_at' => 'datetime',
        'is_approved' => 'boolean',
        'is_paid' => 'boolean',
        'is_capped' => 'boolean',
        'admin_adjusted' => 'boolean',
        'duration_minutes' => 'integer',
        'original_duration_minutes' => 'integer',
        'billable_minutes' => 'integer',
        'non_billable_minutes' => 'integer',
        'hourly_rate' => 'decimal:2',
        'calculated_amount' => 'decimal:2',
        'original_calculated_amount' => 'decimal:2',
    ];

    /**
     * Get the adiutor who created this time entry
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Get the payout this entry belongs to
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }

    /**
     * Get the user who approved this entry
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the admin who adjusted this entry
     */
    public function adjuster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    /**
     * Check if this entry was adjusted by admin
     */
    public function wasAdjusted(): bool
    {
        return $this->admin_adjusted ?? false;
    }

    /**
     * Get the adjustment difference in minutes
     */
    public function getAdjustmentDifferenceMinutes(): int
    {
        if (!$this->wasAdjusted() || !$this->original_duration_minutes) {
            return 0;
        }
        return $this->duration_minutes - $this->original_duration_minutes;
    }

    /**
     * Get the adjustment difference in amount
     */
    public function getAdjustmentDifferenceAmount(): float
    {
        if (!$this->wasAdjusted() || !$this->original_calculated_amount) {
            return 0;
        }
        return (float) $this->calculated_amount - (float) $this->original_calculated_amount;
    }

    /**
     * Get original duration formatted
     */
    public function getOriginalFormattedDuration(): ?string
    {
        if (!$this->original_duration_minutes) {
            return null;
        }
        $hours = floor($this->original_duration_minutes / 60);
        $minutes = $this->original_duration_minutes % 60;
        return sprintf('%dh %dm', $hours, $minutes);
    }

    /**
     * Get original amount formatted
     */
    public function getOriginalFormattedAmount(): ?string
    {
        if (!$this->original_calculated_amount) {
            return null;
        }
        return '₱' . number_format($this->original_calculated_amount, 2);
    }

    /**
     * Apply admin adjustment to this entry
     */
    public function applyAdjustment(float $adjustedHours, string $reason, int $adminId): void
    {
        // Store original values if not already stored
        if (!$this->original_duration_minutes) {
            $this->original_duration_minutes = $this->duration_minutes;
        }
        if (!$this->original_calculated_amount) {
            $this->original_calculated_amount = $this->calculated_amount;
        }

        // Apply adjustment
        $adjustedMinutes = (int) ($adjustedHours * 60);
        $adjustedAmount = round($adjustedHours * (float) $this->hourly_rate, 2);

        $this->update([
            'duration_minutes' => $adjustedMinutes,
            'billable_minutes' => $adjustedMinutes,
            'calculated_amount' => $adjustedAmount,
            'admin_adjusted' => true,
            'adjustment_reason' => $reason,
            'adjusted_by' => $adminId,
            'adjusted_at' => now(),
        ]);
    }

    /**
     * Calculate and set the amount based on duration and rate
     */
    public function calculateAmount()
    {
        if ($this->duration_minutes && $this->hourly_rate) {
            $hours = $this->duration_minutes / 60;
            $this->calculated_amount = $hours * $this->hourly_rate;
            $this->save();
        }
    }

    /**
     * Set hourly rate from task/assignment
     */
    public function setHourlyRateFromTask()
    {
        if ($this->task) {
            $rate = $this->task->getEffectiveHourlyRate();
            if ($rate) {
                $this->hourly_rate = $rate;
                $this->save();
            }
        }
    }

    /**
     * Calculate duration when entry is stopped
     */
    public function calculateDuration()
    {
        if ($this->start_time && $this->end_time) {
            $this->duration_minutes = $this->start_time->diffInMinutes($this->end_time);
            $this->save();
        }
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDuration(): string
    {
        if (!$this->duration_minutes) {
            return 'Running...';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        return sprintf('%dh %dm', $hours, $minutes);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return '₱' . number_format($this->calculated_amount ?? 0, 2);
    }

    /**
     * Check if entry is billable (approved and not paid)
     */
    public function isBillable(): bool
    {
        return $this->is_approved && !$this->is_paid;
    }

    /**
     * Approve time entry
     */
    public function approve($approverId = null)
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $approverId ?? auth()->id(),
            'approved_at' => now(),
        ]);

        // Update task earnings
        $this->task?->updateEarnings();
    }

    /**
     * The task this time entry is associated with
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the project this time entry belongs to directly
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the project through the task relationship (alternative method)
     */
    public function projectViaTask()
    {
        return $this->hasOneThrough(Project::class, Task::class, 'taskID', 'id', 'task_id', 'project_id');
    }

    /**
     * Scope for approved entries
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending entries
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope for active entries (running timers)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('end_time');
    }

    /**
     * Scope for completed entries
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('end_time');
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_minutes) {
            return '0m';
        }

        $hours = intval($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        return sprintf('%dm', $minutes);
    }

    /**
     * Get duration in hours
     */
    public function getDurationHoursAttribute()
    {
        return $this->duration_minutes ? round($this->duration_minutes / 60, 2) : 0;
    }

    // ==========================================
    // BILLABLE/NON-BILLABLE METHODS
    // ==========================================

    /**
     * Get billable minutes (defaults to duration_minutes if not set)
     */
    public function getBillableMinutes(): int
    {
        return $this->billable_minutes ?? $this->duration_minutes ?? 0;
    }

    /**
     * Get non-billable minutes
     */
    public function getNonBillableMinutes(): int
    {
        return $this->non_billable_minutes ?? 0;
    }

    /**
     * Get billable hours
     */
    public function getBillableHours(): float
    {
        return round($this->getBillableMinutes() / 60, 2);
    }

    /**
     * Get non-billable hours
     */
    public function getNonBillableHours(): float
    {
        return round($this->getNonBillableMinutes() / 60, 2);
    }

    /**
     * Check if this entry was capped due to max hours limit
     */
    public function wasCapped(): bool
    {
        return $this->is_capped ?? false;
    }

    /**
     * Get formatted billable duration
     */
    public function getFormattedBillableDuration(): string
    {
        $minutes = $this->getBillableMinutes();
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        return sprintf('%dh %dm', $hours, $mins);
    }

    /**
     * Calculate amount based on billable minutes (not total duration)
     * This respects max hours cap
     */
    public function calculateBillableAmount(): float
    {
        $billableHours = $this->getBillableHours();
        return round($billableHours * (float) ($this->hourly_rate ?? 0), 2);
    }

    /**
     * Get the project assignment for this time entry
     */
    public function getProjectAssignment(): ?ProjectAssignment
    {
        return ProjectAssignment::where('project_id', $this->project_id)
            ->where('adiutor_id', $this->adiutor_id)
            ->first();
    }

    /**
     * Apply max hours cap to this entry
     * Should be called when stopping a timer
     * 
     * Priority: Task max_hours takes precedence over project assignment max_hours
     */
    public function applyMaxHoursCap(): void
    {
        if (!$this->duration_minutes) {
            $this->billable_minutes = 0;
            $this->non_billable_minutes = 0;
            $this->is_capped = false;
            return;
        }

        // First check task-level max hours (takes priority)
        $task = $this->task;
        if ($task && $task->hasMaxHoursLimit()) {
            $result = $task->calculateBillableMinutes($this->duration_minutes);
            
            $this->billable_minutes = $result['billable_minutes'];
            $this->non_billable_minutes = $result['non_billable_minutes'];
            $this->is_capped = $result['is_capped'];
            
            // Recalculate amount based on billable minutes only
            $this->calculated_amount = $this->calculateBillableAmount();
            
            // Save and update task totals
            $this->save();
            $task->updateBillableHourTotals();
            return;
        }

        // Fall back to project assignment max hours
        $assignment = $this->getProjectAssignment();
        
        if (!$assignment) {
            // No assignment and no task limit, all time is billable
            $this->billable_minutes = $this->duration_minutes;
            $this->non_billable_minutes = 0;
            $this->is_capped = false;
            return;
        }

        // Calculate billable/non-billable split from assignment
        $result = $assignment->calculateBillableMinutes($this->duration_minutes);
        
        $this->billable_minutes = $result['billable_minutes'];
        $this->non_billable_minutes = $result['non_billable_minutes'];
        $this->is_capped = $result['is_capped'];
        
        // Recalculate amount based on billable minutes only
        $this->calculated_amount = $this->calculateBillableAmount();
    }

    /**
     * Check if this entry was capped due to task max hours
     */
    public function wasCappedByTask(): bool
    {
        if (!$this->is_capped) {
            return false;
        }
        
        $task = $this->task;
        return $task && $task->hasMaxHoursLimit();
    }

    /**
     * Check if this entry was capped due to assignment max hours
     */
    public function wasCappedByAssignment(): bool
    {
        if (!$this->is_capped) {
            return false;
        }
        
        $assignment = $this->getProjectAssignment();
        return $assignment && $assignment->hasMaxHoursLimit() && !$this->wasCappedByTask();
    }

    /**
     * Get capping source for display
     */
    public function getCappingSource(): ?string
    {
        if (!$this->is_capped) {
            return null;
        }
        
        if ($this->wasCappedByTask()) {
            return 'task';
        }
        
        if ($this->wasCappedByAssignment()) {
            return 'assignment';
        }
        
        return 'unknown';
    }

    /**
     * Scope for capped entries
     */
    public function scopeCapped($query)
    {
        return $query->where('is_capped', true);
    }
}
