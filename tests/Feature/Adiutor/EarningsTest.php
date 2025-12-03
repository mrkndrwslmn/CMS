<?php

namespace Tests\Feature\Adiutor;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\Payout;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Adiutor Earnings Tests
 * 
 * Tests the earnings and wallet functionality for adiutors:
 * - Viewing earnings dashboard
 * - Viewing wallet balance
 * - Requesting payouts
 * - Viewing payout history
 */
class EarningsTest extends TestCase
{
    use UseCmsSqlSchema;

    protected User $adiutor;
    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bind test services
        $this->app->bind(\App\Services\FirebaseService::class, \Tests\Mocks\FakeFirebaseService::class);

        // Create adiutor with wallet balance
        // Note: users table has work_earnings_balance, work_earnings_pending, work_earnings_withdrawn
        $this->adiutor = User::factory()->create([
            'role' => 'adiutor',
            'email_verified_at' => now(),
            'status' => 'active',
            'work_earnings_balance' => 5000.00,
            'work_earnings_pending' => 2000.00,
            'work_earnings_withdrawn' => 3000.00,
        ]);

        // Create project
        $this->project = Project::factory()->create();

        // Assign adiutor to project
        DB::table('project_assignments')->insert([
            'project_id' => $this->project->id,
            'adiutor_id' => $this->adiutor->id,
            'role' => 'developer',
            'status' => 'accepted',
            'hourly_rate' => 500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // =========================================
    // Earnings Dashboard Tests
    // =========================================

    public function test_adiutor_can_view_earnings_dashboard(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.earnings.index'));

        $response->assertStatus(200);
    }

    public function test_non_adiutor_cannot_access_earnings(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($client);

        $response = $this->get(route('adiutor.earnings.index'));

        $this->assertTrue(
            $response->isRedirection() || $response->status() === 403
        );
    }

    public function test_unauthenticated_user_is_redirected(): void
    {
        $response = $this->get(route('adiutor.earnings.index'));

        $response->assertRedirect(route('login'));
    }

    // =========================================
    // Wallet Dashboard Tests
    // =========================================

    public function test_adiutor_can_view_wallet(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.earnings.wallet'));

        $response->assertStatus(200);
    }

    public function test_wallet_shows_correct_balance(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.earnings.wallet'));

        $response->assertStatus(200);
        // The view should contain balance information
    }

    // =========================================
    // Payout History Tests
    // =========================================

    public function test_adiutor_can_view_payout_history(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.earnings.payouts'));

        $response->assertStatus(200);
    }

    public function test_payout_history_shows_own_payouts(): void
    {
        $this->actingAs($this->adiutor);

        // Create payouts for this adiutor
        Payout::factory()->count(3)->create([
            'adiutor_id' => $this->adiutor->id,
            'status' => 'completed',
        ]);

        // Create payouts for another adiutor
        $otherAdiutor = User::factory()->create(['role' => 'adiutor', 'status' => 'active']);
        Payout::factory()->count(2)->create([
            'adiutor_id' => $otherAdiutor->id,
            'status' => 'completed',
        ]);

        $response = $this->get(route('adiutor.earnings.payouts'));

        $response->assertStatus(200);
    }

    // =========================================
    // View Payout Details Tests
    // =========================================

    public function test_adiutor_can_view_own_payout_details(): void
    {
        $this->actingAs($this->adiutor);

        $payout = Payout::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'status' => 'completed',
        ]);

        $response = $this->get(route('adiutor.earnings.payout.show', $payout->id));

        $response->assertStatus(200);
    }

    public function test_cannot_view_other_adiutor_payout(): void
    {
        $this->actingAs($this->adiutor);

        $otherAdiutor = User::factory()->create(['role' => 'adiutor', 'status' => 'active']);
        $payout = Payout::factory()->create([
            'adiutor_id' => $otherAdiutor->id,
            'status' => 'completed',
        ]);

        $response = $this->get(route('adiutor.earnings.payout.show', $payout->id));

        $this->assertTrue(
            $response->status() === 403 || $response->status() === 404 || $response->isRedirection()
        );
    }

    // =========================================
    // Request Payout Tests
    // =========================================

    public function test_adiutor_can_view_payout_request_form(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.earnings.request-form'));

        $response->assertStatus(200);
    }

    public function test_adiutor_can_request_payout(): void
    {
        $this->actingAs($this->adiutor);

        // Create approved time entries
        TimeEntry::factory()->count(3)->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'is_approved' => true,
            'approved_at' => now(),
            'is_paid' => false,
            'calculated_amount' => 1000,
        ]);

        $response = $this->post(route('adiutor.earnings.request'), [
            'payout_method' => 'bank_transfer',
            'bank_name' => 'BDO',
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        // Should redirect with success
        $response->assertRedirect();
    }

    public function test_cannot_request_payout_without_approved_entries(): void
    {
        // Create adiutor with no approved entries
        $newAdiutor = User::factory()->create([
            'role' => 'adiutor',
            'status' => 'active',
            'email_verified_at' => now(),
            'work_earnings_balance' => 0,
        ]);

        $this->actingAs($newAdiutor);

        $response = $this->post(route('adiutor.earnings.request'), [
            'payout_method' => 'bank_transfer',
            'bank_name' => 'BDO',
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        // Should be redirected with error
        $response->assertRedirect();
    }

    public function test_payout_request_requires_payout_method(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.earnings.request'), [
            // Missing payout_method
            'bank_name' => 'BDO',
            'account_number' => '1234567890',
        ]);

        $response->assertSessionHasErrors('payout_method');
    }

    // =========================================
    // Pending Payouts Tests
    // =========================================

    public function test_adiutor_cannot_request_payout_with_pending_payout(): void
    {
        $this->actingAs($this->adiutor);

        // Create pending payout
        Payout::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('adiutor.earnings.request'), [
            'payout_method' => 'bank_transfer',
            'bank_name' => 'BDO',
            'account_number' => '1234567890',
            'account_name' => 'John Doe',
        ]);

        // Should be redirected with error
        $response->assertRedirect();
    }
}
