<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'config',
        'created_by',
        'is_public'
    ];

    protected $casts = [
        'config' => 'array',
        'is_public' => 'boolean'
    ];

    /**
     * Get the user who created this template
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for public templates
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for user's own templates
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope for accessible templates (public or owned by user)
     */
    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('is_public', true)
              ->orWhere('created_by', $userId);
        });
    }
}
