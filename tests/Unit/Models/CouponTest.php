<?php

namespace Tests\Unit\Models;

use App\Models\Coupon;
use App\Models\User;
use App\Models\ServiceRequest;
use Tests\TestCase;

class CouponTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase (no migrations needed)

    /**
     * Test percentage discount calculation
     */
    public function test_calculates_percentage_discount(): void
    {
        $coupon = Coupon::factory()->percentage(20)->create();

        $discount = $coupon->calculateDiscount(10000);

        $this->assertEquals(2000, $discount);
    }

    /**
     * Test percentage discount respects max amount
     */
    public function test_percentage_discount_respects_max(): void
    {
        $coupon = Coupon::factory()->create([
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'max_discount_amount' => 1000,
        ]);

        // 20% of 10000 = 2000, but max is 1000
        $discount = $coupon->calculateDiscount(10000);

        $this->assertEquals(1000, $discount);
    }

    /**
     * Test fixed amount discount calculation
     */
    public function test_calculates_fixed_discount(): void
    {
        $coupon = Coupon::factory()->fixed(500)->create();

        $discount = $coupon->calculateDiscount(10000);

        $this->assertEquals(500, $discount);
    }

    /**
     * Test discount cannot exceed purchase amount
     */
    public function test_discount_cannot_exceed_purchase(): void
    {
        $coupon = Coupon::factory()->fixed(1000)->create([
            'min_purchase_amount' => 0, // No minimum so we can test with 500
        ]);

        // Buying 500 worth item with 1000 off coupon
        $discount = $coupon->calculateDiscount(500);

        $this->assertEquals(500, $discount);
    }

    /**
     * Test minimum purchase amount
     */
    public function test_respects_minimum_purchase(): void
    {
        $coupon = Coupon::factory()->create([
            'min_purchase_amount' => 5000,
            'discount_type' => 'fixed_amount',
            'discount_value' => 500,
        ]);

        // Below minimum - no discount
        $discount = $coupon->calculateDiscount(3000);
        $this->assertEquals(0, $discount);

        // At or above minimum - discount applies
        $discount = $coupon->calculateDiscount(5000);
        $this->assertEquals(500, $discount);
    }

    /**
     * Test coupon validity check
     */
    public function test_is_valid_check(): void
    {
        $validCoupon = Coupon::factory()->active()->create();
        $expiredCoupon = Coupon::factory()->expired()->create();
        $inactiveCoupon = Coupon::factory()->inactive()->create();

        $this->assertTrue($validCoupon->isValid());
        $this->assertFalse($expiredCoupon->isValid());
        $this->assertFalse($inactiveCoupon->isValid());
    }

    /**
     * Test validity period check
     */
    public function test_respects_validity_period(): void
    {
        $notYetValid = Coupon::factory()->create([
            'status' => 'active',
            'valid_from' => now()->addDays(5),
            'valid_until' => now()->addMonth(),
        ]);

        $this->assertFalse($notYetValid->isValid());
    }

    /**
     * Test usage limit check
     */
    public function test_respects_usage_limit(): void
    {
        $coupon = Coupon::factory()->create([
            'status' => 'active',
            'max_total_uses' => 10,
            'current_uses' => 10,
        ]);

        $this->assertFalse($coupon->hasUsageLeft());
        $this->assertFalse($coupon->isValid());
    }

    /**
     * Test unlimited usage coupon
     */
    public function test_unlimited_usage_coupon(): void
    {
        $coupon = Coupon::factory()->unlimited()->active()->create();

        $this->assertTrue($coupon->hasUsageLeft());
    }

    /**
     * Test user can use public coupon
     */
    public function test_user_can_use_public_coupon(): void
    {
        $user = User::factory()->client()->create();
        $coupon = Coupon::factory()->public()->active()->create();

        $this->assertTrue($coupon->canBeUsedBy($user));
    }

    /**
     * Test user-specific coupon
     */
    public function test_user_specific_coupon(): void
    {
        $targetUser = User::factory()->client()->create();
        $otherUser = User::factory()->client()->create();

        $coupon = Coupon::factory()->forUser($targetUser)->active()->create();

        $this->assertTrue($coupon->canBeUsedBy($targetUser));
        $this->assertFalse($coupon->canBeUsedBy($otherUser));
    }

    /**
     * Test per-user usage limit
     */
    public function test_per_user_usage_limit(): void
    {
        $user = User::factory()->client()->create();
        $coupon = Coupon::factory()->active()->create([
            'max_uses_per_user' => 1,
        ]);

        // First use - should be allowed
        $this->assertTrue($coupon->canBeUsedBy($user));

        // Create a service request for the usage record (FK constraint)
        $serviceRequest = ServiceRequest::factory()->forClient($user)->create();

        // Create a usage record
        $coupon->usages()->create([
            'user_id' => $user->id,
            'service_request_id' => $serviceRequest->id,
            'original_amount' => 1000,
            'discount_amount' => 100,
            'final_amount' => 900,
            'payment_status' => 'completed',
            'used_at' => now(),
        ]);

        // Second use - should be denied
        $this->assertFalse($coupon->canBeUsedBy($user));
    }

    /**
     * Test increment usage
     */
    public function test_increment_usage(): void
    {
        $coupon = Coupon::factory()->active()->create([
            'current_uses' => 0,
        ]);

        $coupon->incrementUsage();
        $coupon->refresh();

        $this->assertEquals(1, $coupon->current_uses);
    }

    /**
     * Test auto-expire on max uses
     */
    public function test_auto_expires_on_max_uses(): void
    {
        $coupon = Coupon::factory()->active()->create([
            'max_total_uses' => 1,
            'current_uses' => 0,
        ]);

        $coupon->incrementUsage();
        $coupon->refresh();

        $this->assertEquals('expired', $coupon->status);
    }

    /**
     * Test decrement usage
     */
    public function test_decrement_usage(): void
    {
        $coupon = Coupon::factory()->create([
            'current_uses' => 5,
        ]);

        $coupon->decrementUsage();
        $coupon->refresh();

        $this->assertEquals(4, $coupon->current_uses);
    }

    /**
     * Test decrement reactivates expired coupon
     */
    public function test_decrement_reactivates_if_was_maxed(): void
    {
        $coupon = Coupon::factory()->create([
            'status' => 'expired',
            'max_total_uses' => 10,
            'current_uses' => 10,
            'valid_until' => now()->addMonth(), // Not date-expired
        ]);

        $coupon->decrementUsage();
        $coupon->refresh();

        $this->assertEquals('active', $coupon->status);
    }

    /**
     * Test discount label formatting
     */
    public function test_discount_label_formatting(): void
    {
        $percentage = Coupon::factory()->percentage(20)->create();
        $fixed = Coupon::factory()->fixed(1000)->create();

        $this->assertEquals('20% OFF', $percentage->getDiscountLabel());
        $this->assertEquals('₱1,000 OFF', $fixed->getDiscountLabel());
    }

    /**
     * Test coupon type labels
     */
    public function test_coupon_type_labels(): void
    {
        $public = Coupon::factory()->public()->create();
        $userSpecific = Coupon::factory()->create(['coupon_type' => 'user_specific']);

        $this->assertEquals('Public Coupon', $public->getCouponTypeLabel());
        $this->assertEquals('User-Specific', $userSpecific->getCouponTypeLabel());
    }

    /**
     * Test status color
     */
    public function test_status_colors(): void
    {
        $active = Coupon::factory()->active()->create();
        $expired = Coupon::factory()->expired()->create();

        $this->assertEquals('success', $active->getStatusColor());
        $this->assertEquals('error', $expired->getStatusColor());
    }

    /**
     * Test days until expiration
     */
    public function test_days_until_expiration(): void
    {
        $coupon = Coupon::factory()->create([
            'valid_until' => now()->addDays(10),
        ]);

        // Allow for slight timing differences (9-10 days)
        $days = $coupon->getDaysUntilExpiration();
        $this->assertTrue($days >= 9 && $days <= 10, "Expected 9-10 days, got $days");
    }

    /**
     * Test expiring soon check
     */
    public function test_expiring_soon_check(): void
    {
        $expiringSoon = Coupon::factory()->expiringSoon()->create();
        $notExpiringSoon = Coupon::factory()->create([
            'valid_until' => now()->addMonth(),
        ]);

        $this->assertTrue($expiringSoon->isExpiringSoon());
        $this->assertFalse($notExpiringSoon->isExpiringSoon());
    }

    /**
     * Test usage percentage
     */
    public function test_usage_percentage(): void
    {
        $coupon = Coupon::factory()->create([
            'max_total_uses' => 100,
            'current_uses' => 25,
        ]);

        $this->assertEquals(25, $coupon->getUsagePercentage());
    }

    /**
     * Test active scope
     */
    public function test_active_scope(): void
    {
        Coupon::factory()->active()->count(3)->create();
        Coupon::factory()->expired()->count(2)->create();
        Coupon::factory()->inactive()->count(1)->create();

        $active = Coupon::active()->count();

        $this->assertEquals(3, $active);
    }

    /**
     * Test public scope
     */
    public function test_public_scope(): void
    {
        Coupon::factory()->public()->count(4)->create();
        Coupon::factory()->create(['coupon_type' => 'user_specific']);

        $public = Coupon::public()->count();

        $this->assertEquals(4, $public);
    }

    /**
     * Test get available for user
     */
    public function test_get_available_for_user(): void
    {
        $user = User::factory()->client()->create();

        // Create public coupons
        Coupon::factory()->public()->active()->count(2)->create();
        
        // Create user-specific coupon for this user
        Coupon::factory()->forUser($user)->active()->create();
        
        // Create user-specific coupon for another user
        $otherUser = User::factory()->client()->create();
        Coupon::factory()->forUser($otherUser)->active()->create();

        $available = Coupon::getAvailableForUser($user);

        $this->assertEquals(3, $available->count());
    }
}
