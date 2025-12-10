<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'platform_fee',
        'margin_earnings',
        'total_platform_revenue',
        'client_payment',
        'project_budget',
        'working_budget',
        'total_adiutor_cost',
        'hourly_cost',
        'fixed_rate_cost',
        'fee_percentage',
        'total_task_allocated',
        'unallocated_budget',
        'status',
        'finalized_at',
        'finalized_by',
        'notes',
        'adjustment_amount',
        'adjustment_reason',
        'adjusted_by',
        'adjusted_at',
    ];

    protected function casts(): array
    {
        return [
            'platform_fee' => 'decimal:2',
            'margin_earnings' => 'decimal:2',
            'total_platform_revenue' => 'decimal:2',
            'client_payment' => 'decimal:2',
            'project_budget' => 'decimal:2',
            'working_budget' => 'decimal:2',
            'total_adiutor_cost' => 'decimal:2',
            'hourly_cost' => 'decimal:2',
            'fixed_rate_cost' => 'decimal:2',
            'fee_percentage' => 'decimal:2',
            'total_task_allocated' => 'decimal:2',
            'unallocated_budget' => 'decimal:2',
            'adjustment_amount' => 'decimal:2',
            'finalized_at' => 'datetime',
            'adjusted_at' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_FINALIZED = 'finalized';
    const STATUS_ADJUSTED = 'adjusted';

    /**
     * Get the project this earning belongs to
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who finalized this earning
     */
    public function finalizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    /**
     * Get the user who made adjustments
     */
    public function adjuster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    /**
     * Scope for pending earnings
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for in progress earnings
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope for finalized earnings
     */
    public function scopeFinalized($query)
    {
        return $query->where('status', self::STATUS_FINALIZED);
    }

    /**
     * Check if earnings are finalized
     */
    public function isFinalized(): bool
    {
        return $this->status === self::STATUS_FINALIZED;
    }

    /**
     * Get the profit margin percentage
     */
    public function getProfitMarginPercentage(): float
    {
        if ($this->project_budget <= 0) {
            return 0;
        }
        return round(($this->total_platform_revenue / $this->project_budget) * 100, 2);
    }

    /**
     * Get the adiutor cost percentage
     */
    public function getAdiutorCostPercentage(): float
    {
        if ($this->project_budget <= 0) {
            return 0;
        }
        return round(($this->total_adiutor_cost / $this->project_budget) * 100, 2);
    }

    /**
     * Get final revenue after adjustments
     */
    public function getFinalRevenue(): float
    {
        return (float) $this->total_platform_revenue + (float) $this->adjustment_amount;
    }

    /**
     * Format currency for display
     */
    public function formatCurrency(float $amount): string
    {
        return '₱' . number_format($amount, 2);
    }

    /**
     * Get status badge variant
     */
    public function getStatusBadgeVariant(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_FINALIZED => 'success',
            self::STATUS_ADJUSTED => 'info',
            default => 'neutral',
        };
    }

    /**
     * Get human-readable status
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_FINALIZED => 'Finalized',
            self::STATUS_ADJUSTED => 'Adjusted',
            default => ucfirst($this->status),
        };
    }

    /**
     * Accessor for total_earnings (alias for total_platform_revenue)
     */
    public function getTotalEarningsAttribute(): float
    {
        return (float) $this->total_platform_revenue;
    }

    /**
     * Accessor for profit margin percentage
     */
    public function getProfitMarginPercentageAttribute(): ?float
    {
        if ($this->project_budget <= 0) {
            return null;
        }
        return round(($this->total_platform_revenue / $this->project_budget) * 100, 2);
    }
}
