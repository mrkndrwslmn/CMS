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
        'hourly_rate',
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
        'hourly_rate' => 'decimal:2',
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
        return $this->belongsToMany(Skill::class, 'adiutor_skills', 'user_id', 'skill_id')
                    ->withPivot('proficiency', 'years_experience')
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
}