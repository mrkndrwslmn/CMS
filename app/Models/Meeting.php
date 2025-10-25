<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'client_id',
        'admin_id',
        'title',
        'description',
        'requested_date',
        'requested_time',
        'rescheduled_date',
        'rescheduled_time',
        'scheduled_date',
        'scheduled_time',
        'status',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'zoom_password',
        'admin_notes',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'rescheduled_date' => 'date',
        'scheduled_date' => 'date',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_RESCHEDULED = 'rescheduled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the project that owns the meeting
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the client that requested the meeting
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the admin that manages the meeting
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Check if meeting is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if meeting is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if meeting is rescheduled
     */
    public function isRescheduled(): bool
    {
        return $this->status === self::STATUS_RESCHEDULED;
    }

    /**
     * Check if meeting is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if meeting is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if meeting is upcoming (approved and scheduled in future)
     */
    public function isUpcoming(): bool
    {
        if (!$this->isApproved() || !$this->scheduled_date || !$this->scheduled_time) {
            return false;
        }

        $scheduledDateTime = Carbon::parse($this->scheduled_date->format('Y-m-d') . ' ' . $this->scheduled_time);
        return $scheduledDateTime->isFuture();
    }

    /**
     * Check if meeting should be pinned (approved and upcoming)
     */
    public function shouldBePinned(): bool
    {
        return $this->isApproved() && $this->isUpcoming();
    }

    /**
     * Get the scheduled date and time as Carbon instance
     */
    public function getScheduledDateTime(): ?Carbon
    {
        if (!$this->scheduled_date || !$this->scheduled_time) {
            return null;
        }

        return Carbon::parse($this->scheduled_date->format('Y-m-d') . ' ' . $this->scheduled_time);
    }

    /**
     * Get formatted scheduled date and time
     */
    public function getFormattedScheduledDateTime(): string
    {
        $dateTime = $this->getScheduledDateTime();
        
        if (!$dateTime) {
            return 'Not scheduled';
        }

        return $dateTime->format('F j, Y \a\t g:i A');
    }

    /**
     * Get time remaining until meeting
     */
    public function getTimeRemaining(): ?string
    {
        $dateTime = $this->getScheduledDateTime();
        
        if (!$dateTime || !$dateTime->isFuture()) {
            return null;
        }

        return $dateTime->diffForHumans();
    }

    /**
     * Mark meeting as completed
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
    }

    /**
     * Scope for upcoming meetings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', self::STATUS_APPROVED)
            ->where('scheduled_date', '>=', now()->toDateString())
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time');
    }

    /**
     * Scope for pending meetings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->orderBy('requested_date')
            ->orderBy('requested_time');
    }

    /**
     * Scope for meetings by project
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }
}
