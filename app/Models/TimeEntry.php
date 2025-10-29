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
        'description',
        'is_approved',
        'approved_by',
        'approved_at',
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approved_at' => 'datetime',
        'is_approved' => 'boolean',
        'duration_minutes' => 'integer'
    ];

    /**
     * Get the adiutor who created this time entry
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Get the task this time entry is associated with
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the admin who approved this time entry
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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
