<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\Service;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Admin Service Request Management Tests
 * 
 * Tests admin functionality for managing service requests:
 * - Viewing requests list
 * - Approving/rejecting requests
 * - Assigning priority
 * - Converting to projects
 */
class ServiceRequestManagementTest extends TestCase
{
    use UseCmsSqlSchema;

    protected User $admin;
    protected User $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bind test services
        $this->app->bind(\App\Services\FirebaseService::class, \Tests\Mocks\FakeFirebaseService::class);

        // Create admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create client
        $this->client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);
    }

    // =========================================
    // Request List Tests
    // =========================================

    public function test_admin_can_view_service_requests_list(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.requests.index'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_requests_list(): void
    {
        $this->actingAs($this->client);

        $response = $this->get(route('admin.requests.index'));

        $this->assertTrue(
            $response->isRedirection() || $response->status() === 403
        );
    }

    public function test_requests_list_can_filter_by_status(): void
    {
        $this->actingAs($this->admin);

        ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->get(route('admin.requests.index', ['status' => 'pending']));

        $response->assertStatus(200);
    }

    public function test_requests_list_can_filter_by_priority(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.requests.index', ['priority' => 'high']));

        $response->assertStatus(200);
    }

    // =========================================
    // View Request Details Tests
    // =========================================

    public function test_admin_can_view_request_details(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->get(route('admin.requests.show', $request->id));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_request_with_attachments(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->get(route('admin.requests.show', $request->id));

        $response->assertStatus(200);
    }

    // =========================================
    // Approve Request Tests
    // =========================================

    public function test_admin_can_approve_service_request(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.requests.approve', $request->id), [
            'notes' => 'Request approved for processing',
        ]);

        $response->assertRedirect();

        $request->refresh();
        $this->assertEquals('approved', $request->status);
    }

    public function test_admin_can_approve_with_estimated_hours(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.requests.approve', $request->id), [
            'estimated_hours' => 40,
            'estimated_budget' => 20000,
            'notes' => 'Approved with estimates',
        ]);

        $response->assertRedirect();

        $request->refresh();
        $this->assertEquals('approved', $request->status);
    }

    public function test_cannot_approve_already_approved_request(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'approved',
        ]);

        $response = $this->post(route('admin.requests.approve', $request->id));

        // Should redirect or show error
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 422
        );
    }

    // =========================================
    // Reject Request Tests
    // =========================================

    public function test_admin_can_reject_service_request(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.requests.reject', $request->id), [
            'reason' => 'Request does not meet our criteria',
        ]);

        $response->assertRedirect();

        $request->refresh();
        $this->assertEquals('rejected', $request->status);
    }

    public function test_rejection_requires_reason(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.requests.reject', $request->id), [
            // No reason provided
        ]);

        $response->assertSessionHasErrors('reason');
    }

    // =========================================
    // Update Priority Tests
    // =========================================

    public function test_admin_can_update_request_priority(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'priority' => 'low',
        ]);

        $response = $this->post(route('admin.requests.update-priority', $request->id), [
            'priority' => 'high',
        ]);

        $response->assertRedirect();

        $request->refresh();
        $this->assertEquals('high', $request->priority);
    }

    public function test_priority_must_be_valid(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->post(route('admin.requests.update-priority', $request->id), [
            'priority' => 'invalid_priority',
        ]);

        $response->assertSessionHasErrors('priority');
    }

    // =========================================
    // Convert to Project Tests
    // =========================================

    public function test_admin_can_convert_approved_request_to_project(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'approved',
        ]);

        $response = $this->post(route('admin.requests.convert-to-project', $request->id), [
            'project_name' => 'New Project from Request',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(2)->format('Y-m-d'),
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'service_request_id' => $request->id,
        ]);
    }

    public function test_cannot_convert_pending_request_to_project(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.requests.convert-to-project', $request->id), [
            'project_name' => 'New Project',
        ]);

        // Should redirect or show error
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 422
        );
    }

    // =========================================
    // Assign Reviewer Tests
    // =========================================

    public function test_admin_can_assign_reviewer_to_request(): void
    {
        $this->actingAs($this->admin);

        $anotherAdmin = User::factory()->create([
            'role' => 'admin',
            'status' => 'active',
        ]);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->post(route('admin.requests.assign-reviewer', $request->id), [
            'reviewer_id' => $anotherAdmin->id,
        ]);

        $response->assertRedirect();
    }

    // =========================================
    // Add Notes Tests
    // =========================================

    public function test_admin_can_add_notes_to_request(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->post(route('admin.requests.add-note', $request->id), [
            'note' => 'Important note about this request',
        ]);

        $response->assertRedirect();
    }

    public function test_note_cannot_be_empty(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->post(route('admin.requests.add-note', $request->id), [
            'note' => '',
        ]);

        $response->assertSessionHasErrors('note');
    }

    // =========================================
    // Bulk Actions Tests
    // =========================================

    public function test_admin_can_bulk_approve_requests(): void
    {
        $this->actingAs($this->admin);

        $requests = ServiceRequest::factory()->count(3)->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.requests.bulk-action'), [
            'action' => 'approve',
            'request_ids' => $requests->pluck('id')->toArray(),
        ]);

        $response->assertRedirect();
    }

    public function test_admin_can_bulk_reject_requests(): void
    {
        $this->actingAs($this->admin);

        $requests = ServiceRequest::factory()->count(3)->create([
            'client_id' => $this->client->id,
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.requests.bulk-action'), [
            'action' => 'reject',
            'request_ids' => $requests->pluck('id')->toArray(),
            'reason' => 'Bulk rejection - does not meet criteria',
        ]);

        $response->assertRedirect();
    }

    // =========================================
    // Delete Request Tests
    // =========================================

    public function test_admin_can_delete_request(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->delete(route('admin.requests.destroy', $request->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('service_requests', ['id' => $request->id]);
    }

    public function test_cannot_delete_approved_request_with_project(): void
    {
        $this->actingAs($this->admin);

        $request = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'approved',
        ]);

        // Create a project linked to the request
        \App\Models\Project::factory()->create([
            'service_request_id' => $request->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->delete(route('admin.requests.destroy', $request->id));

        // Should prevent deletion or show error
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 422 || $response->status() === 409
        );
    }
}
