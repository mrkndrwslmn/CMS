<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $table = 'project_assignments';

    protected $fillable = [
        'project_id',
        'adiutor_id',
        'agreed_rate',
        'hourly_rate',
        'requires_time_tracking',
        'total_earnings',
        'total_hours_worked',
        'start_date',
        'expected_completion',
        'status',
        'notes',
        'progress_percentage'
    ];

    protected $casts = [
        'start_date' => 'date',
        'expected_completion' => 'date',
        'agreed_rate' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'requires_time_tracking' => 'boolean',
        'total_earnings' => 'decimal:2',
        'total_hours_worked' => 'decimal:2',
        'progress_percentage' => 'integer'
    ];

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
     * Get time entries for this assignment
     */
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class, 'project_id', 'project_id')
            ->where('adiutor_id', $this->adiutor_id);
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
}
