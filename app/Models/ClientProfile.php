<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'industry',
        'company_size',
        'address',
        'website',
        'preferred_contact_methods',
        'timezone',
        'business_hours',
        'notes',
        'is_verified',
        'total_projects',
        'total_spent',
        'client_type',
    ];

    protected $casts = [
        'preferred_contact_methods' => 'array',
        'business_hours' => 'array',
        'is_verified' => 'boolean',
        'total_spent' => 'decimal:2',
    ];

    /**
     * Get the user that owns the client profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if client is verified
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * Get formatted total spent
     */
    public function getFormattedTotalSpent(): string
    {
        return '$' . number_format($this->total_spent, 2);
    }

    /**
     * Get client type badge color
     */
    public function getClientTypeColor(): string
    {
        return match($this->client_type) {
            'individual' => 'primary',
            'small_business' => 'success',
            'enterprise' => 'accent',
            default => 'neutral'
        };
    }
}
