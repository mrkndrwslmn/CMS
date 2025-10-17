<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetChangeRequest extends Model
{
    protected $fillable = [
        'task_id',
        'adiutor_id',
        'current_budget',
        'requested_budget',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'current_budget' => 'decimal:2',
        'requested_budget' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the task that this budget change request belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the adiutor who made the request
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Get the admin who reviewed the request
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope to get only pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get the budget difference
     */
    public function getBudgetDifferenceAttribute()
    {
        return $this->requested_budget - $this->current_budget;
    }

    /**
     * Get the percentage change
     */
    public function getPercentageChangeAttribute()
    {
        if ($this->current_budget == 0) {
            return 100;
        }
        return (($this->requested_budget - $this->current_budget) / $this->current_budget) * 100;
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
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
