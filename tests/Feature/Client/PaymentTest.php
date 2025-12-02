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
            ->get(route('client.requests.payment', $this->serviceRequest->id));

        $response->assertStatus(200);
    }

    /**
     * Test Maya checkout creation
     */
    public function test_maya_checkout_is_created(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('client.maya.checkout', $this->serviceRequest->id));

        // Fake Maya service should have created a checkout
        $this->fakeMayaPayment->assertCheckoutCreated();

        $lastCheckout = $this->fakeMayaPayment->getLastCheckout();
        $this->assertNotNull($lastCheckout);
    }

    /**
     * Test checkout with correct amount
     */
    public function test_checkout_uses_correct_amount(): void
    {
        $this->actingAs($this->client)
            ->post(route('client.maya.checkout', $this->serviceRequest->id));

        $this->fakeMayaPayment->assertCheckoutCreatedWith([
            'amount' => 50000,
        ]);
    }

    /**
     * Test payment success callback
     */
    public function test_payment_success_callback(): void
    {
        $payment = Payment::factory()->pending()->forServiceRequest($this->serviceRequest)->create([
            'payment_details' => ['checkout_id' => 'test_checkout_123'],
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('client.maya.success', [
                'checkout_id' => 'test_checkout_123',
            ]));

        // Fake Maya should verify the payment
        $this->fakeMayaPayment->assertPaymentVerified('test_checkout_123');

        $payment->refresh();
        $this->assertEquals('confirmed', $payment->status);
    }

    /**
     * Test payment failure handling
     */
    public function test_payment_failure_handling(): void
    {
        $this->fakeMayaPayment->shouldFail();

        $payment = Payment::factory()->pending()->forServiceRequest($this->serviceRequest)->create([
            'payment_details' => ['checkout_id' => 'failed_checkout'],
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('client.maya.failure', [
                'checkout_id' => 'failed_checkout',
            ]));

        $payment->refresh();
        $this->assertEquals('failed', $payment->status);
    }

    /**
     * Test payment creates record in database
     */
    public function test_payment_creates_database_record(): void
    {
        $this->actingAs($this->client)
            ->post(route('client.maya.checkout', $this->serviceRequest->id));

        $this->assertDatabaseHas('payments', [
            'service_request_id' => $this->serviceRequest->id,
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test confirmed payment updates service request status
     */
    public function test_confirmed_payment_updates_request_status(): void
    {
        // Create a full payment
        $payment = Payment::factory()->pending()->fullPayment()->create([
            'service_request_id' => $this->serviceRequest->id,
            'client_id' => $this->client->id,
            'amount' => 50000,
            'payment_details' => ['checkout_id' => 'test_checkout'],
        ]);

        // Simulate successful payment callback
        $response = $this->actingAs($this->client)
            ->get(route('client.maya.success', [
                'checkout_id' => 'test_checkout',
            ]));

        $this->serviceRequest->refresh();
        $this->assertEquals('paid', $this->serviceRequest->status);
    }

    /**
     * Test downpayment flow
     */
    public function test_downpayment_flow(): void
    {
        $request = ServiceRequest::factory()
            ->withDownpayment(30)
            ->forClient($this->client)
            ->create([
                'status' => 'pending_payment',
                'approved_budget' => 100000,
            ]);

        // First payment should be downpayment
        $currentDue = $request->getCurrentPaymentAmountDue();
        $this->assertEquals(30000, $currentDue);

        // Simulate downpayment
        $request->update([
            'downpayment_paid' => true,
            'downpayment_paid_at' => now(),
        ]);

        // Next payment should be remaining balance
        $nextDue = $request->getCurrentPaymentAmountDue();
        $this->assertEquals(70000, $nextDue);
    }

    /**
     * Test payment descriptions
     */
    public function test_payment_descriptions(): void
    {
        $fullPayment = ServiceRequest::factory()->create([
            'payment_type' => 'full_payment',
        ]);
        
        $downpayment = ServiceRequest::factory()->create([
            'payment_type' => 'downpayment',
            'downpayment_percentage' => 30,
            'downpayment_paid' => false,
        ]);

        $this->assertEquals('Full Project Payment', $fullPayment->getCurrentPaymentDescription());
        $this->assertStringContains('Downpayment', $downpayment->getCurrentPaymentDescription());
    }

    /**
     * Test cannot pay for other client's request
     */
    public function test_cannot_pay_for_others_request(): void
    {
        $otherClient = User::factory()->client()->create();
        $otherRequest = ServiceRequest::factory()
            ->pendingPayment()
            ->forClient($otherClient)
            ->create();

        $response = $this->actingAs($this->client)
            ->post(route('client.maya.checkout', $otherRequest->id));

        $response->assertStatus(403);
    }

    /**
     * Test payment history
     */
    public function test_can_view_payment_history(): void
    {
        Payment::factory()->count(3)->confirmed()->forServiceRequest($this->serviceRequest)->create();

        $response = $this->actingAs($this->client)
            ->get(route('client.payments.index'));

        $response->assertStatus(200);
    }

    /**
     * Test payment receipt
     */
    public function test_can_view_payment_receipt(): void
    {
        $payment = Payment::factory()->confirmed()->forServiceRequest($this->serviceRequest)->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('client.payments.receipt', $payment->id));

        $response->assertStatus(200);
    }

    /**
     * Helper to check string contains
     */
    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(
            str_contains($haystack, $needle),
            "Failed asserting that '$haystack' contains '$needle'"
        );
    }
}
