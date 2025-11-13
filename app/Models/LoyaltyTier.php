<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier',
        'points_required',
        'earning_rate_percentage',
        'discount_percentage',
        'benefits',
        'badge_icon',
        'badge_color',
        'order',
    ];

    protected $casts = [
        'benefits' => 'array',
    ];

    /**
     * Get benefits as array
     */
    public function getBenefitsList(): array
    {
        return $this->benefits ?? [];
    }

    /**
     * Get tier display name
     */
    public function getDisplayName(): string
    {
        return ucfirst($this->tier) . ' Tier';
    }

    /**
     * Scope: Get tiers in order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Static: Get tier configuration by name
     */
    public static function getTierConfig(string $tierName): ?self
    {
        return self::where('tier', $tierName)->first();
    }

    /**
     * Static: Get all tiers ordered
     */
    public static function getAllTiers(): \Illuminate\Database\Eloquent\Collection
    {
        return self::ordered()->get();
    }
}
