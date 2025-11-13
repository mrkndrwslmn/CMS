<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdiutorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'title',
        'standard_hourly_rate',
        'currency',
        'minimum_payout_amount',
        'preferred_payout_method',
        'payout_details',
        'availability',
        'portfolio_url',
        'linkedin_url',
        'github_url',
        'experience',
        'languages',
        'location',
        'is_verified',
        'rating',
        'total_projects',
        'status',
    ];

    protected $casts = [
        'availability' => 'array',
        'languages' => 'array',
        'standard_hourly_rate' => 'decimal:2',
        'minimum_payout_amount' => 'decimal:2',
        'payout_details' => 'array',
        'rating' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user that owns the adiutor profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the skills associated with this adiutor
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'adiutor_skills', 'adiutor_id', 'skill_id')
                    ->withPivot('proficiency_level', 'years_experience')
                    ->withTimestamps();
    }

    /**
     * Check if adiutor is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get formatted rating
     */
    public function getFormattedRating(): string
    {
        return number_format($this->rating, 1);
    }

    /**
     * Get formatted standard hourly rate
     */
    public function getFormattedStandardRate(): string
    {
        return '₱' . number_format($this->standard_hourly_rate ?? 0, 2) . '/hr';
    }

    /**
     * Check if payout details are configured
     */
    public function hasPayoutDetails(): bool
    {
        return !empty($this->payout_details) && !empty($this->preferred_payout_method);
    }

    /**
     * Get payout method label
     */
    public function getPayoutMethodLabel(): string
    {
        return match($this->preferred_payout_method) {
            'bank_transfer' => 'Bank Transfer',
            'paypal' => 'PayPal',
            'gcash' => 'GCash',
            'paymaya' => 'PayMaya',
            'other' => 'Other',
            default => 'Not Set'
        };
    }
}