<?php

namespace Tests\Feature\Client;

use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Payment;
use Tests\TestCase;

class ServiceRequestTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase

    protected User $client;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = User::factory()->client()->create();
        $this->admin = User::factory()->admin()->create();
    }

    /**
     * Test client can view service request form
     */
    public function test_client_can_view_service_request_form(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.service-requests.create'));

        $response->assertStatus(200);
    }

    /**
     * Test client can create service request
     */
    public function test_client_can_create_service_request(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('client.service-requests.store'), [
                'service_type' => 'web_development',
                'project_name' => 'Build E-commerce Website',
                'request_description' => 'Need a full-featured online store with payment integration',
                'estimated_budget' => 50000,
                'deadline' => now()->addMonths(2)->format('Y-m-d'),
                'contact_method' => 'email',
                'contact_details' => $this->client->email,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('service_requests', [
            'client_id' => $this->client->id,
            'project_name' => 'Build E-commerce Website',
            'status' => 'pending',
        ]);
    }

    /**
     * Test service request requires mandatory fields
     */
    public function test_service_request_requires_mandatory_fields(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('client.service-requests.store'), [
                // Empty data
            ]);

        $response->assertSessionHasErrors(['service_type', 'project_name', 'request_description']);
    }

    /**
     * Test client can view their service requests
     */
    public function test_client_can_view_their_requests(): void
    {
        ServiceRequest::factory()->count(3)->forClient($this->client)->create();
        
        // Create requests for another client (should not be visible)
        $otherClient = User::factory()->client()->create();
        ServiceRequest::factory()->count(2)->forClient($otherClient)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.requests.index'));

        $response->assertStatus(200);
    }

    /**
     * Test client can view single request details
     */
    public function test_client_can_view_request_details(): void
    {
        $request = ServiceRequest::factory()->forClient($this->client)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.requests.show', $request->id));

        $response->assertStatus(200);
    }

    /**
     * Test client cannot view another client's request
     */
    public function test_client_cannot_view_others_request(): void
    {
        $otherClient = User::factory()->client()->create();
        $request = ServiceRequest::factory()->forClient($otherClient)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.requests.show', $request->id));

        $response->assertStatus(403);
    }

    /**
     * Test admin can approve service request
     */
    public function test_admin_can_approve_service_request(): void
    {
        $request = ServiceRequest::factory()->pending()->forClient($this->client)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.requests.approve', $request->id), [
                'approved_budget' => 75000,
                'admin_notes' => 'Approved with standard terms',
            ]);

        $response->assertRedirect();

        $request->refresh();
        $this->assertEquals('pending_payment', $request->status);
        $this->assertEquals(75000, $request->approved_budget);
    }

    /**
     * Test admin can reject service request
     */
    public function test_admin_can_reject_service_request(): void
    {
        $request = ServiceRequest::factory()->pending()->forClient($this->client)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.requests.reject', $request->id), [
                'rejection_reason' => 'Budget too low for requirements',
            ]);

        $response->assertRedirect();

        $request->refresh();
        $this->assertEquals('rejected', $request->status);
        $this->assertEquals('Budget too low for requirements', $request->rejection_reason);
    }

    /**
     * Test approved request shows payment information
     */
    public function test_approved_request_shows_payment_info(): void
    {
        $request = ServiceRequest::factory()->pendingPayment()->forClient($this->client)->create([
            'approved_budget' => 50000,
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('client.requests.show', $request->id));

        $response->assertStatus(200);
        $response->assertSee('50,000');
    }

    /**
     * Test service request status flow
     */
    public function test_service_request_status_methods(): void
    {
        $pendingRequest = ServiceRequest::factory()->pending()->create();
        $approvedRequest = ServiceRequest::factory()->approved()->create();
        $paidRequest = ServiceRequest::factory()->paid()->create();
        $rejectedRequest = ServiceRequest::factory()->rejected()->create();

        $this->assertTrue($pendingRequest->isPending());
        $this->assertFalse($pendingRequest->isApproved());

        $this->assertTrue($approvedRequest->isApproved());
        $this->assertFalse($approvedRequest->isPending());

        $this->assertTrue($paidRequest->isPaid());

        $this->assertTrue($rejectedRequest->isRejected());
    }

    /**
     * Test downpayment calculation
     */
    public function test_downpayment_calculation(): void
    {
        $request = ServiceRequest::factory()->withDownpayment(30)->create([
            'approved_budget' => 100000,
        ]);

        $this->assertEquals(30000, $request->calculateDownpaymentAmount());
        $this->assertEquals(70000, $request->calculateRemainingBalance());
    }

    /**
     * Test payment progress calculation
     */
    public function test_payment_progress_calculation(): void
    {
        $request = ServiceRequest::factory()->approved()->create([
            'approved_budget' => 100000,
        ]);

        // Create a confirmed payment
        Payment::factory()->confirmed()->forServiceRequest($request)->create([
            'amount' => 50000,
        ]);

        $this->assertEquals(50, $request->getPaymentProgress());
        $this->assertEquals(50000, $request->getRemainingPaymentBalance());
        $this->assertFalse($request->isFullyPaid());
    }

    /**
     * Test fully paid check
     */
    public function test_fully_paid_check(): void
    {
        $request = ServiceRequest::factory()->approved()->create([
            'approved_budget' => 50000,
        ]);

        Payment::factory()->confirmed()->forServiceRequest($request)->create([
            'amount' => 50000,
        ]);

        $this->assertTrue($request->isFullyPaid());
        $this->assertEquals(100, $request->getPaymentProgress());
    }

    /**
     * Test client can edit pending request
     */
    public function test_client_can_edit_pending_request(): void
    {
        $request = ServiceRequest::factory()->pending()->forClient($this->client)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.service-requests.edit', $request->id));

        $response->assertStatus(200);
    }

    /**
     * Test client cannot edit approved request
     */
    public function test_client_cannot_edit_approved_request(): void
    {
        $request = ServiceRequest::factory()->approved()->forClient($this->client)->create();

        $response = $this->actingAs($this->client)
            ->put(route('client.service-requests.update', $request->id), [
                'project_name' => 'Updated Name',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test service request status colors
     */
    public function test_status_colors(): void
    {
        $pending = ServiceRequest::factory()->pending()->create();
        $approved = ServiceRequest::factory()->approved()->create();
        $rejected = ServiceRequest::factory()->rejected()->create();

        $this->assertEquals('warning', $pending->getStatusColor());
        $this->assertEquals('success', $approved->getStatusColor());
        $this->assertEquals('error', $rejected->getStatusColor());
    }

    /**
     * Test service request status labels
     */
    public function test_status_labels(): void
    {
        $pending = ServiceRequest::factory()->pending()->create();
        $paid = ServiceRequest::factory()->paid()->create();

        $this->assertEquals('Pending Review', $pending->getStatusLabel());
        $this->assertEquals('Payment Confirmed', $paid->getStatusLabel());
    }
}
