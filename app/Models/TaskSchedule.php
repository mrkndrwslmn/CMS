<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'adiutor_id',
        'scheduled_start',
        'scheduled_end',
        'estimated_duration_minutes',
        'schedule_type',
        'status',
        'google_calendar_event_id',
        'calendar_synced_at',
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'calendar_synced_at' => 'datetime',
        'estimated_duration_minutes' => 'integer',
    ];

    /**
     * Get the task that owns the schedule
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the adiutor assigned to this schedule
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Check if this schedule is synced to Google Calendar
     */
    public function isSynced(): bool
    {
        return !is_null($this->google_calendar_event_id) && !is_null($this->calendar_synced_at);
    }

    /**
     * Get the duration in hours
     */
    public function getDurationInHours(): float
    {
        return round($this->estimated_duration_minutes / 60, 1);
    }
}
