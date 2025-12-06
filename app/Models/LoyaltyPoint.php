<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class LoyaltyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_points',
        'available_points',
        'lifetime_earned',
        'lifetime_redeemed',
        'tier',
        'tier_achieved_at',
        'points_to_next_tier',
        'last_earned_at',
        'last_redeemed_at',
    ];

    protected $casts = [
        'tier_achieved_at' => 'datetime',
        'last_earned_at' => 'datetime',
        'last_redeemed_at' => 'datetime',
    ];

    /**
     * Get the user who owns these loyalty points
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all transactions for this loyalty account
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(LoyaltyTransaction::class, 'user_id', 'user_id');
    }

    /**
     * Get tier configuration
     */
    public function tierConfig(): ?LoyaltyTier
    {
        return LoyaltyTier::where('tier', $this->tier)->first();
    }

    /**
     * Earn points (add to balance)
     */
    public function earnPoints(
        int $points,
        string $source,
        string $description,
        $relatedModel = null,
        ?\DateTime $expiresAt = null
    ): LoyaltyTransaction {
        return DB::transaction(function () use ($points, $source, $description, $relatedModel, $expiresAt) {
            // Lock this record to prevent concurrent modifications
            $locked = static::where('id', $this->id)->lockForUpdate()->first();
            
            $balanceBefore = $locked->available_points;

            $locked->increment('total_points', $points);
            $locked->increment('available_points', $points);
            $locked->increment('lifetime_earned', $points);
            $locked->update(['last_earned_at' => now()]);

            $balanceAfter = $balanceBefore + $points;

            // Default expiry to configured months if not provided
            $expiryDate = $expiresAt ?? now()->addMonths(config('loyalty.points.expiry_months', 12));

            // Create transaction record
            $transaction = LoyaltyTransaction::create([
                'user_id' => $this->user_id,
                'transaction_type' => 'earned',
                'points' => $points,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source' => $source,
                'description' => $description,
                'service_request_id' => $relatedModel instanceof ServiceRequest ? $relatedModel->id : null,
                'payment_id' => $relatedModel instanceof Payment ? $relatedModel->id : null,
                'expires_at' => $expiryDate,
            ]);

            // Refresh this instance with locked data
            $this->refresh();

            // Check for tier upgrade
            $this->checkAndUpgradeTier();

            return $transaction;
        });
    }

    /**
     * Redeem points (deduct from balance)
     */
    public function redeemPoints(
        int $points,
        string $source,
        string $description,
        $relatedModel = null
    ): LoyaltyTransaction {
        return DB::transaction(function () use ($points, $source, $description, $relatedModel) {
            // Lock this record to prevent concurrent modifications
            $locked = static::where('id', $this->id)->lockForUpdate()->first();
            
            if ($points > $locked->available_points) {
                throw new \Exception('Insufficient loyalty points. Available: ' . $locked->available_points);
            }

            $balanceBefore = $locked->available_points;

            $locked->decrement('total_points', $points);
            $locked->decrement('available_points', $points);
            $locked->increment('lifetime_redeemed', $points);
            $locked->update(['last_redeemed_at' => now()]);

            $balanceAfter = $balanceBefore - $points;

            // Refresh this instance with locked data
            $this->refresh();

            // Create transaction record (negative points)
            return LoyaltyTransaction::create([
                'user_id' => $this->user_id,
                'transaction_type' => 'redeemed',
                'points' => -$points,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source' => $source,
                'description' => $description,
                'service_request_id' => $relatedModel instanceof ServiceRequest ? $relatedModel->id : null,
                'payment_id' => $relatedModel instanceof Payment ? $relatedModel->id : null,
                'coupon_id' => $relatedModel instanceof Coupon ? $relatedModel->id : null,
            ]);
        });
    }

    /**
     * Refund points (e.g., on payment failure)
     */
    public function refundPoints(
        int $points,
        string $description,
        $relatedModel = null
    ): LoyaltyTransaction {
        return DB::transaction(function () use ($points, $description, $relatedModel) {
            // Lock this record to prevent concurrent modifications
            $locked = static::where('id', $this->id)->lockForUpdate()->first();
            
            $balanceBefore = $locked->available_points;

            $locked->increment('total_points', $points);
            $locked->increment('available_points', $points);
            $locked->decrement('lifetime_redeemed', $points);

            $balanceAfter = $balanceBefore + $points;

            // Refresh this instance with locked data
            $this->refresh();

            return LoyaltyTransaction::create([
                'user_id' => $this->user_id,
                'transaction_type' => 'refunded',
                'points' => $points,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source' => 'points_refund',
                'description' => $description,
                'service_request_id' => $relatedModel instanceof ServiceRequest ? $relatedModel->id : null,
                'payment_id' => $relatedModel instanceof Payment ? $relatedModel->id : null,
            ]);
        });
    }

    /**
     * Manual adjustment (admin only)
     */
    public function adjustPoints(
        int $points,
        string $reason,
        User $performedBy
    ): LoyaltyTransaction {
        return DB::transaction(function () use ($points, $reason, $performedBy) {
            // Lock this record to prevent concurrent modifications
            $locked = static::where('id', $this->id)->lockForUpdate()->first();
            
            // Validate deduction doesn't exceed balance
            if ($points < 0 && abs($points) > $locked->available_points) {
                throw new \Exception('Cannot deduct more than available balance: ' . $locked->available_points);
            }
            
            $balanceBefore = $locked->available_points;

            if ($points > 0) {
                $locked->increment('total_points', abs($points));
                $locked->increment('available_points', abs($points));
                $locked->increment('lifetime_earned', abs($points));
            } else {
                $locked->decrement('total_points', abs($points));
                $locked->decrement('available_points', abs($points));
                $locked->increment('lifetime_redeemed', abs($points));
            }

            $balanceAfter = $balanceBefore + $points;

            // Refresh this instance with locked data
            $this->refresh();

            return LoyaltyTransaction::create([
                'user_id' => $this->user_id,
                'transaction_type' => 'adjusted',
                'points' => $points,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source' => 'manual_adjustment',
                'description' => $reason,
                'performed_by' => $performedBy->id,
            ]);
        });
    }

    /**
     * Expire points (system-level, no user required)
     */
    public function expirePoints(
        int $points,
        string $description
    ): LoyaltyTransaction {
        return DB::transaction(function () use ($points, $description) {
            // Lock this record to prevent concurrent modifications
            $locked = static::where('id', $this->id)->lockForUpdate()->first();
            
            $balanceBefore = $locked->available_points;

            $locked->decrement('total_points', abs($points));
            $locked->decrement('available_points', abs($points));

            $balanceAfter = $balanceBefore - abs($points);

            // Refresh this instance with locked data
            $this->refresh();

            return LoyaltyTransaction::create([
                'user_id' => $this->user_id,
                'transaction_type' => 'expired',
                'points' => -abs($points),
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'source' => 'points_expired',
                'description' => $description,
            ]);
        });
    }

    /**
     * Calculate current tier based on lifetime earned points
     */
    public function calculateTier(): string
    {
        $tiersConfig = config('loyalty.tiers', [
            'bronze' => ['points' => 0],
            'silver' => ['points' => 5000],
            'gold' => ['points' => 15000],
            'platinum' => ['points' => 50000],
        ]);

        $currentTier = 'bronze';

        foreach ($tiersConfig as $tier => $tierData) {
            // Handle both array and flat formats
            $requiredPoints = is_array($tierData) ? ($tierData['points'] ?? 0) : $tierData;
            if ($this->lifetime_earned >= $requiredPoints) {
                $currentTier = $tier;
            }
        }

        return $currentTier;
    }

    /**
     * Check and upgrade tier if threshold met
     */
    public function checkAndUpgradeTier(): bool
    {
        $newTier = $this->calculateTier();

        if ($newTier !== $this->tier) {
            $oldTier = $this->tier;
            
            $this->update([
                'tier' => $newTier,
                'tier_achieved_at' => now(),
                'points_to_next_tier' => $this->calculatePointsToNextTier($newTier),
            ]);

            // Dispatch tier upgrade event/notification
            event(new \App\Events\TierUpgraded($this->user, $oldTier, $newTier));

            return true;
        }

        // Update points to next tier even if no upgrade
        $this->update(['points_to_next_tier' => $this->calculatePointsToNextTier()]);

        return false;
    }

    /**
     * Calculate points needed to reach next tier
     */
    public function calculatePointsToNextTier(?string $currentTier = null): int
    {
        $tier = $currentTier ?? $this->tier;
        
        $tiersConfig = config('loyalty.tiers', [
            'bronze' => ['points' => 0],
            'silver' => ['points' => 5000],
            'gold' => ['points' => 15000],
            'platinum' => ['points' => 50000],
        ]);

        // Extract points from tier config (handle both array and flat formats)
        $tiers = [];
        foreach ($tiersConfig as $tierName => $tierData) {
            $tiers[$tierName] = is_array($tierData) ? ($tierData['points'] ?? 0) : $tierData;
        }

        $tierOrder = ['bronze', 'silver', 'gold', 'platinum'];
        $currentIndex = array_search($tier, $tierOrder);

        // If platinum (highest tier), return 0
        if ($currentIndex === count($tierOrder) - 1) {
            return 0;
        }

        $nextTier = $tierOrder[$currentIndex + 1];
        $nextTierPoints = $tiers[$nextTier];

        return max(0, $nextTierPoints - $this->lifetime_earned);
    }

    /**
     * Get tier earning rate (points per 100 pesos)
     */
    public function getEarningRate(): int
    {
        $rates = config('loyalty.points.earning_rate', [
            'bronze' => 1,
            'silver' => 2,
            'gold' => 3,
            'platinum' => 5,
        ]);

        return $rates[$this->tier] ?? 1;
    }

    /**
     * Get tier discount percentage
     */
    public function getTierDiscount(): int
    {
        $discounts = [
            'bronze' => 0,
            'silver' => 5,
            'gold' => 10,
            'platinum' => 15,
        ];

        return $discounts[$this->tier] ?? 0;
    }

    /**
     * Get tier badge color for UI
     */
    public function getTierColor(): string
    {
        return match($this->tier) {
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'platinum' => '#E5E4E2',
            default => '#6B7280'
        };
    }

    /**
     * Get tier display name
     */
    public function getTierName(): string
    {
        return ucfirst($this->tier);
    }

    /**
     * Get expiring points (points that will expire soon)
     */
    public function getExpiringPoints(int $days = 30): int
    {
        return $this->transactions()
            ->where('transaction_type', 'earned')
            ->where('expired', false)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)])
            ->sum('points');
    }

    /**
     * Check if user has enough points
     */
    public function hasEnoughPoints(int $requiredPoints): bool
    {
        return $this->available_points >= $requiredPoints;
    }

    /**
     * Convert points to monetary value
     */
    public function convertPointsToMoney(int $points): float
    {
        $conversionRate = config('loyalty.points.conversion_rate', 1);
        return $points * $conversionRate;
    }

    /**
     * Scope: Get by tier
     */
    public function scopeByTier($query, string $tier)
    {
        return $query->where('tier', $tier);
    }

    /**
     * Scope: Get users with points above threshold
     */
    public function scopeWithPointsAbove($query, int $points)
    {
        return $query->where('available_points', '>=', $points);
    }
}
