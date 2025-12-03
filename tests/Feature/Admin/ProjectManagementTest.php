<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Admin Project Management Tests
 * 
 * Tests admin functionality for managing projects:
 * - Viewing projects list
 * - Creating/editing projects
 * - Assigning adiutors
 * - Updating project status
 */
class ProjectManagementTest extends TestCase
{
    use UseCmsSqlSchema;

    protected User $admin;
    protected User $client;
    protected User $adiutor;

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

        // Create adiutor
        $this->adiutor = User::factory()->create([
            'role' => 'adiutor',
            'status' => 'active',
        ]);
    }

    // =========================================
    // Project List Tests
    // =========================================

    public function test_admin_can_view_projects_list(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.projects.index'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_projects(): void
    {
        $this->actingAs($this->client);

        $response = $this->get(route('admin.projects.index'));

        $this->assertTrue(
            $response->isRedirection() || $response->status() === 403
        );
    }

    public function test_unauthenticated_user_is_redirected(): void
    {
        $response = $this->get(route('admin.projects.index'));

        $response->assertRedirect();
    }

    // =========================================
    // Create Project Tests
    // =========================================

    public function test_admin_can_view_create_project_form(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.projects.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_project(): void
    {
        $this->actingAs($this->admin);

        // Create a service request first
        $serviceRequest = ServiceRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'approved',
        ]);

        $response = $this->post(route('admin.projects.store'), [
            'service_request_id' => $serviceRequest->id,
            'name' => 'New Project',
            'description' => 'Project description',
            'client_id' => $this->client->id,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'budget' => 100000,
            'priority' => 'high',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'name' => 'New Project',
            'client_id' => $this->client->id,
        ]);
    }

    public function test_project_creation_requires_name(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.projects.store'), [
            'description' => 'Project description',
            'client_id' => $this->client->id,
        ]);

        $response->assertSessionHasErrors('name');
    }

    // =========================================
    // View Project Details Tests
    // =========================================

    public function test_admin_can_view_project_details(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->get(route('admin.projects.show', $project->id));

        $response->assertStatus(200);
    }

    // =========================================
    // Edit Project Tests
    // =========================================

    public function test_admin_can_view_edit_project_form(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->get(route('admin.projects.edit', $project->id));

        $response->assertStatus(200);
    }

    public function test_admin_can_update_project(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
            'name' => 'Original Name',
        ]);

        $response = $this->put(route('admin.projects.update', $project->id), [
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'client_id' => $this->client->id,
            'priority' => 'medium',
        ]);

        $response->assertRedirect();

        $project->refresh();
        $this->assertEquals('Updated Name', $project->name);
    }

    // =========================================
    // Delete Project Tests
    // =========================================

    public function test_admin_can_delete_project(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->delete(route('admin.projects.destroy', $project->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    // =========================================
    // Assign Adiutor Tests
    // =========================================

    public function test_admin_can_assign_adiutor_to_project(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->post(route('admin.projects.assign-adiutor', $project->id), [
            'adiutor_id' => $this->adiutor->id,
            'role' => 'developer',
            'hourly_rate' => 500,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('project_assignments', [
            'project_id' => $project->id,
            'adiutor_id' => $this->adiutor->id,
        ]);
    }

    public function test_cannot_assign_non_adiutor_to_project(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
        ]);

        $response = $this->post(route('admin.projects.assign-adiutor', $project->id), [
            'adiutor_id' => $this->client->id, // Client, not adiutor
            'role' => 'developer',
            'hourly_rate' => 500,
        ]);

        $response->assertSessionHasErrors('adiutor_id');
    }

    // =========================================
    // Remove Adiutor Tests
    // =========================================

    public function test_admin_can_remove_adiutor_from_project(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
        ]);

        // Assign adiutor first
        DB::table('project_assignments')->insert([
            'project_id' => $project->id,
            'adiutor_id' => $this->adiutor->id,
            'role' => 'developer',
            'status' => 'accepted',
            'hourly_rate' => 500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->delete(route('admin.projects.remove-adiutor', [
            'projectId' => $project->id,
            'adiutorId' => $this->adiutor->id,
        ]));

        $response->assertRedirect();
    }

    // =========================================
    // Update Project Status Tests
    // =========================================

    public function test_admin_can_update_project_status(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'in_progress',
        ]);

        $response = $this->post(route('admin.projects.update-status', $project->id), [
            'status' => 'on_hold',
            'reason' => 'Waiting for client feedback',
        ]);

        $response->assertRedirect();

        $project->refresh();
        $this->assertEquals('on_hold', $project->status);
    }

    // =========================================
    // Complete Project Tests
    // =========================================

    public function test_admin_can_mark_project_complete(): void
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'in_progress',
        ]);

        $response = $this->post(route('admin.projects.complete', $project->id), [
            'notes' => 'Project delivered successfully',
        ]);

        $response->assertRedirect();

        $project->refresh();
        $this->assertEquals('completed', $project->status);
    }

    // =========================================
    // Bulk Actions Tests
    // =========================================

    public function test_admin_can_perform_bulk_action(): void
    {
        $this->actingAs($this->admin);

        $projects = Project::factory()->count(3)->create([
            'client_id' => $this->client->id,
            'status' => 'in_progress',
        ]);

        $response = $this->post(route('admin.projects.bulk-action'), [
            'action' => 'on_hold',
            'project_ids' => $projects->pluck('id')->toArray(),
        ]);

        $response->assertRedirect();
    }
}
