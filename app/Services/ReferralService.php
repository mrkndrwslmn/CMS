<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\Coupon;
use App\Models\Payment;
use App\Mail\ReferralSignupMail;
use App\Mail\ReferralCompletedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReferralService
{
    protected CouponService $couponService;
    protected LoyaltyService $loyaltyService;

    public function __construct(
        CouponService $couponService,
        LoyaltyService $loyaltyService
    ) {
        $this->couponService = $couponService;
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Process referral during user registration
     * 
     * @param User $newUser
     * @param string|null $referralCode
     * @param array $metadata (ip_address, user_agent, etc.)
     * @return Referral|null
     */
    public function processRegistrationReferral(
        User $newUser,
        ?string $referralCode,
        array $metadata = []
    ): ?Referral {
        if (empty($referralCode)) {
            return null;
        }

        try {
            DB::beginTransaction();

            // Find referrer by code
            $referrerCode = ReferralCode::where('code', $referralCode)
                ->where('is_active', true)
                ->first();

            if (!$referrerCode) {
                Log::warning('Invalid referral code used', [
                    'code' => $referralCode,
                    'new_user_id' => $newUser->id,
                ]);
                DB::rollBack();
                return null;
            }

            $referrer = $referrerCode->user;

            // Prevent self-referral
            if ($referrer->id === $newUser->id) {
                Log::warning('Self-referral attempted', [
                    'user_id' => $newUser->id,
                ]);
                DB::rollBack();
                return null;
            }

            // Check if already referred
            if ($newUser->isReferred()) {
                Log::warning('User already referred', [
                    'user_id' => $newUser->id,
                    'previous_referrer' => $newUser->referred_by_user_id,
                ]);
                DB::rollBack();
                return null;
            }

            // Calculate rewards
            $referrerPendingPoints = config('referral.legacy_rewards.referrer.completion_points', 1000);
            $referredWelcomePoints = config('referral.legacy_rewards.referred.welcome_points', 500);

            // Create referral record
            $referral = Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $newUser->id,
                'referral_code' => $referralCode,
                'status' => 'pending',
                'referrer_points_pending' => $referrerPendingPoints,
                'referred_points_earned' => $referredWelcomePoints,
                'ip_address' => $metadata['ip_address'] ?? null,
                'user_agent' => $metadata['user_agent'] ?? null,
                'metadata' => $metadata,
            ]);

            // Update new user record with referral info
            $updated = $newUser->update([
                'referred_by_user_id' => $referrer->id,
                'referred_by_code' => $referralCode,
                'referral_registered_at' => now(),
            ]);
            
            if (!$updated) {
                Log::error('Failed to update user with referral info', [
                    'user_id' => $newUser->id,
                    'referrer_id' => $referrer->id,
                    'referral_code' => $referralCode,
                ]);
            } else {
                // Refresh the model to ensure changes are reflected
                $newUser->refresh();
                Log::info('User updated with referral info', [
                    'user_id' => $newUser->id,
                    'referred_by_user_id' => $newUser->referred_by_user_id,
                ]);
            }

            // Update referral code stats
            $referrerCode->incrementReferral('pending');

            // Award welcome bonus points to new user
            $this->awardWelcomeBonus($newUser, $referredWelcomePoints);

            // Generate welcome coupon for new user
            $welcomeCoupon = $this->generateWelcomeCoupon($newUser);
            if ($welcomeCoupon) {
                $referral->update(['referred_coupon_id' => $welcomeCoupon->id]);
            }

            DB::commit();

            // Send email notifications
            $this->sendReferralSignupNotifications($referrer, $newUser, $referral);

            Log::info('Referral processed successfully', [
                'referrer_id' => $referrer->id,
                'referred_id' => $newUser->id,
                'referral_id' => $referral->id,
            ]);

            return $referral;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process registration referral', [
                'error' => $e->getMessage(),
                'new_user_id' => $newUser->id,
                'referral_code' => $referralCode,
            ]);
            return null;
        }
    }

    /**
     * Process referral completion after first payment
     * 
     * @param Payment $payment
     * @return bool
     */
    public function processReferralCompletion(Payment $payment): bool
    {
        try {
            $user = $payment->client;
            
            Log::info('Starting referral completion process', [
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'payment_amount' => $payment->amount,
            ]);

            // Check if user was referred
            if (!$user->isReferred()) {
                Log::info('User was not referred, skipping referral processing', [
                    'user_id' => $user->id,
                ]);
                return false;
            }
            
            Log::info('User was referred, checking for referral record', [
                'user_id' => $user->id,
            ]);

            // Find the referral record
            $referral = Referral::where('referred_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$referral) {
                Log::warning('No pending referral found for user', [
                    'user_id' => $user->id,
                    'all_referrals' => Referral::where('referred_id', $user->id)->get()->toArray(),
                ]);
                return false;
            }
            
            Log::info('Found pending referral', [
                'referral_id' => $referral->id,
                'referrer_id' => $referral->referrer_id,
            ]);

            DB::beginTransaction();

            // Check if payment amount qualifies for rewards
            $minQualifyingAmount = config('referral.eligibility.min_qualifying_amount', 100000);
            if ($payment->amount < $minQualifyingAmount) {
                Log::info('Payment amount below minimum for referral rewards', [
                    'payment_amount' => $payment->amount,
                    'min_amount' => $minQualifyingAmount,
                ]);
                DB::rollBack();
                return false;
            }
            
            Log::info('Payment qualifies for referral rewards', [
                'payment_amount' => $payment->amount,
                'min_amount' => $minQualifyingAmount,
            ]);

            // Mark referral as completed
            $referral->markCompleted($payment);
            $referral->update(['qualifying_payment_amount' => $payment->amount]);

            // Calculate and award tiered benefits
            $this->awardTieredBenefits($referral, $payment->amount);

            // Update referral code stats
            $referrerCode = $referral->referrer->referralCode;
            if ($referrerCode) {
                $referrerCode->incrementReferral('completed');
                $referrerCode->addEarnings($referral->referrer_points_earned);
            }

            // Mark as rewarded
            $referral->markRewarded();

            DB::commit();

            // Send completion notifications
            $this->sendReferralCompletionNotifications($referral);

            Log::info('Referral completed successfully', [
                'referral_id' => $referral->id,
                'payment_id' => $payment->id,
                'payment_amount' => $payment->amount,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process referral completion', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
            return false;
        }
    }

    /**
     * Award tiered benefits based on payment amount
     */
    protected function awardTieredBenefits(Referral $referral, float $paymentAmount): void
    {
        $tier = $this->getRewardTier($paymentAmount);
        
        if (!$tier) {
            Log::warning('No reward tier found for payment amount', [
                'payment_amount' => $paymentAmount,
            ]);
            return;
        }

        // Award benefits to referrer
        $this->awardBenefitToReferrer($referral, $paymentAmount, $tier);
        
        // Award benefits to referred user
        $this->awardBenefitToReferred($referral, $paymentAmount, $tier);
    }

    /**
     * Get the appropriate reward tier for a payment amount
     */
    protected function getRewardTier(float $amount): ?array
    {
        $tiers = config('referral.reward_tiers', []);
        
        foreach ($tiers as $tier) {
            if ($amount >= $tier['min_amount']) {
                if ($tier['max_amount'] === null || $amount <= $tier['max_amount']) {
                    return $tier;
                }
            }
        }
        
        return null;
    }

    /**
     * Award benefit to referrer (person who referred)
     * ALWAYS awards CREDITS (withdrawable money)
     */
    protected function awardBenefitToReferrer(Referral $referral, float $paymentAmount, array $tier): void
    {
        $percentage = $tier['referrer_percentage'];
        $benefitAmount = ($paymentAmount * $percentage) / 100;

        $referral->update([
            'referrer_benefit_type' => 'credits',
            'referrer_discount_percentage' => $percentage,
        ]);

        // Award withdrawable credits (ALWAYS)
        $this->awardReferralCredits(
            $referral->referrer,
            $benefitAmount,
            $referral,
            "Referral reward: {$percentage}% of ₱" . number_format($paymentAmount, 2)
        );
        
        $referral->update(['referrer_credits_earned' => $benefitAmount]);

        // Also award legacy points for compatibility
        $this->awardReferrerCompletionReward($referral);
    }

    /**
     * Award benefit to referred user (person who was referred)
     * ALWAYS awards COUPON (discount percentage)
     */
    protected function awardBenefitToReferred(Referral $referral, float $paymentAmount, array $tier): void
    {
        $percentage = $tier['referred_percentage'];

        $referral->update([
            'referred_benefit_type' => 'coupon',
            'referred_discount_percentage' => $percentage,
        ]);

        // Generate coupon (ALWAYS)
        // The percentage is now the coupon discount percentage (not payment percentage)
        $coupon = $this->generateReferredTieredCoupon($referral->referred, $percentage, $paymentAmount);
        if ($coupon) {
            $referral->update(['referred_coupon_id' => $coupon->id]);
        }
    }

    /**
     * Award referral credits to a user
     */
    protected function awardReferralCredits(
        User $user,
        float $amount,
        Referral $referral,
        string $description
    ): void {
        $balanceBefore = $user->referral_credits;
        $balanceAfter = $balanceBefore + $amount;

        // Update user balance
        $user->increment('referral_credits', $amount);

        // Log transaction
        \App\Models\ReferralCreditTransaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'earned',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'source' => 'referral_completed',
            'description' => $description,
            'referral_id' => $referral->id,
        ]);

        Log::info('Referral credits awarded', [
            'user_id' => $user->id,
            'amount' => $amount,
            'balance_after' => $balanceAfter,
        ]);
    }

    /**
     * Generate tiered coupon for referrer
     */
    protected function generateReferrerTieredCoupon(User $referrer, float $percentage, float $maxDiscount): ?Coupon
    {
        try {
            $validDays = config('referral.benefits.coupon.validity_days', 60);
            $minPurchase = config('referral.benefits.coupon.min_purchase_amount', 10000);

            $couponData = [
                'code' => 'REF' . strtoupper(substr($referrer->fullName, 0, 4)) . rand(1000, 9999),
                'name' => 'Referral Reward - ' . $percentage . '%',
                'description' => "Thank you for referring! Get {$percentage}% off your next service (up to ₱" . number_format($maxDiscount, 0) . ")",
                'discount_type' => 'percentage',
                'discount_value' => $percentage,
                'max_discount_amount' => $maxDiscount,
                'min_purchase_amount' => $minPurchase,
                'valid_until' => now()->addDays($validDays),
                'stackable_with_loyalty_tier' => config('referral.benefits.coupon.stackable_with_loyalty', true),
                'stackable_with_points' => false,
            ];

            return $this->couponService->createUserSpecificCoupon(
                $couponData,
                $referrer,
                $referrer
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate referrer tiered coupon', [
                'error' => $e->getMessage(),
                'referrer_id' => $referrer->id,
            ]);
            return null;
        }
    }

    /**
     * Generate tiered coupon for referred user
     */
    protected function generateReferredTieredCoupon(User $referred, float $percentage, float $qualifyingAmount): ?Coupon
    {
        try {
            $validDays = config('referral.benefits.coupon.validity_days', 90);
            $minPurchase = config('referral.benefits.coupon.min_purchase_amount', 50000);
            $maxDiscount = config('referral.benefits.coupon.max_discount_amount', 30000);

            $couponData = [
                'code' => 'WELCOME' . strtoupper(substr($referred->fullName, 0, 4)) . rand(1000, 9999),
                'name' => 'Referral Welcome - ' . $percentage . '% Off',
                'description' => "Thank you for joining! Get {$percentage}% off your next project (up to ₱" . number_format($maxDiscount, 0) . ")",
                'discount_type' => 'percentage',
                'discount_value' => $percentage,
                'max_discount_amount' => $maxDiscount,
                'min_purchase_amount' => $minPurchase,
                'valid_until' => now()->addDays($validDays),
                'stackable_with_loyalty_tier' => config('referral.benefits.coupon.stackable_with_loyalty', false),
                'stackable_with_points' => false,
            ];

            return $this->couponService->createUserSpecificCoupon(
                $couponData,
                $referred,
                $referred
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate referred tiered coupon', [
                'error' => $e->getMessage(),
                'referred_id' => $referred->id,
            ]);
            return null;
        }
    }

    /**
     * Award welcome bonus to new referred user
     */
    protected function awardWelcomeBonus(User $user, int $points): void
    {
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $loyaltyPoint->earnPoints(
            $points,
            'referral_welcome',
            "Welcome bonus for joining via referral",
            null
        );
    }

    /**
     * Award completion reward to referrer
     */
    protected function awardReferrerCompletionReward(Referral $referral): void
    {
        $loyaltyPoint = $referral->referrer->getOrCreateLoyaltyPoints();
        $points = $referral->referrer_points_pending;

        $loyaltyPoint->earnPoints(
            $points,
            'referral_completion',
            "Referral bonus for {$referral->referred->fullName}'s first payment",
            $referral
        );

        $referral->update(['referrer_points_earned' => $points]);
    }

    /**
     * Generate welcome coupon for new user
     */
    protected function generateWelcomeCoupon(User $user): ?Coupon
    {
        try {
            $discountValue = config('referral.legacy_rewards.referred.coupon_discount', 15);
            $validDays = config('referral.legacy_rewards.referred.coupon_validity_days', 30);

            $couponData = [
                'code' => 'WELCOME' . strtoupper(substr($user->fullName, 0, 4)) . rand(100, 999),
                'name' => 'Welcome Referral Discount',
                'description' => 'Special discount for joining via referral!',
                'discount_type' => 'percentage',
                'discount_value' => $discountValue,
                'min_purchase_amount' => 1000,
                'valid_until' => now()->addDays($validDays),
                'stackable_with_loyalty_tier' => true,
                'stackable_with_points' => true,
            ];

            return $this->couponService->createUserSpecificCoupon(
                $couponData,
                $user,
                $user // Created by the user themselves (auto-generated)
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate welcome coupon', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            return null;
        }
    }

    /**
     * Generate reward coupon for referrer
     */
    protected function generateReferrerRewardCoupon(User $referrer): ?Coupon
    {
        try {
            $discountValue = config('referral.legacy_rewards.referrer.coupon_discount', 20);
            $validDays = config('referral.legacy_rewards.referrer.coupon_validity_days', 60);

            $couponData = [
                'code' => 'REFERRAL' . strtoupper(substr($referrer->fullName, 0, 4)) . rand(100, 999),
                'name' => 'Referral Reward Discount',
                'description' => 'Thank you for referring new clients!',
                'discount_type' => 'percentage',
                'discount_value' => $discountValue,
                'min_purchase_amount' => 2000,
                'valid_until' => now()->addDays($validDays),
                'stackable_with_loyalty_tier' => true,
                'stackable_with_points' => false, // Higher value, exclusive
            ];

            return $this->couponService->createUserSpecificCoupon(
                $couponData,
                $referrer,
                $referrer // Auto-generated
            );
        } catch (\Exception $e) {
            Log::error('Failed to generate referrer reward coupon', [
                'error' => $e->getMessage(),
                'referrer_id' => $referrer->id,
            ]);
            return null;
        }
    }

    /**
     * Get referral statistics for a user
     */
    public function getReferralStats(User $user): array
    {
        $referralCode = $user->referralCode;
        
        if (!$referralCode) {
            return [
                'code' => null,
                'total_referrals' => 0,
                'successful_referrals' => 0,
                'pending_referrals' => 0,
                'lifetime_earnings' => 0,
                'conversion_rate' => 0,
                'referral_credits' => $user->referral_credits ?? 0,
                'referral_credits_pending' => $user->referral_credits_pending ?? 0,
                'referral_credits_withdrawn' => $user->referral_credits_withdrawn ?? 0,
            ];
        }

        return [
            'code' => $referralCode->code,
            'total_referrals' => $referralCode->total_referrals,
            'successful_referrals' => $referralCode->successful_referrals,
            'pending_referrals' => $referralCode->pending_referrals,
            'lifetime_earnings' => $referralCode->lifetime_earnings_points,
            'conversion_rate' => $referralCode->getConversionRate(),
            'last_used' => $referralCode->last_used_at,
            'referral_credits' => $user->referral_credits ?? 0,
            'referral_credits_pending' => $user->referral_credits_pending ?? 0,
            'referral_credits_withdrawn' => $user->referral_credits_withdrawn ?? 0,
        ];
    }

    /**
     * Request withdrawal of referral credits
     */
    public function requestWithdrawal(
        User $user,
        float $amount,
        string $withdrawalMethod,
        array $withdrawalDetails,
        ?string $notes = null
    ): ?\App\Models\ReferralCreditWithdrawal {
        $minWithdrawal = config('referral.benefits.credits.minimum_withdrawal', 1000);
        
        if ($amount < $minWithdrawal) {
            throw new \Exception("Minimum withdrawal amount is ₱" . number_format($minWithdrawal, 2));
        }
        
        if ($user->referral_credits < $amount) {
            throw new \Exception("Insufficient referral credits. Available: ₱" . number_format($user->referral_credits, 2));
        }

        try {
            DB::beginTransaction();

            // Create withdrawal request
            $withdrawal = \App\Models\ReferralCreditWithdrawal::create([
                'withdrawal_number' => \App\Models\ReferralCreditWithdrawal::generateWithdrawalNumber(),
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'pending',
                'withdrawal_method' => $withdrawalMethod,
                'withdrawal_details' => $withdrawalDetails,
                'user_notes' => $notes,
                'requested_at' => now(),
            ]);

            // Move credits from available to pending
            $user->decrement('referral_credits', $amount);
            $user->increment('referral_credits_pending', $amount);

            // Log transaction
            $balanceBefore = $user->referral_credits + $amount;
            \App\Models\ReferralCreditTransaction::create([
                'user_id' => $user->id,
                'transaction_type' => 'withdrawn',
                'amount' => -$amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $user->referral_credits,
                'source' => 'withdrawal_requested',
                'description' => "Withdrawal request #{$withdrawal->withdrawal_number}",
                'withdrawal_id' => $withdrawal->id,
            ]);

            DB::commit();

            Log::info('Referral credit withdrawal requested', [
                'user_id' => $user->id,
                'withdrawal_id' => $withdrawal->id,
                'amount' => $amount,
            ]);

            return $withdrawal;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to request withdrawal', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'amount' => $amount,
            ]);
            throw $e;
        }
    }

    /**
     * Approve and complete a withdrawal
     */
    public function completeWithdrawal(
        \App\Models\ReferralCreditWithdrawal $withdrawal,
        string $referenceNumber,
        ?string $proofPath = null,
        ?string $notes = null,
        User $admin = null
    ): bool {
        try {
            DB::beginTransaction();

            $withdrawal->markCompleted($referenceNumber, $proofPath, $notes);

            $user = $withdrawal->user;

            // Move from pending to withdrawn
            $user->decrement('referral_credits_pending', $withdrawal->amount);
            $user->increment('referral_credits_withdrawn', $withdrawal->amount);

            DB::commit();

            Log::info('Referral credit withdrawal completed', [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'amount' => $withdrawal->amount,
                'processed_by' => $admin?->id,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to complete withdrawal', [
                'error' => $e->getMessage(),
                'withdrawal_id' => $withdrawal->id,
            ]);
            return false;
        }
    }

    /**
     * Reject a withdrawal and refund credits
     */
    public function rejectWithdrawal(
        \App\Models\ReferralCreditWithdrawal $withdrawal,
        string $reason,
        User $admin = null
    ): bool {
        try {
            DB::beginTransaction();

            $withdrawal->markRejected($reason);

            $user = $withdrawal->user;

            // Return credits from pending to available
            $user->increment('referral_credits', $withdrawal->amount);
            $user->decrement('referral_credits_pending', $withdrawal->amount);

            // Log refund transaction
            $balanceBefore = $user->referral_credits - $withdrawal->amount;
            \App\Models\ReferralCreditTransaction::create([
                'user_id' => $user->id,
                'transaction_type' => 'refunded',
                'amount' => $withdrawal->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $user->referral_credits,
                'source' => 'withdrawal_rejected',
                'description' => "Withdrawal #{$withdrawal->withdrawal_number} rejected: {$reason}",
                'withdrawal_id' => $withdrawal->id,
                'performed_by' => $admin?->id,
            ]);

            DB::commit();

            Log::info('Referral credit withdrawal rejected', [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'amount' => $withdrawal->amount,
                'processed_by' => $admin?->id,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject withdrawal', [
                'error' => $e->getMessage(),
                'withdrawal_id' => $withdrawal->id,
            ]);
            return false;
        }
    }

    /**
     * Validate referral code
     */
    public function validateReferralCode(string $code): array
    {
        $referralCode = ReferralCode::where('code', $code)
            ->where('is_active', true)
            ->with('user')
            ->first();

        if (!$referralCode) {
            return [
                'valid' => false,
                'message' => 'Invalid or inactive referral code.',
            ];
        }

        return [
            'valid' => true,
            'referrer_name' => $referralCode->user->fullName,
            'message' => 'Valid referral code!',
        ];
    }

    /**
     * Send signup notifications
     */
    protected function sendReferralSignupNotifications(
        User $referrer,
        User $referred,
        Referral $referral
    ): void {
        try {
            // Email to referrer
            Mail::to($referrer->email)
                ->queue(new ReferralSignupMail($referrer, $referred, $referral));

            // Email to referred user (welcome)
            // Handled by registration flow
        } catch (\Exception $e) {
            Log::error('Failed to send referral signup notifications', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send completion notifications
     */
    protected function sendReferralCompletionNotifications(Referral $referral): void
    {
        try {
            // Email to referrer
            Mail::to($referral->referrer->email)
                ->queue(new ReferralCompletedMail($referral));
        } catch (\Exception $e) {
            Log::error('Failed to send referral completion notifications', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
