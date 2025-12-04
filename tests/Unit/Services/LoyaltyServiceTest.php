<?php

namespace Tests\Unit\Services;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Payment;
use App\Services\LoyaltyService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LoyaltyServiceTest extends TestCase
{
    protected LoyaltyService $loyaltyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loyaltyService = app(LoyaltyService::class);
        
        // Clear caches before each test
        Cache::flush();
    }

    /**
     * Test calculating points for a payment
     */
    public function test_calculate_points_for_payment(): void
    {
        $client = User::factory()->client()->create();
        $loyaltyPoint = $client->getOrCreateLoyaltyPoints();
        $loyaltyPoint->update(['tier' => 'bronze']); // 1% earning rate

        $payment = Payment::factory()->create([
            'client_id' => $client->id,
            'amount' => 10000, // ₱10,000
            'status' => 'completed',
        ]);

        $points = $this->loyaltyService->calculatePointsForPayment($payment);

        // ₱10,000 / 100 = 100 base points * 1% = 1 point (minimum 1)
        $this->assertGreaterThanOrEqual(1, $points);
    }

    /**
     * Test higher tier earns more points
     */
    public function test_higher_tier_earns_more_points(): void
    {
        $bronzeClient = User::factory()->client()->create();
        $platinumClient = User::factory()->client()->create();

        $bronzeClient->getOrCreateLoyaltyPoints()->update(['tier' => 'bronze']);
        $platinumClient->getOrCreateLoyaltyPoints()->update(['tier' => 'platinum']);

        $bronzePayment = Payment::factory()->create([
            'client_id' => $bronzeClient->id,
            'amount' => 100000,
            'status' => 'completed',
        ]);

        $platinumPayment = Payment::factory()->create([
            'client_id' => $platinumClient->id,
            'amount' => 100000,
            'status' => 'completed',
        ]);

        $bronzePoints = $this->loyaltyService->calculatePointsForPayment($bronzePayment);
        $platinumPoints = $this->loyaltyService->calculatePointsForPayment($platinumPayment);

        $this->assertGreaterThan($bronzePoints, $platinumPoints);
    }

    /**
     * Test get all tiers returns correct structure
     */
    public function test_get_all_tiers_returns_correct_structure(): void
    {
        $tiers = $this->loyaltyService->getAllTiers();

        $this->assertArrayHasKey('bronze', $tiers);
        $this->assertArrayHasKey('silver', $tiers);
        $this->assertArrayHasKey('gold', $tiers);
        $this->assertArrayHasKey('platinum', $tiers);

        // Check structure
        foreach ($tiers as $tierName => $tierData) {
            $this->assertArrayHasKey('points', $tierData);
            $this->assertArrayHasKey('discount', $tierData);
        }

        // Check ordering (bronze should have lowest points requirement)
        $this->assertEquals(0, $tiers['bronze']['points']);
        $this->assertLessThan($tiers['gold']['points'], $tiers['silver']['points']);
        $this->assertLessThan($tiers['platinum']['points'], $tiers['gold']['points']);
    }

    /**
     * Test get tier benefits returns correct structure
     */
    public function test_get_tier_benefits_returns_correct_structure(): void
    {
        $benefits = $this->loyaltyService->getTierBenefits('gold');

        $this->assertArrayHasKey('earning_rate', $benefits);
        $this->assertArrayHasKey('discount', $benefits);
        $this->assertArrayHasKey('benefits', $benefits);
        $this->assertIsArray($benefits['benefits']);
    }

    /**
     * Test invalid tier returns bronze benefits
     */
    public function test_invalid_tier_returns_bronze_benefits(): void
    {
        $benefits = $this->loyaltyService->getTierBenefits('invalid_tier');
        $bronzeBenefits = $this->loyaltyService->getTierBenefits('bronze');

        $this->assertEquals($bronzeBenefits, $benefits);
    }

    /**
     * Test get user statistics returns complete data
     */
    public function test_get_user_statistics_returns_complete_data(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $loyaltyPoint->earnPoints(1000, 'test', 'Test points');

        $stats = $this->loyaltyService->getUserStatistics($user);

        $this->assertArrayHasKey('current_tier', $stats);
        $this->assertArrayHasKey('available_points', $stats);
        $this->assertArrayHasKey('lifetime_earned', $stats);
        $this->assertArrayHasKey('lifetime_redeemed', $stats);
        $this->assertArrayHasKey('next_tier', $stats);
        $this->assertArrayHasKey('points_to_next_tier', $stats);
        $this->assertArrayHasKey('tier_benefits', $stats);
        $this->assertArrayHasKey('earning_rate', $stats);
        $this->assertArrayHasKey('expiring_soon', $stats);
    }

    /**
     * Test user statistics shows correct next tier
     */
    public function test_user_statistics_shows_correct_next_tier(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $loyaltyPoint->update(['tier' => 'bronze', 'lifetime_earned' => 1000]);

        $stats = $this->loyaltyService->getUserStatistics($user);

        $this->assertEquals('bronze', $stats['current_tier']);
        $this->assertEquals('silver', $stats['next_tier']);
        $this->assertEquals(4000, $stats['points_to_next_tier']); // 5000 - 1000
    }

    /**
     * Test platinum tier has no next tier
     */
    public function test_platinum_has_no_next_tier(): void
    {
        $user = User::factory()->client()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $loyaltyPoint->update(['tier' => 'platinum', 'lifetime_earned' => 60000]);

        $stats = $this->loyaltyService->getUserStatistics($user);

        $this->assertEquals('platinum', $stats['current_tier']);
        $this->assertNull($stats['next_tier']);
        $this->assertNull($stats['points_to_next_tier']);
    }

    /**
     * Test adjust points through service
     */
    public function test_adjust_points_through_service(): void
    {
        $user = User::factory()->client()->create();
        $admin = User::factory()->admin()->create();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        $initialBalance = $loyaltyPoint->available_points;

        $this->loyaltyService->adjustPoints($user, 500, 'Test adjustment', $admin);

        $loyaltyPoint->refresh();
        $this->assertEquals($initialBalance + 500, $loyaltyPoint->available_points);
    }

    /**
     * Test convert points to discount
     */
    public function test_convert_points_to_discount(): void
    {
        // Default conversion rate is 1 point = ₱1
        $discount = $this->loyaltyService->convertPointsToDiscount(500);

        $conversionRate = config('loyalty.points.conversion_rate', 1);
        $this->assertEquals(500 * $conversionRate, $discount);
    }

    /**
     * Test global statistics returns complete data
     */
    public function test_get_global_statistics_returns_complete_data(): void
    {
        // Create some users with loyalty points
        $user1 = User::factory()->client()->create();
        $user2 = User::factory()->client()->create();

        $user1->getOrCreateLoyaltyPoints()->earnPoints(1000, 'test', 'Test');
        $user2->getOrCreateLoyaltyPoints()->earnPoints(2000, 'test', 'Test');

        $stats = $this->loyaltyService->getGlobalStatistics();

        $this->assertArrayHasKey('total_members', $stats);
        $this->assertArrayHasKey('total_points_circulation', $stats);
        $this->assertArrayHasKey('total_points_earned', $stats);
        $this->assertArrayHasKey('total_points_redeemed', $stats);
        $this->assertArrayHasKey('average_points_per_user', $stats);
        $this->assertArrayHasKey('tier_distribution', $stats);
        $this->assertArrayHasKey('transactions_this_month', $stats);

        $this->assertGreaterThanOrEqual(2, $stats['total_members']);
    }

    /**
     * Test tier distribution is accurate
     */
    public function test_tier_distribution_is_accurate(): void
    {
        // Create users at different tiers
        $bronzeUser = User::factory()->client()->create();
        $silverUser = User::factory()->client()->create();
        $goldUser = User::factory()->client()->create();

        $bronzeUser->getOrCreateLoyaltyPoints()->update(['tier' => 'bronze']);
        $silverUser->getOrCreateLoyaltyPoints()->update(['tier' => 'silver']);
        $goldUser->getOrCreateLoyaltyPoints()->update(['tier' => 'gold']);

        // Clear cache to get fresh data
        $this->loyaltyService->clearGlobalCache();
        
        $stats = $this->loyaltyService->getGlobalStatistics();

        $this->assertGreaterThanOrEqual(1, $stats['tier_distribution']['bronze']);
        $this->assertGreaterThanOrEqual(1, $stats['tier_distribution']['silver']);
        $this->assertGreaterThanOrEqual(1, $stats['tier_distribution']['gold']);
    }

    /**
     * Test caching works for tier data
     */
    public function test_tier_data_is_cached(): void
    {
        // First call - should query and cache
        $tiers1 = $this->loyaltyService->getAllTiers();

        // Verify cache was set
        $this->assertTrue(Cache::has('loyalty:all_tiers'));

        // Second call - should use cache
        $tiers2 = $this->loyaltyService->getAllTiers();

        $this->assertEquals($tiers1, $tiers2);
    }

    /**
     * Test cache invalidation for user stats
     */
    public function test_cache_invalidation_on_adjust_points(): void
    {
        $user = User::factory()->client()->create();
        $admin = User::factory()->admin()->create();
        $user->getOrCreateLoyaltyPoints();

        // Cache the stats
        $statsBefore = $this->loyaltyService->getUserStatistics($user);
        $cachedKey = "loyalty:user_stats:{$user->id}";
        $this->assertTrue(Cache::has($cachedKey));

        // Adjust points (should clear cache)
        $this->loyaltyService->adjustPoints($user, 500, 'Test', $admin);

        // Cache should be cleared
        $this->assertFalse(Cache::has($cachedKey));
    }

    /**
     * Test clear all cache method
     */
    public function test_clear_all_cache(): void
    {
        // Populate caches
        $this->loyaltyService->getAllTiers();
        $this->loyaltyService->getTierBenefits('gold');
        
        $user = User::factory()->client()->create();
        $user->getOrCreateLoyaltyPoints();
        $this->loyaltyService->getGlobalStatistics();

        // Clear all
        $this->loyaltyService->clearAllCache();

        // Verify caches are cleared
        $this->assertFalse(Cache::has('loyalty:all_tiers'));
        $this->assertFalse(Cache::has('loyalty:tier_benefits:gold'));
        $this->assertFalse(Cache::has('loyalty:global_stats'));
    }
}
