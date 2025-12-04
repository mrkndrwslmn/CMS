<?php

namespace Tests\Unit\Models;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoyaltyPointTest extends TestCase
{
    /**
     * Test loyalty point relationships
     */
    public function test_loyalty_point_belongs_to_user(): void
    {
        $user = User::factory()->client()->create();
        
        $loyaltyPoint = LoyaltyPoint::create([
            'user_id' => $user->id,
            'total_points' => 0,
            'available_points' => 0,
            'lifetime_earned' => 0,
            'lifetime_redeemed' => 0,
            'tier' => 'bronze',
        ]);

        $this->assertEquals($user->id, $loyaltyPoint->user->id);
    }

    /**
     * Test earning points increases balances correctly
     */
    public function test_earn_points_increases_balances(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        
        $initialAvailable = $loyaltyPoint->available_points;
        $initialLifetime = $loyaltyPoint->lifetime_earned;

        $transaction = $loyaltyPoint->earnPoints(
            500,
            'test_source',
            'Test earning points'
        );

        $loyaltyPoint->refresh();

        $this->assertEquals($initialAvailable + 500, $loyaltyPoint->available_points);
        $this->assertEquals($initialLifetime + 500, $loyaltyPoint->lifetime_earned);
        $this->assertEquals(500, $transaction->points);
        $this->assertEquals('earned', $transaction->transaction_type);
    }

    /**
     * Test redeeming points decreases balances correctly
     */
    public function test_redeem_points_decreases_balances(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        
        // First earn some points
        $loyaltyPoint->earnPoints(1000, 'test', 'Initial points');
        $loyaltyPoint->refresh();

        $initialAvailable = $loyaltyPoint->available_points;
        $initialRedeemed = $loyaltyPoint->lifetime_redeemed;

        // Now redeem
        $transaction = $loyaltyPoint->redeemPoints(
            300,
            'redemption_test',
            'Test redeeming points'
        );

        $loyaltyPoint->refresh();

        $this->assertEquals($initialAvailable - 300, $loyaltyPoint->available_points);
        $this->assertEquals($initialRedeemed + 300, $loyaltyPoint->lifetime_redeemed);
        $this->assertEquals(-300, $transaction->points);
        $this->assertEquals('redeemed', $transaction->transaction_type);
    }

    /**
     * Test cannot redeem more points than available
     */
    public function test_cannot_redeem_more_than_available(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        
        // Earn only 100 points
        $loyaltyPoint->earnPoints(100, 'test', 'Initial points');
        $loyaltyPoint->refresh();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient loyalty points');

        // Try to redeem 500
        $loyaltyPoint->redeemPoints(500, 'test', 'Over-redemption attempt');
    }

    /**
     * Test points adjustment (positive)
     */
    public function test_adjust_points_positive(): void
    {
        $user = User::factory()->client()->create();
        $admin = User::factory()->admin()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        $initialBalance = $loyaltyPoint->available_points;

        $transaction = $loyaltyPoint->adjustPoints(
            250,
            'Bonus for feedback',
            $admin
        );

        $loyaltyPoint->refresh();

        $this->assertEquals($initialBalance + 250, $loyaltyPoint->available_points);
        $this->assertEquals(250, $transaction->points);
        $this->assertEquals('adjusted', $transaction->transaction_type);
        $this->assertEquals($admin->id, $transaction->performed_by);
    }

    /**
     * Test points adjustment (negative)
     */
    public function test_adjust_points_negative(): void
    {
        $user = User::factory()->client()->create();
        $admin = User::factory()->admin()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // First give some points
        $loyaltyPoint->earnPoints(500, 'test', 'Initial');
        $loyaltyPoint->refresh();

        $initialBalance = $loyaltyPoint->available_points;

        $transaction = $loyaltyPoint->adjustPoints(
            -200,
            'Correction for error',
            $admin
        );

        $loyaltyPoint->refresh();

        $this->assertEquals($initialBalance - 200, $loyaltyPoint->available_points);
        $this->assertEquals(-200, $transaction->points);
    }

    /**
     * Test cannot adjust negative more than available
     */
    public function test_cannot_adjust_negative_more_than_available(): void
    {
        $user = User::factory()->client()->create();
        $admin = User::factory()->admin()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // Only 100 points available
        $loyaltyPoint->earnPoints(100, 'test', 'Initial');
        $loyaltyPoint->refresh();

        $this->expectException(\Exception::class);

        // Try to deduct 500
        $loyaltyPoint->adjustPoints(-500, 'Invalid deduction', $admin);
    }

