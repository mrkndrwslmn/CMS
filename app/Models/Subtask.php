<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Subtask Model
 * 
 * Represents a subtask of a parent task. Subtasks are smaller, 
 * trackable items that contribute to task progress.
 * 
 * @property int $id
 * @property int $task_id
 * @property string $title
 * @property string|null $description
 * @property bool $is_completed
 * @property \Carbon\Carbon|null $completed_at
 * @property int|null $completed_by
 * @property int|null $assigned_to
 * @property \Carbon\Carbon|null $due_date
 * @property int $sort_order
 * @property int $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Subtask extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subtasks';

    protected $fillable = [
        'task_id',
        'title',
        'description',
        'is_completed',
        'completed_at',
        'completed_by',
        'assigned_to',
        'due_date',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'due_date' => 'date',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent task.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the user who completed this subtask.
     */
    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by', 'id');
    }

    /**
     * Get the user this subtask is assigned to.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    /**
     * Get the user who created this subtask.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Scope for completed subtasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope for pending subtasks.
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Scope for overdue subtasks.
     */
    public function scopeOverdue($query)
    {
        return $query->pending()
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString());
    }

    /**
     * Scope for subtasks due today.
     */
    public function scopeDueToday($query)
    {
        return $query->pending()
            ->whereDate('due_date', now()->toDateString());
    }

    /**
     * Check if the subtask is overdue.
     */
    public function isOverdue(): bool
    {
        if ($this->is_completed || !$this->due_date) {
            return false;
        }

        return $this->due_date->isPast();
    }

    /**
     * Check if the subtask is due today.
     */
    public function isDueToday(): bool
    {
        if (!$this->due_date) {
            return false;
        }

        return $this->due_date->isToday();
    }

    /**
     * Mark the subtask as completed.
     */
    public function markComplete(User $user): bool
    {
        $wasCompleted = $this->is_completed;
        
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => $user->id,
        ]);

        // Update parent task progress if this is a new completion
        if (!$wasCompleted) {
            $this->task->updateProgressFromSubtasks();
        }

        return true;
    }

    /**
     * Mark the subtask as incomplete.
     */
    public function markIncomplete(): bool
    {
        $wasCompleted = $this->is_completed;
        
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
            'completed_by' => null,
        ]);

        // Update parent task progress if this was completed before
        if ($wasCompleted) {
            $this->task->updateProgressFromSubtasks();
        }

        return true;
    }

    /**
     * Toggle the completion status.
     */
    public function toggle(User $user): bool
    {
        if ($this->is_completed) {
            return $this->markIncomplete();
        } else {
            return $this->markComplete($user);
        }
    }

    /**
     * Boot method - add event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // When a subtask is created, update parent task progress
        static::created(function ($subtask) {
            $subtask->task->updateProgressFromSubtasks();
        });

        // When a subtask is deleted, update parent task progress
        static::deleted(function ($subtask) {
            $subtask->task->updateProgressFromSubtasks();
        });

        // When a subtask is restored, update parent task progress
        static::restored(function ($subtask) {
            $subtask->task->updateProgressFromSubtasks();
        });
    }
}
