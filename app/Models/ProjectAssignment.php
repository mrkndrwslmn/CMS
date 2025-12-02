<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'project_assignments';

    /**
     * Payment type constants
     */
    const PAYMENT_TYPE_FIXED = 'fixed_rate';
    const PAYMENT_TYPE_HOURLY = 'hourly_rate';

    protected $fillable = [
        'project_id',
        'adiutor_id',
        'agreed_rate',
        'hourly_rate',
        'max_hours',
        'total_hours_logged',
        'total_billable_hours',
        'requires_time_tracking',
        'total_earnings',
        'total_hours_worked',
        'start_date',
        'expected_completion',
        'status',
        'notes',
        'progress_percentage',
        'payment_type',
        'fixed_rate_approved',
        'fixed_rate_approved_at',
        'fixed_rate_approved_by',
        'fixed_rate_paid',
        'fixed_rate_payout_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_completion' => 'date',
        'agreed_rate' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'max_hours' => 'decimal:2',
        'total_hours_logged' => 'decimal:2',
        'total_billable_hours' => 'decimal:2',
        'requires_time_tracking' => 'boolean',
        'total_earnings' => 'decimal:2',
        'total_hours_worked' => 'decimal:2',
        'progress_percentage' => 'integer',
        'fixed_rate_approved' => 'boolean',
        'fixed_rate_approved_at' => 'datetime',
        'fixed_rate_paid' => 'boolean',
    ];

    /**
     * Boot method to automatically add adiutor to group chat when assigned
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($assignment) {
            // Get or create group chat for the project
            $groupChat = GroupChat::getOrCreateForProject($assignment->project_id);
            
            // Add the adiutor to the group chat
            $groupChat->addMember($assignment->adiutor_id);
        });

        static::deleted(function ($assignment) {
            // Optionally remove adiutor from group chat when assignment is deleted
            // For now, we'll keep them in the chat for historical context
            // If you want to remove them, uncomment below:
            // $groupChat = GroupChat::where('project_id', $assignment->project_id)->first();
            // if ($groupChat) {
            //     $groupChat->members()->detach($assignment->adiutor_id);
            // }
        });
    }

    /**
     * Get the project that this assignment belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the adiutor assigned to this project
     */
    public function adiutor()
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Get the admin who approved the fixed rate
     */
    public function fixedRateApprover()
    {
        return $this->belongsTo(User::class, 'fixed_rate_approved_by');
    }

    /**
     * Get the payout that included the fixed rate payment
     */
    public function fixedRatePayout()
    {
        return $this->belongsTo(Payout::class, 'fixed_rate_payout_id');
    }

    /**
     * Get time entries for this assignment
     */
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class, 'project_id', 'project_id')
            ->where('adiutor_id', $this->adiutor_id);
    }

    /**
     * Check if this assignment uses fixed rate payment
     */
    public function isFixedRate(): bool
    {
        return $this->payment_type === self::PAYMENT_TYPE_FIXED;
    }

    /**
     * Check if this assignment uses hourly rate payment
     */
    public function isHourlyRate(): bool
    {
        return $this->payment_type === self::PAYMENT_TYPE_HOURLY;
    }

    /**
     * Check if fixed rate can be approved (for fixed_rate assignments)
     */
    public function canApproveFixedRate(): bool
    {
        return $this->isFixedRate() 
            && !$this->fixed_rate_approved 
            && $this->status === 'completed'
            && $this->agreed_rate > 0;
    }

    /**
     * Check if fixed rate has been approved but not yet paid
     */
    public function isFixedRatePendingPayout(): bool
    {
        return $this->isFixedRate() 
            && $this->fixed_rate_approved 
            && !$this->fixed_rate_paid;
    }

    /**
     * Get the effective rate based on payment type
     * Returns agreed_rate for fixed, hourly_rate for hourly
     */
    public function getEffectiveRate(): ?float
    {
        if ($this->isFixedRate()) {
            return $this->agreed_rate ? (float) $this->agreed_rate : null;
        }
        
        return $this->getEffectiveHourlyRate();
    }

    /**
     * Get the total earnings for this assignment based on payment type
     */
    public function getTotalEarnings(): float
    {
        if ($this->isFixedRate()) {
            // For fixed rate, return agreed_rate if approved
            return $this->fixed_rate_approved ? (float) ($this->agreed_rate ?? 0) : 0;
        }
        
        // For hourly rate, sum approved time entries
        return (float) $this->timeEntries()
            ->where('is_approved', true)
            ->sum('calculated_amount');
    }

    /**
     * Get pending earnings (not yet approved)
     */
    public function getPendingEarnings(): float
    {
        if ($this->isFixedRate()) {
            // For fixed rate, return agreed_rate if not yet approved
            return !$this->fixed_rate_approved ? (float) ($this->agreed_rate ?? 0) : 0;
        }
        
        // For hourly rate, sum pending time entries
        return (float) $this->timeEntries()
            ->where('is_approved', false)
            ->whereNotNull('end_time')
            ->sum('calculated_amount');
    }

    /**
     * Get formatted payment type label
     */
    public function getPaymentTypeLabel(): string
    {
        return $this->isFixedRate() ? 'Fixed Rate' : 'Hourly Rate';
    }

    /**
     * Get payment type badge HTML
     */
    public function getPaymentTypeBadge(): string
    {
        if ($this->isFixedRate()) {
            return '<span class="badge bg-info">💰 Fixed Rate</span>';
        }
        return '<span class="badge bg-primary">⏱️ Hourly Rate</span>';
    }

    /**
     * Get the effective hourly rate for this assignment
     * Priority: assignment rate > adiutor standard rate
     */
    public function getEffectiveHourlyRate(): ?float
    {
        if ($this->hourly_rate) {
            return (float) $this->hourly_rate;
        }

        $adiutorProfile = $this->adiutor->adiutorProfile;
        return $adiutorProfile?->standard_hourly_rate ? (float) $adiutorProfile->standard_hourly_rate : null;
    }

    /**
     * Calculate and update earnings
     */
    public function updateEarnings()
    {
        $timeEntries = $this->timeEntries()
            ->whereNotNull('end_time')
            ->get();

        $totalHours = $timeEntries->sum('duration_minutes') / 60;
        $totalEarnings = $timeEntries->sum('calculated_amount');

        $this->update([
            'total_hours_worked' => $totalHours,
            'total_earnings' => $totalEarnings,
        ]);
    }

    /**
     * Get formatted hourly rate
     */
    public function getFormattedHourlyRate(): string
    {
        $rate = $this->getEffectiveHourlyRate();
        return $rate ? '₱' . number_format($rate, 2) . '/hr' : 'Not Set';
    }

    /**
     * Get formatted earnings
     */
    public function getFormattedEarnings(): string
    {
        return '₱' . number_format($this->total_earnings ?? 0, 2);
    }

    // ==========================================
    // MAX HOURS ENFORCEMENT METHODS (Phase 3)
    // ==========================================

    /**
     * Check if this assignment has a max hours limit
     */
    public function hasMaxHoursLimit(): bool
    {
        return $this->max_hours !== null && $this->max_hours > 0;
    }

    /**
     * Get remaining billable hours for this assignment
     */
    public function getRemainingBillableHours(): ?float
    {
        if (!$this->hasMaxHoursLimit()) {
            return null; // No limit
        }

        $remaining = (float) $this->max_hours - (float) $this->total_billable_hours;
        return max(0, $remaining);
    }

    /**
     * Check if max hours limit has been reached
     */
    public function isMaxHoursReached(): bool
    {
        if (!$this->hasMaxHoursLimit()) {
            return false;
        }

        return (float) $this->total_billable_hours >= (float) $this->max_hours;
    }

    /**
     * Check if approaching max hours limit (80% threshold)
     */
    public function isApproachingMaxHours(): bool
    {
        if (!$this->hasMaxHoursLimit()) {
            return false;
        }

        $percentage = ((float) $this->total_billable_hours / (float) $this->max_hours) * 100;
        return $percentage >= 80 && $percentage < 100;
    }

    /**
     * Get max hours utilization percentage
     */
    public function getMaxHoursUtilizationPercentage(): ?float
    {
        if (!$this->hasMaxHoursLimit()) {
            return null;
        }

        return min(100, round(((float) $this->total_billable_hours / (float) $this->max_hours) * 100, 2));
    }

    /**
     * Calculate billable minutes for a new time entry
     * Returns array with 'billable_minutes', 'non_billable_minutes', 'is_capped'
     */
    public function calculateBillableMinutes(int $durationMinutes): array
    {
        // If no max hours limit, all time is billable
        if (!$this->hasMaxHoursLimit()) {
            return [
                'billable_minutes' => $durationMinutes,
                'non_billable_minutes' => 0,
                'is_capped' => false,
            ];
        }

        $remainingBillableHours = $this->getRemainingBillableHours();
        $remainingBillableMinutes = (int) ($remainingBillableHours * 60);

        // If already at limit
        if ($remainingBillableMinutes <= 0) {
            return [
                'billable_minutes' => 0,
                'non_billable_minutes' => $durationMinutes,
                'is_capped' => true,
            ];
        }

        // If this entry would exceed limit
        if ($durationMinutes > $remainingBillableMinutes) {
            return [
                'billable_minutes' => $remainingBillableMinutes,
                'non_billable_minutes' => $durationMinutes - $remainingBillableMinutes,
                'is_capped' => true,
            ];
        }

        // Entry fits within limit
        return [
            'billable_minutes' => $durationMinutes,
            'non_billable_minutes' => 0,
            'is_capped' => false,
        ];
    }

    /**
     * Update cached hour totals from time entries
     */
    public function updateHourTotals(): void
    {
        $timeEntries = $this->timeEntries()
            ->whereNotNull('end_time')
            ->get();

        $totalLogged = $timeEntries->sum('duration_minutes') / 60;
        $totalBillable = $timeEntries->sum(function ($entry) {
            return ($entry->billable_minutes ?? $entry->duration_minutes) / 60;
        });

        $this->update([
            'total_hours_logged' => round($totalLogged, 2),
            'total_billable_hours' => round($totalBillable, 2),
        ]);
    }

    /**
     * Get max hours status label with color context
     */
    public function getMaxHoursStatus(): array
    {
        if (!$this->hasMaxHoursLimit()) {
            return [
                'label' => 'Unlimited',
                'color' => 'neutral',
                'icon' => 'infinity',
            ];
        }

        $percentage = $this->getMaxHoursUtilizationPercentage();

        if ($percentage >= 100) {
            return [
                'label' => 'Limit Reached',
                'color' => 'error',
                'icon' => 'ban',
            ];
        }

        if ($percentage >= 80) {
            return [
                'label' => 'Approaching Limit',
                'color' => 'warning',
                'icon' => 'exclamation-triangle',
            ];
        }

        return [
            'label' => 'Within Limit',
            'color' => 'success',
            'icon' => 'check-circle',
        ];
    }
}