    /**
     * Test tier calculation based on lifetime points
     */
    public function test_calculate_tier_returns_correct_tier(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // Bronze (0 points)
        $this->assertEquals('bronze', $loyaltyPoint->calculateTier());

        // Earn to Silver threshold (5000+)
        $loyaltyPoint->update(['lifetime_earned' => 5000]);
        $this->assertEquals('silver', $loyaltyPoint->calculateTier());

        // Earn to Gold threshold (15000+)
        $loyaltyPoint->update(['lifetime_earned' => 15000]);
        $this->assertEquals('gold', $loyaltyPoint->calculateTier());

        // Earn to Platinum threshold (50000+)
        $loyaltyPoint->update(['lifetime_earned' => 50000]);
        $this->assertEquals('platinum', $loyaltyPoint->calculateTier());
    }

    /**
     * Test tier upgrade when earning enough points
     */
    public function test_tier_upgrades_on_earning_points(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        $this->assertEquals('bronze', $loyaltyPoint->tier);

        // Earn enough for silver (5000 points)
        $loyaltyPoint->earnPoints(5000, 'big_purchase', 'Large payment');
        $loyaltyPoint->refresh();

        $this->assertEquals('silver', $loyaltyPoint->tier);
    }

    /**
     * Test earning rate varies by tier
     */
    public function test_earning_rate_varies_by_tier(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // Bronze = 1%
        $loyaltyPoint->update(['tier' => 'bronze']);
        $this->assertEquals(1, $loyaltyPoint->getEarningRate());

        // Silver = 2%
        $loyaltyPoint->update(['tier' => 'silver']);
        $this->assertEquals(2, $loyaltyPoint->getEarningRate());

        // Gold = 3%
        $loyaltyPoint->update(['tier' => 'gold']);
        $this->assertEquals(3, $loyaltyPoint->getEarningRate());

        // Platinum = 5%
        $loyaltyPoint->update(['tier' => 'platinum']);
        $this->assertEquals(5, $loyaltyPoint->getEarningRate());
    }

    /**
     * Test points refund restores balance
     */
    public function test_refund_points_restores_balance(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // Earn and redeem
        $loyaltyPoint->earnPoints(1000, 'test', 'Initial');
        $loyaltyPoint->redeemPoints(500, 'test', 'Redemption');
        $loyaltyPoint->refresh();

        $balanceBefore = $loyaltyPoint->available_points;

        // Refund
        $transaction = $loyaltyPoint->refundPoints(500, 'Payment cancelled');
        $loyaltyPoint->refresh();

        $this->assertEquals($balanceBefore + 500, $loyaltyPoint->available_points);
        $this->assertEquals(500, $transaction->points);
        $this->assertEquals('refunded', $transaction->transaction_type);
    }

    /**
     * Test transaction records balance before and after
     */
    public function test_transaction_records_balances(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        $balanceBefore = $loyaltyPoint->available_points;

        $transaction = $loyaltyPoint->earnPoints(750, 'test', 'Test transaction');

        $this->assertEquals($balanceBefore, $transaction->balance_before);
        $this->assertEquals($balanceBefore + 750, $transaction->balance_after);
    }

    /**
     * Test points expiry date is set correctly
     */
    public function test_earn_points_sets_expiry_date(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        $transaction = $loyaltyPoint->earnPoints(100, 'test', 'Test');

        $this->assertNotNull($transaction->expires_at);
        
        // Should expire in configured months (default 12)
        $expectedExpiry = now()->addMonths(config('loyalty.points.expiry_months', 12));
        $this->assertEquals(
            $expectedExpiry->format('Y-m-d'),
            $transaction->expires_at->format('Y-m-d')
        );
    }

    /**
     * Test custom expiry date can be set
     */
    public function test_earn_points_accepts_custom_expiry(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        $customExpiry = now()->addMonths(6);

        $transaction = $loyaltyPoint->earnPoints(
            100,
            'test',
            'Test',
            null,
            $customExpiry
        );

        $this->assertEquals(
            $customExpiry->format('Y-m-d'),
            $transaction->expires_at->format('Y-m-d')
        );
    }
}
