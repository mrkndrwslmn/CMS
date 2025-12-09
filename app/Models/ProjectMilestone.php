<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'phase_name',
        'phase_description',
        'phase_order',
        'percentage',
        'amount',
        'start_date',
        'due_date',
        'completed_date',
        'status',
        'is_paid',
        'paid_at',
        'adiutor_payout_id',
        'adiutor_paid',
        'adiutor_paid_at',
        'notes',
        'deliverables',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_date' => 'date',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
        'adiutor_paid' => 'boolean',
        'adiutor_paid_at' => 'datetime',
        'deliverables' => 'array',
    ];

    /**
     * Get the project that owns this milestone
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the payout this milestone was included in for adiutor payment
     */
    public function adiutorPayout(): BelongsTo
    {
        return $this->belongsTo(Payout::class, 'adiutor_payout_id');
    }

    /**
     * Get all tasks associated with this phase
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'phase_id');
    }

    /**
     * Get the milestone payment record for this phase
     */
    public function milestonePayment(): HasOne
    {
        return $this->hasOne(MilestonePayment::class, 'milestone_id');
    }

    /**
     * Get all payments made for this milestone
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'milestone_id');
    }

    /**
     * Scope: Get only paid milestones
     */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    /**
     * Scope: Get only unpaid milestones
     */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    /**
     * Scope: Get milestones in order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('phase_order', 'asc');
    }

    /**
     * Scope: Get milestones by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if this milestone/phase is paid
     */
    public function isPaid(): bool
    {
        return $this->is_paid;
    }

    /**
     * Check if this milestone is accessible (paid or full payment type)
     */
    public function isAccessible(): bool
    {
        // If the project payment type is full_payment, all milestones are accessible
        if ($this->project->serviceRequest->payment_type === 'full_payment') {
            return true;
        }

        // For milestone payment, check if this specific phase is paid
        return $this->is_paid;
    }

    /**
     * Mark this milestone as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'is_paid' => true,
            'paid_at' => now(),
            'status' => 'paid',
        ]);
    }

    /**
     * Calculate the amount based on project budget and percentage
     */
    public function calculateAmount(): float
    {
        $projectBudget = $this->project->budget ?? $this->project->serviceRequest->approved_budget ?? 0;
        return round(($projectBudget * $this->percentage) / 100, 2);
    }

    /**
     * Check if this is the current active phase
     */
    public function isCurrent(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if this phase is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->status === 'paid';
    }

    /**
     * Get the next milestone in sequence
     */
    public function nextMilestone(): ?ProjectMilestone
    {
        return static::where('project_id', $this->project_id)
            ->where('phase_order', '>', $this->phase_order)
            ->orderBy('phase_order', 'asc')
            ->first();
    }

    /**
     * Get the previous milestone in sequence
     */
    public function previousMilestone(): ?ProjectMilestone
    {
        return static::where('project_id', $this->project_id)
            ->where('phase_order', '<', $this->phase_order)
            ->orderBy('phase_order', 'desc')
            ->first();
    }

    /**
     * Check if all previous milestones are paid
     */
    public function arePreviousMilestonesPaid(): bool
    {
        $previousMilestonesCount = static::where('project_id', $this->project_id)
            ->where('phase_order', '<', $this->phase_order)
            ->count();

        $paidPreviousMilestonesCount = static::where('project_id', $this->project_id)
            ->where('phase_order', '<', $this->phase_order)
            ->where('is_paid', true)
            ->count();

        return $previousMilestonesCount === $paidPreviousMilestonesCount;
    }

    /**
     * Get completion percentage of tasks in this phase
     */
    public function getTaskCompletionPercentage(): float
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->tasks()->where('status', 'completed')->count();
        
        return round(($completedTasks / $totalTasks) * 100, 2);
    }
}
