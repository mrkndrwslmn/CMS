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
            $referrerPendingPoints = config('referral.rewards.referrer.completion_points', 1000);
            $referredWelcomePoints = config('referral.rewards.referred.welcome_points', 500);

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

            // Update new user record
            $newUser->update([
                'referred_by_user_id' => $referrer->id,
                'referred_by_code' => $referralCode,
                'referral_registered_at' => now(),
            ]);

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

            // Check if user was referred
            if (!$user->isReferred()) {
                return false;
            }

            // Find the referral record
            $referral = Referral::where('referred_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$referral) {
                return false;
            }

            DB::beginTransaction();

            // Mark referral as completed
            $referral->markCompleted($payment);

            // Award points to referrer
            $this->awardReferrerCompletionReward($referral);

            // Generate reward coupon for referrer
            $referrerCoupon = $this->generateReferrerRewardCoupon($referral->referrer);
            if ($referrerCoupon) {
                $referral->update(['referrer_coupon_id' => $referrerCoupon->id]);
            }

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
            $discountValue = config('referral.rewards.referred.coupon_discount', 15);
            $validDays = config('referral.rewards.referred.coupon_validity_days', 30);

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
            $discountValue = config('referral.rewards.referrer.coupon_discount', 20);
            $validDays = config('referral.rewards.referrer.coupon_validity_days', 60);

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
        ];
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
