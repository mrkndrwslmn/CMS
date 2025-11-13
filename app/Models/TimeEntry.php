<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class TimeEntry extends Model
{
    use Auditable;

    protected $fillable = [
        'adiutor_id',
        'task_id',
        'project_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'hourly_rate',
        'calculated_amount',
        'description',
        'is_approved',
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
        'is_approved' => 'boolean',
        'is_paid' => 'boolean',
        'duration_minutes' => 'integer',
        'hourly_rate' => 'decimal:2',
        'calculated_amount' => 'decimal:2',
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
}
