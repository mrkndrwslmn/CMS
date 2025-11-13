<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class CouponService
{
    /**
     * Validate a coupon code for a specific user and amount
     * 
     * @param string $code Coupon code to validate
     * @param User $user User attempting to use the coupon
     * @param float $amount Purchase amount
     * @return array ['valid' => bool, 'coupon' => ?Coupon, 'discount' => float, 'message' => string]
     */
    public function validateCoupon(string $code, User $user, float $amount): array
    {
        // Find the coupon
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'coupon' => null,
                'discount' => 0,
                'message' => 'Invalid coupon code.',
            ];
        }

        // Check if coupon is valid
        if (!$coupon->isValid()) {
            if ($coupon->status === 'expired') {
                return [
                    'valid' => false,
                    'coupon' => $coupon,
                    'discount' => 0,
                    'message' => 'This coupon has expired.',
                ];
            }

            if ($coupon->status === 'inactive') {
                return [
                    'valid' => false,
                    'coupon' => $coupon,
                    'discount' => 0,
                    'message' => 'This coupon is currently inactive.',
                ];
            }

            if (!$coupon->hasUsageLeft()) {
                return [
                    'valid' => false,
                    'coupon' => $coupon,
                    'discount' => 0,
                    'message' => 'This coupon has reached its usage limit.',
                ];
            }

            return [
                'valid' => false,
                'coupon' => $coupon,
                'discount' => 0,
                'message' => 'This coupon is not currently valid.',
            ];
        }

        // Check if user can use this coupon
        if (!$coupon->canBeUsedBy($user)) {
            // Check specific reasons
            if ($coupon->coupon_type === 'user_specific' && $coupon->specific_user_id !== $user->id) {
                return [
                    'valid' => false,
                    'coupon' => $coupon,
                    'discount' => 0,
                    'message' => 'This coupon is not available for your account.',
                ];
            }

            if ($coupon->coupon_type === 'request_specific') {
                return [
                    'valid' => false,
                    'coupon' => $coupon,
                    'discount' => 0,
                    'message' => 'This coupon is automatically applied and cannot be manually used.',
                ];
            }

            // Check per-user limit
            $userUsageCount = $coupon->usages()
                ->where('user_id', $user->id)
                ->where('payment_status', '!=', 'failed')
                ->count();

            if ($userUsageCount >= $coupon->max_uses_per_user) {
                return [
                    'valid' => false,
                    'coupon' => $coupon,
                    'discount' => 0,
                    'message' => 'You have already used this coupon the maximum number of times.',
                ];
            }

            return [
                'valid' => false,
                'coupon' => $coupon,
                'discount' => 0,
                'message' => 'You are not eligible to use this coupon.',
            ];
        }

        // Check minimum purchase amount
        if ($amount < $coupon->min_purchase_amount) {
            return [
                'valid' => false,
                'coupon' => $coupon,
                'discount' => 0,
                'message' => sprintf(
                    'Minimum purchase amount of ₱%s required to use this coupon.',
                    number_format($coupon->min_purchase_amount, 0)
                ),
            ];
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($amount);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'final_amount' => $amount - $discount,
            'message' => sprintf(
                'Coupon applied! You save ₱%s (%s)',
                number_format($discount, 0),
                $coupon->getDiscountLabel()
            ),
        ];
    }

    /**
     * Apply a coupon to a service request
     * 
     * @param ServiceRequest $request
     * @param Coupon $coupon
     * @param bool $autoApplied Whether coupon was auto-applied by admin
     * @return bool
     */
    public function applyCouponToRequest(ServiceRequest $request, Coupon $coupon, bool $autoApplied = false): bool
    {
        try {
            DB::beginTransaction();

            // Remove existing coupon if any
            if ($request->applied_coupon_id) {
                $this->removeCouponFromRequest($request);
            }

            // Validate coupon can be applied
            $validation = $this->validateCoupon(
                $coupon->code,
                $request->client,
                $request->approved_budget
            );

            if (!$validation['valid'] && !$autoApplied) {
                // Allow admin to force-apply even if validation fails
                throw new \Exception($validation['message']);
            }

            // Store original budget if not already stored
            if (!$request->original_approved_budget) {
                $request->original_approved_budget = $request->approved_budget;
            }

            // Calculate discount
            $discount = $coupon->calculateDiscount($request->approved_budget);

            // Apply coupon to request
            $request->update([
                'applied_coupon_id' => $coupon->id,
                'coupon_discount_amount' => $discount,
                'coupon_applied_at' => now(),
                'coupon_auto_applied' => $autoApplied,
                'approved_budget' => $request->approved_budget - $discount, // Reduce budget by discount
                'total_discount_amount' => ($request->total_discount_amount ?? 0) + $discount,
            ]);

            // Create usage record
            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $request->client_id,
                'service_request_id' => $request->id,
                'original_amount' => $request->original_approved_budget,
                'discount_amount' => $discount,
                'final_amount' => $request->approved_budget,
                'payment_status' => 'pending',
                'used_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Increment coupon usage
            $coupon->incrementUsage();

            DB::commit();

            Log::info('Coupon applied to service request', [
                'coupon_id' => $coupon->id,
                'coupon_code' => $coupon->code,
                'service_request_id' => $request->id,
                'discount' => $discount,
                'auto_applied' => $autoApplied,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to apply coupon to service request', [
                'error' => $e->getMessage(),
                'coupon_id' => $coupon->id,
                'service_request_id' => $request->id,
            ]);
            throw $e;
        }
    }

    /**
     * Remove coupon from a service request
     * 
     * @param ServiceRequest $request
     * @return bool
     */
    public function removeCouponFromRequest(ServiceRequest $request): bool
    {
        if (!$request->applied_coupon_id) {
            return false;
        }

        try {
            DB::beginTransaction();

            $coupon = $request->appliedCoupon;
            $discount = $request->coupon_discount_amount;

            // Restore original budget
            $request->update([
                'approved_budget' => $request->approved_budget + $discount,
                'applied_coupon_id' => null,
                'coupon_discount_amount' => 0,
                'coupon_applied_at' => null,
                'coupon_auto_applied' => false,
                'total_discount_amount' => max(0, ($request->total_discount_amount ?? 0) - $discount),
            ]);

            // Update usage record
            $usage = $request->couponUsage;
            if ($usage) {
                $usage->update(['payment_status' => 'cancelled']);
            }

            // Decrement coupon usage
            if ($coupon) {
                $coupon->decrementUsage();
            }

            DB::commit();

            Log::info('Coupon removed from service request', [
                'coupon_id' => $coupon->id ?? null,
                'service_request_id' => $request->id,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to remove coupon from service request', [
                'error' => $e->getMessage(),
                'service_request_id' => $request->id,
            ]);
            throw $e;
        }
    }

    /**
     * Get available coupons for a user
     * 
     * @param User $user
     * @return Collection
     */
    public function getAvailableCouponsForUser(User $user): Collection
    {
        return Coupon::getAvailableForUser($user);
    }

    /**
     * Auto-assign coupon to request (used during approval)
     * 
     * @param ServiceRequest $request
     * @param Coupon|null $coupon
     * @return bool
     */
    public function autoAssignCouponToRequest(ServiceRequest $request, ?Coupon $coupon = null): bool
    {
        if (!$coupon) {
            return false;
        }

        return $this->applyCouponToRequest($request, $coupon, true);
    }

    /**
     * Create a request-specific coupon
     * 
     * @param array $data
     * @param ServiceRequest $request
     * @param User $createdBy
     * @return Coupon
     */
    public function createRequestSpecificCoupon(array $data, ServiceRequest $request, User $createdBy): Coupon
    {
        return Coupon::create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'description' => $data['description'] ?? "Special discount for {$request->project_name}",
            'discount_type' => $data['discount_type'],
            'discount_value' => $data['discount_value'],
            'max_discount_amount' => $data['max_discount_amount'] ?? null,
            'min_purchase_amount' => $data['min_purchase_amount'] ?? 0,
            'coupon_type' => 'request_specific',
            'specific_request_id' => $request->id,
            'max_total_uses' => 1,
            'max_uses_per_user' => 1,
            'valid_from' => now(),
            'valid_until' => $data['valid_until'] ?? now()->addDays(30),
            'status' => 'active',
            'created_by' => $createdBy->id,
            'stackable_with_loyalty_tier' => $data['stackable_with_loyalty_tier'] ?? false,
            'stackable_with_points' => $data['stackable_with_points'] ?? true,
        ]);
    }

    /**
     * Update coupon usage status after payment
     * 
     * @param ServiceRequest $request
     * @param string $status ('completed', 'failed', 'refunded')
     * @return void
     */
    public function updateCouponUsageStatus(ServiceRequest $request, string $status): void
    {
        $usage = $request->couponUsage;
        
        if ($usage) {
            $usage->update(['payment_status' => $status]);

            // If payment failed or refunded, decrement coupon usage
            if (in_array($status, ['failed', 'refunded']) && $request->appliedCoupon) {
                $request->appliedCoupon->decrementUsage();
            }
        }
    }

    /**
     * Calculate final amount after all discounts
     * 
     * @param ServiceRequest $request
     * @return float
     */
    public function calculateFinalAmount(ServiceRequest $request): float
    {
        $original = $request->original_approved_budget ?? $request->approved_budget;
        $totalDiscount = $request->getTotalDiscount();

        // Apply maximum discount cap (70%)
        $maxDiscount = $original * (config('loyalty.discounts.maximum_total_percentage', 70) / 100);
        $totalDiscount = min($totalDiscount, $maxDiscount);

        return max(0, $original - $totalDiscount);
    }

    /**
     * Get coupon statistics
     * 
     * @param Coupon|null $coupon
     * @return array
     */
    public function getCouponStatistics(?Coupon $coupon = null): array
    {
        if ($coupon) {
            return [
                'total_uses' => $coupon->current_uses,
                'total_discount_given' => $coupon->usages()->sum('discount_amount'),
                'unique_users' => $coupon->usages()->distinct('user_id')->count(),
                'usage_percentage' => $coupon->getUsagePercentage(),
                'average_discount' => $coupon->usages()->avg('discount_amount'),
            ];
        }

        // Global statistics
        return [
            'total_coupons' => Coupon::count(),
            'active_coupons' => Coupon::active()->count(),
            'total_redemptions' => CouponUsage::completed()->count(),
            'total_discount_given' => CouponUsage::completed()->sum('discount_amount'),
            'average_discount' => CouponUsage::completed()->avg('discount_amount'),
        ];
    }
}
