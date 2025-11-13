<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_id',
        'time_entry_id',
        'task_id',
        'project_id',
        'item_type',
        'description',
        'amount',
        'hours',
        'rate',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'hours' => 'decimal:2',
        'rate' => 'decimal:2',
    ];

    /**
     * Get the payout this item belongs to
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }

    /**
     * Get the time entry for this item
     */
    public function timeEntry(): BelongsTo
    {
        return $this->belongsTo(TimeEntry::class);
    }

    /**
     * Get the task for this item
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the project for this item
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        return '₱' . number_format($this->amount, 2);
    }
}
