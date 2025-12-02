<?php

namespace Tests\Unit\Models;

use App\Models\Referral;
use App\Models\User;
use App\Models\Payment;
use App\Models\Coupon;
use Tests\TestCase;

class ReferralTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase

    /**
     * Test referral relationships
     */
    public function test_referral_has_correct_relationships(): void
    {
        $referrer = User::factory()->client()->create();
        $referred = User::factory()->client()->create();

        $referral = Referral::factory()->create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referred->id,
        ]);

        $this->assertEquals($referrer->id, $referral->referrer->id);
        $this->assertEquals($referred->id, $referral->referred->id);
    }

    /**
     * Test pending status check
     */
    public function test_is_pending_returns_correct_status(): void
    {
        $pending = Referral::factory()->pending()->create();
        $completed = Referral::factory()->completed()->create();

        $this->assertTrue($pending->isPending());
        $this->assertFalse($completed->isPending());
    }

    /**
     * Test completed status check
     */
    public function test_is_completed_returns_correct_status(): void
    {
        $pending = Referral::factory()->pending()->create();
        $completed = Referral::factory()->completed()->create();

        $this->assertFalse($pending->isCompleted());
        $this->assertTrue($completed->isCompleted());
    }

    /**
     * Test rewarded status check
     */
    public function test_is_rewarded_returns_correct_status(): void
    {
        $completed = Referral::factory()->completed()->create();
        $rewarded = Referral::factory()->rewarded()->create();

        $this->assertFalse($completed->isRewarded());
        $this->assertTrue($rewarded->isRewarded());
    }

    /**
     * Test mark completed
     */
    public function test_mark_completed_updates_referral(): void
    {
        $referral = Referral::factory()->pending()->create();
        $payment = Payment::factory()->confirmed()->create();

        $referral->markCompleted($payment);
        $referral->refresh();

        $this->assertEquals('completed', $referral->status);
        $this->assertEquals($payment->id, $referral->first_payment_id);
        $this->assertNotNull($referral->completed_at);
    }

    /**
     * Test mark rewarded
     */
    public function test_mark_rewarded_updates_referral(): void
    {
        $referral = Referral::factory()->completed()->create();

        $referral->markRewarded();
        $referral->refresh();

        $this->assertEquals('rewarded', $referral->status);
        $this->assertNotNull($referral->rewarded_at);
    }

    /**
     * Test referral with coupon reward
     */
    public function test_referral_with_coupon_rewards(): void
    {
        $referrerCoupon = Coupon::factory()->active()->create();
        $referredCoupon = Coupon::factory()->active()->create();

        $referral = Referral::factory()->create([
            'referrer_coupon_id' => $referrerCoupon->id,
            'referred_coupon_id' => $referredCoupon->id,
        ]);

        $this->assertEquals($referrerCoupon->id, $referral->referrerCoupon->id);
        $this->assertEquals($referredCoupon->id, $referral->referredCoupon->id);
    }

    /**
     * Test referral points tracking
     */
    public function test_referral_tracks_points(): void
    {
        $referral = Referral::factory()->create([
            'referrer_points_pending' => 100,
            'referrer_points_earned' => 0,
            'referred_points_earned' => 50,
        ]);

        $this->assertEquals(100, $referral->referrer_points_pending);
        $this->assertEquals(0, $referral->referrer_points_earned);
        $this->assertEquals(50, $referral->referred_points_earned);
    }

    /**
     * Test referral code is stored
     */
    public function test_referral_stores_code(): void
    {
        $user = User::factory()->client()->create();

        $referral = Referral::factory()->create([
            'referrer_id' => $user->id,
            'referral_code' => 'MYCODE',
        ]);

        $this->assertEquals('MYCODE', $referral->referral_code);
    }

    /**
     * Test referral metadata is stored as array
     */
    public function test_referral_casts_metadata_as_array(): void
    {
        $referral = Referral::factory()->create([
            'metadata' => ['source' => 'facebook', 'campaign' => 'summer2024'],
        ]);

        $this->assertIsArray($referral->metadata);
        $this->assertEquals('facebook', $referral->metadata['source']);
        $this->assertEquals('summer2024', $referral->metadata['campaign']);
    }

    /**
     * Test referral tracks IP address and user agent
     */
    public function test_referral_tracks_browser_info(): void
    {
        $referral = Referral::factory()->create([
            'ip_address' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0 Test Browser',
        ]);

        $this->assertEquals('192.168.1.1', $referral->ip_address);
        $this->assertEquals('Mozilla/5.0 Test Browser', $referral->user_agent);
    }

    /**
     * Test datetime casts
     */
    public function test_dates_are_cast_correctly(): void
    {
        $referral = Referral::factory()->rewarded()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $referral->completed_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $referral->rewarded_at);
    }
}
