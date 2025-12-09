<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Services\CouponService;
use App\Services\LoyaltyService;
use App\Services\MilestoneService;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MilestoneRecalculationTest extends TestCase
{
    protected User $client;
    protected User $admin;
    protected CouponService $couponService;
    protected LoyaltyService $loyaltyService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create users
        $this->client = User::factory()->client()->create();
        $this->admin = User::factory()->admin()->create();
        
        // Initialize services
        $this->couponService = app(CouponService::class);
        $this->loyaltyService = app(LoyaltyService::class);
        
        // Disable email sending
        Mail::fake();
    }

    /**
     * Helper to create a service request with milestones
     */
    protected function createServiceRequestWithMilestones(float $budget = 1000000): array
    {
        // Create service request
        $serviceRequest = ServiceRequest::create([
            'client_id' => $this->client->id,
            'project_name' => 'Test Project ' . uniqid(),
            'service_type' => 'web_development',
            'request_description' => 'Test Description',
            'status' => 'approved',
            'approved_budget' => $budget,
            'payment_type' => 'milestone_payment',
            'contact_method' => 'email',
            'contact_details' => 'test@example.com',
        ]);

        // Create project
        $project = Project::create([
            'service_request_id' => $serviceRequest->id,
            'client_id' => $this->client->id,
            'title' => 'Test Project',
            'description' => 'Test Description',
            'budget' => $budget,
            'status' => 'active',
        ]);

        // Create milestones (40%, 30%, 15%, 15%)
        $percentages = [40, 30, 15, 15];
        $milestones = [];
        
        foreach ($percentages as $index => $percentage) {
            $amount = ($budget * $percentage) / 100;
            $milestones[] = ProjectMilestone::create([
                'project_id' => $project->id,
                'service_request_id' => $serviceRequest->id,
                'phase_name' => 'Phase ' . ($index + 1),
                'phase_order' => $index + 1,
                'percentage' => $percentage,
                'amount' => $amount,
                'is_paid' => false,
            ]);
        }

        return [
            'serviceRequest' => $serviceRequest,
            'project' => $project,
            'milestones' => $milestones,
        ];
    }

    /**
     * Test milestone amounts are recalculated when coupon is applied
     */
    public function test_milestone_amounts_recalculated_after_coupon_applied(): void
    {
        // Create a service request with milestones
        $data = $this->createServiceRequestWithMilestones(1000000);
        $serviceRequest = $data['serviceRequest'];
        $project = $data['project'];

        // Verify initial milestone amounts
        $milestones = $project->milestones()->orderBy('phase_order')->get();
        $this->assertEquals(400000, $milestones[0]->amount);
        $this->assertEquals(300000, $milestones[1]->amount);
        $this->assertEquals(150000, $milestones[2]->amount);
        $this->assertEquals(150000, $milestones[3]->amount);
        $this->assertEquals(1000000, $milestones->sum('amount'));

        // Create and apply a coupon (30,000 discount)
        $coupon = Coupon::factory()->fixed(30000)->active()->create();

        $this->couponService->applyCouponToRequest($serviceRequest, $coupon);

        // Refresh models to get updated values
        $serviceRequest->refresh();
        $project->refresh();
        $milestones = $project->milestones()->orderBy('phase_order')->get();

        // Verify budget was reduced
        $this->assertEquals(970000, $serviceRequest->approved_budget);
        $this->assertEquals(970000, $project->budget);

        // Verify milestone amounts were recalculated
        // 970,000 * 40% = 388,000
        // 970,000 * 30% = 291,000
        // 970,000 * 15% = 145,500
        // 970,000 * 15% = 145,500
        $this->assertEquals(388000, $milestones[0]->amount);
        $this->assertEquals(291000, $milestones[1]->amount);
        $this->assertEquals(145500, $milestones[2]->amount);
        $this->assertEquals(145500, $milestones[3]->amount);
        $this->assertEquals(970000, $milestones->sum('amount'));
    }

    /**
     * Test milestone amounts are recalculated when coupon is removed
     */
    public function test_milestone_amounts_recalculated_after_coupon_removed(): void
    {
        // Create a service request with milestones
        $data = $this->createServiceRequestWithMilestones(1000000);
        $serviceRequest = $data['serviceRequest'];
        $project = $data['project'];

        // Create and apply a coupon (30,000 discount)
        $coupon = Coupon::factory()->fixed(30000)->active()->create();

        $this->couponService->applyCouponToRequest($serviceRequest, $coupon);
        $serviceRequest->refresh();

        // Verify discount was applied
        $this->assertEquals(970000, $serviceRequest->approved_budget);

        // Remove the coupon
        $this->couponService->removeCouponFromRequest($serviceRequest);

        // Refresh models
        $serviceRequest->refresh();
        $project->refresh();
        $milestones = $project->milestones()->orderBy('phase_order')->get();

        // Verify budget was restored
        $this->assertEquals(1000000, $serviceRequest->approved_budget);
        $this->assertEquals(1000000, $project->budget);

        // Verify milestone amounts were restored
        $this->assertEquals(400000, $milestones[0]->amount);
        $this->assertEquals(300000, $milestones[1]->amount);
        $this->assertEquals(150000, $milestones[2]->amount);
        $this->assertEquals(150000, $milestones[3]->amount);
        $this->assertEquals(1000000, $milestones->sum('amount'));
    }

    /**
     * Test milestone amounts are recalculated when loyalty discount is applied
     */
    public function test_milestone_amounts_recalculated_after_loyalty_applied(): void
    {
        // Create a service request with milestones
        $data = $this->createServiceRequestWithMilestones(1000000);
        $serviceRequest = $data['serviceRequest'];
        $project = $data['project'];

        // Create loyalty points for client
        $loyaltyPoint = $this->client->getOrCreateLoyaltyPoints();
        $loyaltyPoint->update([
            'current_balance' => 5000,
            'lifetime_earned' => 5000,
        ]);

        // Apply loyalty discount (1520 points = 1520 pesos)
        $this->loyaltyService->applyLoyaltyDiscount($serviceRequest, 1520);

        // Refresh models
        $serviceRequest->refresh();
        $project->refresh();
        $milestones = $project->milestones()->orderBy('phase_order')->get();

        // Verify budget was reduced
        $this->assertEquals(998480, $serviceRequest->approved_budget);
        $this->assertEquals(998480, $project->budget);

        // Verify milestone amounts were recalculated
        // 998,480 * 40% = 399,392
        // 998,480 * 30% = 299,544
        // 998,480 * 15% = 149,772
        // 998,480 * 15% = 149,772
        $this->assertEquals(399392, $milestones[0]->amount);
        $this->assertEquals(299544, $milestones[1]->amount);
        $this->assertEquals(149772, $milestones[2]->amount);
        $this->assertEquals(149772, $milestones[3]->amount);
        $this->assertEquals(998480, $milestones->sum('amount'));
    }

    /**
     * Test combined coupon and loyalty discount recalculates milestones
     */
    public function test_milestone_amounts_recalculated_after_combined_discounts(): void
    {
        // Create a service request with milestones
        $data = $this->createServiceRequestWithMilestones(1000000);
        $serviceRequest = $data['serviceRequest'];
        $project = $data['project'];

        // Create and apply a coupon (30,000 discount)
        $coupon = Coupon::factory()->fixed(30000)->active()->create();

        $this->couponService->applyCouponToRequest($serviceRequest, $coupon);
        $serviceRequest->refresh();

        // Create loyalty points and apply loyalty discount (1520 pesos)
        $loyaltyPoint = $this->client->getOrCreateLoyaltyPoints();
        $loyaltyPoint->update([
            'current_balance' => 5000,
            'lifetime_earned' => 5000,
        ]);

        $this->loyaltyService->applyLoyaltyDiscount($serviceRequest, 1520);

        // Refresh models
        $serviceRequest->refresh();
        $project->refresh();
        $milestones = $project->milestones()->orderBy('phase_order')->get();

        // Verify budget: 1,000,000 - 30,000 (coupon) - 1,520 (loyalty) = 968,480
        $this->assertEquals(968480, $serviceRequest->approved_budget);
        $this->assertEquals(968480, $project->budget);

        // Verify milestone amounts sum to final budget
        $this->assertEquals(968480, $milestones->sum('amount'));
    }

    /**
     * Test downpayment amounts are recalculated when coupon is applied
     */
    public function test_downpayment_amounts_recalculated_after_coupon_applied(): void
    {
        // Create service request with downpayment type
        $serviceRequest = ServiceRequest::create([
            'client_id' => $this->client->id,
            'project_name' => 'Test Downpayment Project ' . uniqid(),
            'service_type' => 'web_development',
            'request_description' => 'Test Description',
            'status' => 'approved',
            'approved_budget' => 1000000,
            'payment_type' => 'downpayment',
            'downpayment_percentage' => 50,
            'downpayment_amount' => 500000, // 50% of 1,000,000
            'remaining_balance' => 500000,
            'contact_method' => 'email',
            'contact_details' => 'test@example.com',
        ]);

        // Create project
        $project = Project::create([
            'service_request_id' => $serviceRequest->id,
            'client_id' => $this->client->id,
            'title' => 'Test Project',
            'description' => 'Test Description',
            'budget' => 1000000,
            'status' => 'active',
        ]);

        // Verify initial downpayment amounts
        $this->assertEquals(500000, $serviceRequest->downpayment_amount);
        $this->assertEquals(500000, $serviceRequest->remaining_balance);

        // Create and apply a coupon (30,000 discount)
        $coupon = Coupon::factory()->fixed(30000)->active()->create();

        $this->couponService->applyCouponToRequest($serviceRequest, $coupon);

        // Refresh models
        $serviceRequest->refresh();
        $project->refresh();

        // Verify budget was reduced
        $this->assertEquals(970000, $serviceRequest->approved_budget);
        $this->assertEquals(970000, $project->budget);

        // Verify downpayment amounts were recalculated
        // 970,000 * 50% = 485,000
        $this->assertEquals(485000, $serviceRequest->downpayment_amount);
        $this->assertEquals(485000, $serviceRequest->remaining_balance);
    }

    /**
     * Test that paid milestones are not recalculated
     */
    public function test_paid_milestones_not_recalculated(): void
    {
        // Create a service request with milestones
        $data = $this->createServiceRequestWithMilestones(1000000);
        $serviceRequest = $data['serviceRequest'];
        $project = $data['project'];

        // Mark first milestone as paid
        $firstMilestone = $project->milestones()->orderBy('phase_order')->first();
        $firstMilestone->update(['is_paid' => true]);

        // Create and apply a coupon (30,000 discount)
        $coupon = Coupon::factory()->fixed(30000)->active()->create();

        $this->couponService->applyCouponToRequest($serviceRequest, $coupon);

        // Refresh milestones
        $milestones = $project->milestones()->orderBy('phase_order')->get();

        // First milestone (paid) should NOT be changed
        $this->assertEquals(400000, $milestones[0]->amount);
        
        // Other milestones (unpaid) should be recalculated
        $this->assertEquals(291000, $milestones[1]->amount);
        $this->assertEquals(145500, $milestones[2]->amount);
        $this->assertEquals(145500, $milestones[3]->amount);
    }

    /**
     * Test static helper method works correctly
     */
    public function test_static_recalculation_method(): void
    {
        // Create a service request with milestones
        $data = $this->createServiceRequestWithMilestones(1000000);
        $serviceRequest = $data['serviceRequest'];
        $project = $data['project'];

        // Manually update the approved budget (simulating discount)
        $serviceRequest->update(['approved_budget' => 900000]);

        // Call static recalculation method
        MilestoneService::recalculatePaymentAmountsForRequest($serviceRequest);

        // Refresh project and milestones
        $project->refresh();
        $milestones = $project->milestones()->orderBy('phase_order')->get();

        // Verify project budget was updated
        $this->assertEquals(900000, $project->budget);

        // Verify milestone amounts were recalculated
        $this->assertEquals(360000, $milestones[0]->amount);
        $this->assertEquals(270000, $milestones[1]->amount);
        $this->assertEquals(135000, $milestones[2]->amount);
        $this->assertEquals(135000, $milestones[3]->amount);
        $this->assertEquals(900000, $milestones->sum('amount'));
    }
}
