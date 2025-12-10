<?php

namespace App\Services;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTransaction;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Payment;
use App\Events\TierUpgraded;
use App\Mail\LoyaltyPointsEarnedMail;
use App\Mail\TierUpgradedMail;
use App\Services\MilestoneService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoyaltyService
{
    /**
     * Cache key prefixes
     */
    private const CACHE_PREFIX = 'loyalty:';
    private const CACHE_TTL_TIERS = 3600; // 1 hour for tier config
    private const CACHE_TTL_STATS = 300;  // 5 minutes for statistics
    private const CACHE_TTL_USER = 60;    // 1 minute for user-specific data
    /**
     * Calculate points earned for a payment
     * 
     * @param Payment $payment
     * @return int
     */
    public function calculatePointsForPayment(Payment $payment): int
    {
        // Get the client (user) from the payment
        $user = $payment->client;
        
        if (!$user) {
            Log::warning('Payment has no associated client', ['payment_id' => $payment->id]);
            return 0;
        }
        
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $earningRate = $loyaltyPoint->getEarningRate();
        
        // Base calculation: 1 point per ₱100 spent, multiplied by tier rate
        // Example: ₱10,000 payment * (2% / 100) = 200 points for Silver tier
        $basePoints = floor($payment->amount / 100);
        $earnedPoints = floor($basePoints * ($earningRate / 100));

        return max(1, $earnedPoints); // Minimum 1 point
    }

    /**
     * Award points for completed payment
     * 
     * @param ServiceRequest $request
     * @param Payment $payment
     * @return void
     */
    public function awardPointsForPayment(ServiceRequest $request, Payment $payment): void
    {
        try {
            $user = $request->client;
            $points = $this->calculatePointsForPayment($payment);

            // Check for first project bonus
            $isFirstProject = $user->serviceRequests()
                ->whereHas('payments', function ($query) {
                    $query->where('status', 'completed');
                })
                ->count() === 1;

            if ($isFirstProject) {
                $firstProjectBonus = config('loyalty.bonuses.first_project', 500);
                $points += $firstProjectBonus;
            }

            // Award the points
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            $loyaltyPoint->earnPoints(
                $points,
                'payment_completed',
                "Payment completed for {$request->project_name}",
                $request
            );

            Log::info('Loyalty points awarded for payment', [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
                'service_request_id' => $request->id,
                'points' => $points,
                'is_first_project' => $isFirstProject,
            ]);

            // Clear caches after earning points
            $this->clearUserCache($user);
            $this->clearGlobalCache();

            // Send points earned email notification
            try {
                $user->refresh();
                $user->load('loyaltyPoints');
                
                // Get the transaction that was just created
                $transaction = LoyaltyTransaction::where('user_id', $user->id)
                    ->where('payment_id', $payment->id)
                    ->where('transaction_type', 'earned')
                    ->latest()
                    ->first();
                
                if ($transaction) {
                    Mail::to($user->email)
                        ->queue(new LoyaltyPointsEarnedMail($user, $transaction));
                    
                    Log::info('Loyalty points earned email queued', [
                        'user_id' => $user->id,
                        'points' => $points,
                        'transaction_id' => $transaction->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send loyalty points earned email', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'payment_id' => $payment->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to award loyalty points for payment', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'service_request_id' => $request->id,
            ]);
        }
    }

    /**
     * Award milestone bonus points
     * 
     * @param ServiceRequest $request
     * @param string $milestoneName
     * @return void
     */
    public function awardMilestoneBonus(ServiceRequest $request, string $milestoneName): void
    {
        try {
            $user = $request->client;
            $bonusPoints = config('loyalty.bonuses.milestone_completion', 200);

            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            $loyaltyPoint->earnPoints(
                $bonusPoints,
                'milestone_completed',
                "Milestone '{$milestoneName}' completed for {$request->project_name}",
                $request
            );

            Log::info('Milestone bonus points awarded', [
                'user_id' => $user->id,
                'service_request_id' => $request->id,
                'milestone' => $milestoneName,
                'points' => $bonusPoints,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to award milestone bonus points', [
                'error' => $e->getMessage(),
                'service_request_id' => $request->id,
            ]);
        }
    }

    /**
     * Award project completion bonus
     * 
     * @param ServiceRequest $request
     * @return void
     */
    public function awardProjectCompletionBonus(ServiceRequest $request): void
    {
        try {
            $user = $request->client;
            $bonusPoints = config('loyalty.bonuses.project_completion', 500);

            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            $loyaltyPoint->earnPoints(
                $bonusPoints,
                'project_completed',
                "Project '{$request->project_name}' completed successfully",
                $request
            );

            Log::info('Project completion bonus points awarded', [
                'user_id' => $user->id,
                'service_request_id' => $request->id,
                'points' => $bonusPoints,
            ]);

            // Clear caches after earning points
            $this->clearUserCache($user);
            $this->clearGlobalCache();

            // Send points earned email notification
            try {
                $user->refresh();
                $user->load('loyaltyPoints');
                
                // Get the transaction that was just created
                $transaction = LoyaltyTransaction::where('user_id', $user->id)
                    ->where('service_request_id', $request->id)
                    ->where('transaction_type', 'earned')
                    ->where('action_type', 'project_completed')
                    ->latest()
                    ->first();
                
                if ($transaction) {
                    Mail::to($user->email)
                        ->queue(new LoyaltyPointsEarnedMail($user, $transaction));
                    
                    Log::info('Project completion bonus email queued', [
                        'user_id' => $user->id,
                        'points' => $bonusPoints,
                        'transaction_id' => $transaction->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send project completion bonus email', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to award project completion bonus', [
                'error' => $e->getMessage(),
                'service_request_id' => $request->id,
            ]);
        }
    }

    /**
     * Award referral bonus points
     * 
     * @param User $referrer
     * @param User $referred
     * @return void
     */
    public function awardReferralBonus(User $referrer, User $referred): void
    {
        try {
            $bonusPoints = config('loyalty.bonuses.referral', 1000);

            $loyaltyPoint = $referrer->getOrCreateLoyaltyPoints();
            $loyaltyPoint->earnPoints(
                $bonusPoints,
                'referral',
                "Referral bonus for inviting {$referred->fullName}"
            );

            Log::info('Referral bonus points awarded', [
                'referrer_id' => $referrer->id,
                'referred_id' => $referred->id,
                'points' => $bonusPoints,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to award referral bonus points', [
                'error' => $e->getMessage(),
                'referrer_id' => $referrer->id,
            ]);
        }
    }

    /**
     * Award feedback submission bonus
     * 
     * @param User $user
     * @param ServiceRequest $request
     * @return void
     */
    public function awardFeedbackBonus(User $user, ServiceRequest $request): void
    {
        try {
            $bonusPoints = config('loyalty.bonuses.feedback_submission', 100);

            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            $loyaltyPoint->earnPoints(
                $bonusPoints,
                'feedback_submitted',
                "Feedback submitted for {$request->project_name}",
                $request
            );

            Log::info('Feedback bonus points awarded', [
                'user_id' => $user->id,
                'service_request_id' => $request->id,
                'points' => $bonusPoints,
            ]);

            // Clear caches after earning points
            $this->clearUserCache($user);
            $this->clearGlobalCache();

            // Send points earned email notification
            try {
                $user->refresh();
                $user->load('loyaltyPoints');
                
                // Get the transaction that was just created
                $transaction = LoyaltyTransaction::where('user_id', $user->id)
                    ->where('service_request_id', $request->id)
                    ->where('transaction_type', 'earned')
                    ->where('source', 'feedback_bonus')
                    ->latest()
                    ->first();
                
                if ($transaction) {
                    Mail::to($user->email)
                        ->queue(new LoyaltyPointsEarnedMail($user, $transaction));
                    
                    Log::info('Feedback bonus email queued', [
                        'user_id' => $user->id,
                        'points' => $bonusPoints,
                        'transaction_id' => $transaction->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send feedback bonus email', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to award feedback bonus points', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
        }
    }

    /**
     * Convert points to discount amount
     * 
     * @param int $points
     * @return float
     */
    public function convertPointsToDiscount(int $points): float
    {
        $conversionRate = config('loyalty.points.conversion_rate', 1);
        return $points * $conversionRate;
    }

    /**
     * Apply automatic tier discount to service request
     * This is applied when a request is approved, based on the client's current tier
     * 
     * @param ServiceRequest $request
     * @return bool True if discount was applied
     */
    public function applyTierDiscount(ServiceRequest $request): bool
    {
        try {
            $user = $request->client;
            
            if (!$user) {
                Log::warning('Cannot apply tier discount: no client found', [
                    'service_request_id' => $request->id,
                ]);
                return false;
            }

            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            $tierDiscount = $loyaltyPoint->getTierDiscount();
            
            // If no tier discount (Bronze = 0%), skip
            if ($tierDiscount <= 0) {
                Log::info('No tier discount to apply (Bronze tier)', [
                    'service_request_id' => $request->id,
                    'user_id' => $user->id,
                    'tier' => $loyaltyPoint->tier,
                ]);
                return false;
            }

            // Check if tier discount can be stacked with existing coupon
            if ($request->applied_coupon_id && $request->appliedCoupon) {
                $coupon = $request->appliedCoupon;
                if (!$coupon->stackable_with_loyalty_tier) {
                    Log::info('Tier discount not applied: coupon is not stackable with tier discount', [
                        'service_request_id' => $request->id,
                        'coupon_code' => $coupon->code,
                        'tier' => $loyaltyPoint->tier,
                    ]);
                    return false;
                }
            }

            // Store original budget if not already stored
            if (!$request->original_approved_budget) {
                $request->original_approved_budget = $request->approved_budget;
            }

            // Calculate tier discount amount
            $currentBudget = $request->approved_budget;
            $discountAmount = round(($currentBudget * $tierDiscount) / 100, 2);

            // Apply tier discount
            $request->update([
                'tier_discount_amount' => $discountAmount,
                'tier_discount_percentage' => $tierDiscount,
                'tier_at_approval' => $loyaltyPoint->tier,
                'approved_budget' => $currentBudget - $discountAmount,
                'total_discount_amount' => ($request->total_discount_amount ?? 0) + $discountAmount,
            ]);

            Log::info('Tier discount applied to service request', [
                'service_request_id' => $request->id,
                'user_id' => $user->id,
                'tier' => $loyaltyPoint->tier,
                'discount_percentage' => $tierDiscount,
                'discount_amount' => $discountAmount,
                'original_budget' => $request->original_approved_budget,
                'new_budget' => $currentBudget - $discountAmount,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to apply tier discount', [
                'error' => $e->getMessage(),
                'service_request_id' => $request->id,
            ]);
            return false;
        }
    }

    /**
     * Get tier discount percentage for a user
     * 
     * @param User $user
     * @return int
     */
    public function getUserTierDiscount(User $user): int
    {
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        return $loyaltyPoint->getTierDiscount();
    }

    /**
     * Apply loyalty discount to service request
     * 
     * @param ServiceRequest $request
     * @param int $points
     * @return bool
     */
    public function applyLoyaltyDiscount(ServiceRequest $request, int $points): bool
    {
        try {
            DB::beginTransaction();

            $user = $request->client;
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

            // Validate points availability
            if ($points > $loyaltyPoint->available_points) {
                throw new \Exception('Insufficient loyalty points available.');
            }

            // Check minimum redemption
            $minRedemption = config('loyalty.points.minimum_redemption', 100);
            if ($points < $minRedemption) {
                throw new \Exception("Minimum redemption is {$minRedemption} points.");
            }

            // Calculate discount
            $discount = $this->convertPointsToDiscount($points);

            // Check maximum redemption percentage
            $maxRedemptionPercentage = config('loyalty.points.maximum_redemption_percentage', 50);
            $currentAmount = $request->approved_budget;
            $maxDiscount = $currentAmount * ($maxRedemptionPercentage / 100);

            if ($discount > $maxDiscount) {
                throw new \Exception("Maximum points redemption is {$maxRedemptionPercentage}% of order value.");
            }

            // Store original budget if not already stored
            if (!$request->original_approved_budget) {
                $request->original_approved_budget = $request->approved_budget;
            }

            // Apply loyalty discount
            $request->update([
                'loyalty_points_used' => $points,
                'loyalty_discount_amount' => $discount,
                'approved_budget' => $request->approved_budget - $discount,
                'total_discount_amount' => ($request->total_discount_amount ?? 0) + $discount,
                'loyalty_discount_applied_at' => now(),
            ]);

            // Redeem points (will be finalized after payment)
            $loyaltyPoint->redeemPoints(
                $points,
                'discount_applied',
                "Points redeemed for {$request->project_name}",
                $request
            );

            DB::commit();

            // Recalculate payment amounts after discount is applied (outside transaction)
            // This ensures milestones/downpayment reflect the discounted budget
            MilestoneService::recalculatePaymentAmountsForRequest($request);

            Log::info('Loyalty discount applied to service request', [
                'user_id' => $user->id,
                'service_request_id' => $request->id,
                'points' => $points,
                'discount' => $discount,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to apply loyalty discount', [
                'error' => $e->getMessage(),
                'service_request_id' => $request->id,
                'points' => $points,
            ]);
            throw $e;
        }
    }

    /**
     * Refund loyalty points if payment fails
     * 
     * @param ServiceRequest $request
     * @return void
     */
    public function refundLoyaltyPoints(ServiceRequest $request): void
    {
        if ($request->loyalty_points_used > 0) {
            try {
                $user = $request->client;
                $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

                $loyaltyPoint->refundPoints(
                    $request->loyalty_points_used,
                    'payment_failed',
                    "Points refunded due to failed payment for {$request->project_name}",
                    $request
                );

                Log::info('Loyalty points refunded', [
                    'user_id' => $user->id,
                    'service_request_id' => $request->id,
                    'points' => $request->loyalty_points_used,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to refund loyalty points', [
                    'error' => $e->getMessage(),
                    'service_request_id' => $request->id,
                ]);
            }
        }
    }

    /**
     * Get tier benefits for a specific tier (cached)
     * 
     * @param string $tier
     * @return array
     */
    public function getTierBenefits(string $tier): array
    {
        return Cache::remember(
            self::CACHE_PREFIX . "tier_benefits:{$tier}",
            self::CACHE_TTL_TIERS,
            function () use ($tier) {
                return $this->getTierBenefitsData($tier);
            }
        );
    }

    /**
     * Get tier benefits data (uncached)
     */
    protected function getTierBenefitsData(string $tier): array
    {
        $benefits = [
            'bronze' => [
                'earning_rate' => '1%',
                'discount' => '0%',
                'benefits' => [
                    'Basic support',
                    'Standard processing',
                    'Access to basic coupons',
                ],
            ],
            'silver' => [
                'earning_rate' => '2%',
                'discount' => '5%',
                'benefits' => [
                    '2% points earning rate',
                    '5% discount on all services',
                    'Priority support (response within 24h)',
                    'Early access to new services',
                ],
            ],
            'gold' => [
                'earning_rate' => '3%',
                'discount' => '10%',
                'benefits' => [
                    '3% points earning rate',
                    '10% discount on all services',
                    'Priority support (response within 12h)',
                    'Free minor revisions (1 per project)',
                    'Birthday month special coupon',
                ],
            ],
            'platinum' => [
                'earning_rate' => '5%',
                'discount' => '15%',
                'benefits' => [
                    '5% points earning rate',
                    '15% discount on all services',
                    'VIP support (response within 6h)',
                    'Free minor revisions (2 per project)',
                    'Quarterly exclusive coupons',
                    'Dedicated account manager',
                    'Free consultation sessions',
                ],
            ],
        ];

        return $benefits[$tier] ?? $benefits['bronze'];
    }

    /**
     * Get all tiers with their requirements (cached)
     * 
     * @return array
     */
    public function getAllTiers(): array
    {
        return Cache::remember(
            self::CACHE_PREFIX . 'all_tiers',
            self::CACHE_TTL_TIERS,
            function () {
                return config('loyalty.tiers', [
                    'bronze' => ['points' => 0, 'discount' => 0],
                    'silver' => ['points' => 5000, 'discount' => 5],
                    'gold' => ['points' => 15000, 'discount' => 10],
                    'platinum' => ['points' => 50000, 'discount' => 15],
                ]);
            }
        );
    }

    /**
     * Check if user qualifies for tier upgrade and upgrade if eligible
     * 
     * @param User $user
     * @return bool True if upgraded
     */
    public function checkAndUpgradeTier(User $user): bool
    {
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        return $loyaltyPoint->checkAndUpgradeTier();
    }

    /**
     * Manually adjust user's loyalty points (admin only)
     * 
     * @param User $user
     * @param int $points Can be positive (add) or negative (deduct)
     * @param string $reason
     * @param User $adjustedBy
     * @return void
     */
    public function adjustPoints(User $user, int $points, string $reason, User $adjustedBy): void
    {
        try {
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
            $loyaltyPoint->adjustPoints($points, $reason, $adjustedBy);

            // Clear caches after point adjustment
            $this->clearUserCache($user);
            $this->clearGlobalCache();

            Log::info('Loyalty points manually adjusted', [
                'user_id' => $user->id,
                'points' => $points,
                'reason' => $reason,
                'adjusted_by' => $adjustedBy->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to adjust loyalty points', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'points' => $points,
            ]);
            throw $e;
        }
    }

    /**
     * Get loyalty statistics for a user (cached for short period)
     * 
     * @param User $user
     * @return array
     */
    public function getUserStatistics(User $user): array
    {
        return Cache::remember(
            self::CACHE_PREFIX . "user_stats:{$user->id}",
            self::CACHE_TTL_USER,
            function () use ($user) {
                return $this->calculateUserStatistics($user);
            }
        );
    }

    /**
     * Calculate user statistics (uncached)
     */
    protected function calculateUserStatistics(User $user): array
    {
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $tiers = $this->getAllTiers();
        $currentTierData = $tiers[$loyaltyPoint->tier] ?? $tiers['bronze'];
        
        // Find next tier
        $nextTier = null;
        $pointsToNextTier = null;
        foreach ($tiers as $tierName => $tierData) {
            if ($tierData['points'] > $loyaltyPoint->lifetime_earned) {
                $nextTier = $tierName;
                $pointsToNextTier = $tierData['points'] - $loyaltyPoint->lifetime_earned;
                break;
            }
        }

        return [
            'current_tier' => $loyaltyPoint->tier,
            'available_points' => $loyaltyPoint->available_points,
            'lifetime_earned' => $loyaltyPoint->lifetime_earned,
            'lifetime_redeemed' => $loyaltyPoint->lifetime_redeemed,
            'next_tier' => $nextTier,
            'points_to_next_tier' => $pointsToNextTier,
            'tier_benefits' => $this->getTierBenefits($loyaltyPoint->tier),
            'earning_rate' => $loyaltyPoint->getEarningRate() . '%',
            'expiring_soon' => $user->loyaltyTransactions()
                ->earned()
                ->where('expires_at', '<=', now()->addDays(30))
                ->where('expires_at', '>', now())
                ->sum('points'),
        ];
    }

    /**
     * Get global loyalty system statistics (cached)
     * 
     * @return array
     */
    public function getGlobalStatistics(): array
    {
        return Cache::remember(
            self::CACHE_PREFIX . 'global_stats',
            self::CACHE_TTL_STATS,
            function () {
                return $this->calculateGlobalStatistics();
            }
        );
    }

    /**
     * Calculate global statistics (uncached)
     */
    protected function calculateGlobalStatistics(): array
    {
        $totalMembers = LoyaltyPoint::count();
        
        return [
            'total_members' => $totalMembers,
            'total_points_circulation' => LoyaltyPoint::sum('available_points'),
            'total_points_earned' => LoyaltyPoint::sum('lifetime_earned'),
            'total_points_redeemed' => LoyaltyPoint::sum('lifetime_redeemed'),
            'average_points_per_user' => $totalMembers > 0 
                ? LoyaltyPoint::avg('available_points') 
                : 0,
            'tier_distribution' => [
                'bronze' => LoyaltyPoint::where('tier', 'bronze')->count(),
                'silver' => LoyaltyPoint::where('tier', 'silver')->count(),
                'gold' => LoyaltyPoint::where('tier', 'gold')->count(),
                'platinum' => LoyaltyPoint::where('tier', 'platinum')->count(),
            ],
            'transactions_this_month' => LoyaltyTransaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    /**
     * Expire old loyalty points (run via scheduled task)
     * 
     * @return int Number of points expired
     */
    public function expireOldPoints(): int
    {
        $expiredTransactions = LoyaltyTransaction::where('transaction_type', 'earned')
            ->where('expires_at', '<', now())
            ->whereNull('expired_at')
            ->get();

        $totalPointsExpired = 0;

        foreach ($expiredTransactions as $transaction) {
            try {
                DB::beginTransaction();

                $user = $transaction->user;
                $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

                // Deduct expired points using the proper expiration method
                $loyaltyPoint->expirePoints(
                    $transaction->points,
                    "Points expired from transaction #{$transaction->id}"
                );

                // Mark transaction as expired
                $transaction->update(['expired_at' => now()]);

                $totalPointsExpired += $transaction->points;

                DB::commit();

                Log::info('Loyalty points expired', [
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id,
                    'points' => $transaction->points,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to expire loyalty points', [
                    'error' => $e->getMessage(),
                    'transaction_id' => $transaction->id,
                ]);
            }
        }

        return $totalPointsExpired;
    }

    /**
     * Clear user-specific loyalty cache
     * 
     * @param User $user
     * @return void
     */
    public function clearUserCache(User $user): void
    {
        Cache::forget(self::CACHE_PREFIX . "user_stats:{$user->id}");
    }

    /**
     * Clear global statistics cache
     * 
     * @return void
     */
    public function clearGlobalCache(): void
    {
        Cache::forget(self::CACHE_PREFIX . 'global_stats');
    }

    /**
     * Clear all tier-related caches
     * 
     * @return void
     */
    public function clearTierCache(): void
    {
        Cache::forget(self::CACHE_PREFIX . 'all_tiers');
        foreach (['bronze', 'silver', 'gold', 'platinum'] as $tier) {
            Cache::forget(self::CACHE_PREFIX . "tier_benefits:{$tier}");
        }
    }

    /**
     * Clear all loyalty caches
     * 
     * @return void
     */
    public function clearAllCache(): void
    {
        $this->clearTierCache();
        $this->clearGlobalCache();
        // User caches will expire naturally (1 minute TTL)
    }
}
