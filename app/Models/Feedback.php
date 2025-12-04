<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    /**
     * Feedback status constants.
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    /**
     * Feedback type constants.
     */
    public const TYPE_GENERAL = 'general';
    public const TYPE_SERVICE = 'service';
    public const TYPE_TECHNICAL = 'technical';
    public const TYPE_COMPLAINT = 'complaint';
    public const TYPE_SUGGESTION = 'suggestion';

    /**
     * Feedback priority constants.
     */
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    /**
     * Get all available statuses.
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_REVIEWED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_RESOLVED,
            self::STATUS_CLOSED,
        ];
    }

    /**
     * Get all available types.
     */
    public static function types(): array
    {
        return [
            self::TYPE_GENERAL,
            self::TYPE_SERVICE,
            self::TYPE_TECHNICAL,
            self::TYPE_COMPLAINT,
            self::TYPE_SUGGESTION,
        ];
    }

    /**
     * Get all available priorities.
     */
    public static function priorities(): array
    {
        return [
            self::PRIORITY_LOW,
            self::PRIORITY_MEDIUM,
            self::PRIORITY_HIGH,
            self::PRIORITY_URGENT,
        ];
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'client_id',
        'adiutor_id',
        'task_id',
        'project_id',
        'title',
        'message',
        'rating',
        'type',
        'status',
        'priority',
        'admin_response',
        'responded_by',
        'responded_at',
        'resolved_by',
        'resolved_at',
        'internal_notes',
        'category',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'responded_at' => 'datetime',
        'resolved_at' => 'datetime',
        'rating' => 'integer',
        'tags' => 'array',
    ];

    /**
     * Get the client who gave this feedback.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the adiutor who received this feedback.
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Get the task related to this feedback.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'taskID');
    }

    /**
     * Get the project related to this feedback.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the admin who responded to this feedback.
     */
    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Get the admin who resolved this feedback.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Alias for backwards compatibility
     */
    public function form(): BelongsTo
    {
        return $this->project();
    }

    /**
     * Alias for responses relationship (for backwards compatibility)
     */
    public function responses()
    {
        return collect([]); // Return empty collection if no responses table
    }

    /**
     * Get the rating stars as HTML.
     */
    public function getRatingStarsAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                $stars .= '<i class="far fa-star text-gray-300"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'reviewed', 'in_progress' => 'bg-blue-100 text-blue-800',
            'resolved', 'closed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Check if feedback is positive (4-5 stars).
     */
    public function isPositive(): bool
    {
        return $this->rating >= 4;
    }

    /**
     * Check if feedback is negative (1-2 stars).
     */
    public function isNegative(): bool
    {
        return $this->rating <= 2;
    }
}