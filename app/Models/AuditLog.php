<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    protected $fillable = [
        'auditable_type',
        'auditable_id',
        'user_id',
        'action',
        'event_type',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    // Disable updated_at since we only need created_at
    public $timestamps = false;
    protected $dates = ['created_at'];

    /**
     * Get the auditable model
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an audit event
     */
    public static function logAction(
        $model,
        string $action,
        array $oldValues = null,
        array $newValues = null,
        string $eventType = 'model_change',
        array $metadata = []
    ): void {
        self::create([
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'user_id' => Auth::id(),
            'action' => $action,
            'event_type' => $eventType,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Log sensitive action
     */
    public static function logSensitiveAction(string $action, array $metadata = []): void
    {
        self::create([
            'auditable_type' => null,
            'auditable_id' => null,
            'user_id' => Auth::id(),
            'action' => $action,
            'event_type' => 'sensitive_action',
            'old_values' => null,
            'new_values' => null,
            'metadata' => array_merge($metadata, [
                'url' => Request::fullUrl(),
                'method' => Request::method(),
            ]),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get formatted action description
     */
    public function getFormattedActionAttribute(): string
    {
        $userName = $this->user ? $this->user->fullName : 'System';
        $action = ucfirst(str_replace('_', ' ', $this->action));
        
        if ($this->auditable_type) {
            $modelName = class_basename($this->auditable_type);
            return "{$userName} {$action} {$modelName} #{$this->auditable_id}";
        }
        
        return "{$userName} performed: {$action}";
    }
}
