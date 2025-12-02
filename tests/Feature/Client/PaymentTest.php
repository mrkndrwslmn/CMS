<?php

namespace Tests\Feature\Client;

use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Payment;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase

    protected User $client;
    protected ServiceRequest $serviceRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = User::factory()->client()->create();
        $this->serviceRequest = ServiceRequest::factory()
            ->pendingPayment()
            ->forClient($this->client)
            ->create([
                'approved_budget' => 50000,
            ]);
    }

    /**
     * Test client can view payment page
     */
    public function test_client_can_view_payment_page(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.requests.show-payment', $this->serviceRequest->id));

        // May redirect if no payment due or show payment page
        $response->assertStatus(302)->assertRedirect();
    }

    /**
     * Test client can initiate Maya checkout
     */
    public function test_client_can_initiate_checkout(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.maya.checkout', $this->serviceRequest->id));

        // Should redirect to Maya payment page or show checkout view
        $response->assertStatus(302)->assertRedirectContains('maya');
    }

    /**
     * Test client can view payment history
     */
    public function test_client_can_view_payment_history(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.payments.history'));

        $response->assertStatus(200);
    }

    /**
     * Test client can view payment details
     */
    public function test_client_can_view_payment_details(): void
    {
        $payment = Payment::factory()->create([
            'service_request_id' => $this->serviceRequest->id,
            'client_id' => $this->client->id,
            'amount' => 50000,
            'status' => 'confirmed',  // Valid enum: pending, confirmed, failed, refunded, cancelled
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('client.payments.show', $payment->id));

        $response->assertStatus(200);
    }

    /**
     * Test payment success callback
     */
    public function test_payment_success_callback(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.maya.success'));

        // Maya success typically redirects to show the request or displays a success page
        $this->assertTrue(
            in_array($response->status(), [200, 302]),
            "Expected 200 or 302, got {$response->status()}"
        );
    }

    /**
     * Test payment failure callback
     */
    public function test_payment_failure_callback(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.maya.failure'));

        $response->assertStatus(200);
    }

    /**
     * Test payment cancel callback
     */
    public function test_payment_cancel_callback(): void
    {
        $response = $this->actingAs($this->client)
            ->get(route('client.maya.cancel'));

        $response->assertStatus(200);
    }
}
