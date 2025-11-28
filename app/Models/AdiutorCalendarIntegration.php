<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdiutorCalendarIntegration extends Model
{
    protected $fillable = [
        'adiutor_id',
        'provider',
        'calendar_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'is_connected',
        'last_synced_at',
        'sync_settings',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'is_connected' => 'boolean',
        'sync_settings' => 'array',
    ];

    /**
     * Get the adiutor that owns this calendar integration
     */
    public function adiutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adiutor_id');
    }

    /**
     * Check if token is expired or about to expire (within 5 minutes)
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return true;
        }

        return $this->token_expires_at->subMinutes(5)->isPast();
    }

    /**
     * Check if calendar is connected and token is valid
     */
    public function isActive(): bool
    {
        return $this->is_connected && !$this->isTokenExpired();
    }
}
