<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Client Profile Model
 * 
 * Extended profile information for users with role='client'.
 * Contains business information, preferences, and aggregated statistics.
 *
 * @property int $id
 * @property int $user_id Foreign key to users table
 * @property string|null $company_name Client's company or business name
 * @property string|null $company_description Brief description of the company
 * @property string|null $industry Industry sector (Technology, Healthcare, Finance, etc.)
 * @property string|null $company_size Size category (1-10, 11-50, 51-200, 201-500, 500+)
 * @property string|null $address Business address
 * @property string|null $website Company website URL
 * @property array|null $preferred_contact_methods Array of contact preferences (email, phone, chat)
 * @property string|null $timezone Client's timezone identifier
 * @property array|null $business_hours Operating hours by day
 * @property string|null $notes Internal notes about the client
 * @property bool $is_verified Whether the client has been verified
 * @property int $total_projects Cached count of total projects
 * @property float $total_spent Cached sum of all payments
 * @property string|null $client_type Type: individual, small_business, enterprise
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user The user this profile belongs to
 *
 * @see \App\Models\User::clientProfile()
 */
class ClientProfile extends Model
{
    use HasFactory;

    /**
     * Valid client types.
     */
    public const CLIENT_TYPES = ['individual', 'small_business', 'enterprise'];

    /**
     * Valid industry options.
     */
    public const INDUSTRIES = [
        'Technology',
        'Healthcare', 
        'Finance',
        'Education',
        'Retail',
        'Manufacturing',
        'Marketing',
        'Real Estate',
        'Non-profit',
        'Other',
    ];

    /**
     * Valid company size ranges.
     */
    public const COMPANY_SIZES = ['1-10', '11-50', '51-200', '201-500', '500+'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'preferred_contact_methods' => 'array',
        'business_hours' => 'array',
        'is_verified' => 'boolean',
        'total_spent' => 'decimal:2',
    ];

    /**
     * Get the user that owns the client profile.
     *
     * @return BelongsTo<User, ClientProfile>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if client is verified.
     *
     * @return bool True if the client has been verified
     */
    public function isVerified(): bool
    {
        return $this->is_verified ?? false;
    }

    /**
     * Get formatted total spent with currency symbol.
     *
     * @return string Formatted amount (e.g., "$1,234.56")
     */
    public function getFormattedTotalSpent(): string
    {
        return '₱' . number_format($this->total_spent ?? 0, 2);
    }

    /**
     * Get the badge color class for the client type.
     *
     * @return string CSS color class name (primary, success, accent, neutral)
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

    /**
     * Get a human-readable label for the client type.
     *
     * @return string Formatted client type label
     */
    public function getClientTypeLabel(): string
    {
        return match($this->client_type) {
            'individual' => 'Individual',
            'small_business' => 'Small Business',
            'enterprise' => 'Enterprise',
            default => 'Unknown'
        };
    }

    /**
     * Scope to filter by verified clients only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope to filter by client type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type The client type to filter by
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('client_type', $type);
    }
}
