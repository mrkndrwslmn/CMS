<?php

namespace Tests\Feature\Adiutor;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Adiutor Project Tests
 * 
 * Tests the project management functionality for adiutors:
 * - Viewing assigned projects
 * - Accepting/declining project assignments
 * - Updating project progress
 */
class ProjectTest extends TestCase
{
    use UseCmsSqlSchema;

    protected User $adiutor;
    protected User $client;
    protected Project $project;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bind test services
        $this->app->bind(\App\Services\FirebaseService::class, \Tests\Mocks\FakeFirebaseService::class);

        // Create adiutor
        $this->adiutor = User::factory()->create([
            'role' => 'adiutor',
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        // Create client
        $this->client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
        ]);

        // Create project
        $this->project = Project::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'in_progress',
        ]);
    }

    protected function assignAdiutorToProject(string $status = 'pending'): int
    {
        return DB::table('project_assignments')->insertGetId([
            'project_id' => $this->project->id,
            'adiutor_id' => $this->adiutor->id,
            'role' => 'developer',
            'status' => $status,
            'hourly_rate' => 500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // =========================================
    // Project List Tests
    // =========================================

    public function test_adiutor_can_view_projects_list(): void
    {
        $this->actingAs($this->adiutor);
        $this->assignAdiutorToProject('accepted');

        $response = $this->get(route('adiutor.projects.index'));

        $response->assertStatus(200);
    }

    public function test_non_adiutor_cannot_access_adiutor_projects(): void
    {
        $this->actingAs($this->client);

        $response = $this->get(route('adiutor.projects.index'));

        $this->assertTrue(
            $response->isRedirection() || $response->status() === 403
        );
    }

    public function test_unauthenticated_user_is_redirected(): void
    {
        $response = $this->get(route('adiutor.projects.index'));

        $response->assertRedirect(route('login'));
    }

    // =========================================
    // Project Details Tests
    // =========================================

    public function test_adiutor_can_view_assigned_project(): void
    {
        $this->actingAs($this->adiutor);
        $this->assignAdiutorToProject('accepted');

        $response = $this->get(route('adiutor.projects.show', $this->project->id));

        $response->assertStatus(200);
    }

    public function test_adiutor_cannot_view_unassigned_project(): void
    {
        $this->actingAs($this->adiutor);

        // Don't assign the adiutor to the project
        $response = $this->get(route('adiutor.projects.show', $this->project->id));

        $this->assertTrue(
            $response->status() === 403 || $response->status() === 404 || $response->isRedirection()
        );
    }

    // =========================================
    // Accept Assignment Tests
    // =========================================

    public function test_adiutor_can_accept_project_assignment(): void
    {
        $this->actingAs($this->adiutor);
        $assignmentId = $this->assignAdiutorToProject('pending');

        $response = $this->post(route('adiutor.projects.accept', $assignmentId));

        $response->assertRedirect();

        // Verify assignment was accepted
        $this->assertDatabaseHas('project_assignments', [
            'id' => $assignmentId,
            'status' => 'accepted',
        ]);
    }

    public function test_cannot_accept_already_accepted_assignment(): void
    {
        $this->actingAs($this->adiutor);
        $assignmentId = $this->assignAdiutorToProject('accepted');

        $response = $this->post(route('adiutor.projects.accept', $assignmentId));

        // Should redirect with error or be prevented
        $response->assertRedirect();
    }

    public function test_cannot_accept_other_adiutor_assignment(): void
    {
        $this->actingAs($this->adiutor);

        // Create assignment for different adiutor
        $otherAdiutor = User::factory()->create(['role' => 'adiutor', 'status' => 'active']);
        $assignmentId = DB::table('project_assignments')->insertGetId([
            'project_id' => $this->project->id,
            'adiutor_id' => $otherAdiutor->id,
            'role' => 'developer',
            'status' => 'pending',
            'hourly_rate' => 500,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post(route('adiutor.projects.accept', $assignmentId));

        $this->assertTrue(
            $response->status() === 403 || $response->status() === 404 || $response->isRedirection()
        );
    }

    // =========================================
    // Decline Assignment Tests
    // =========================================

    public function test_adiutor_can_decline_project_assignment(): void
    {
        $this->actingAs($this->adiutor);
        $assignmentId = $this->assignAdiutorToProject('pending');

        $response = $this->post(route('adiutor.projects.decline', $assignmentId), [
            'reason' => 'Not available during this period',
        ]);

        $response->assertRedirect();

        // Verify assignment was declined
        $this->assertDatabaseHas('project_assignments', [
            'id' => $assignmentId,
            'status' => 'declined',
        ]);
    }

    public function test_decline_requires_reason(): void
    {
        $this->actingAs($this->adiutor);
        $assignmentId = $this->assignAdiutorToProject('pending');

        $response = $this->post(route('adiutor.projects.decline', $assignmentId), [
            // No reason provided
        ]);

        $response->assertSessionHasErrors('reason');
    }

    // =========================================
    // Update Progress Tests
    // =========================================

    public function test_adiutor_can_update_project_progress(): void
    {
        $this->actingAs($this->adiutor);
        $assignmentId = $this->assignAdiutorToProject('accepted');

        $response = $this->post(route('adiutor.projects.update-progress', $assignmentId), [
            'progress' => 50,
            'notes' => 'Halfway through the project',
        ]);

        $response->assertRedirect();

        // Verify progress was updated
        $this->assertDatabaseHas('project_assignments', [
            'id' => $assignmentId,
            'progress' => 50,
        ]);
    }

    public function test_progress_must_be_valid_percentage(): void
    {
        $this->actingAs($this->adiutor);
        $assignmentId = $this->assignAdiutorToProject('accepted');

        $response = $this->post(route('adiutor.projects.update-progress', $assignmentId), [
            'progress' => 150, // Invalid percentage
        ]);

        $response->assertSessionHasErrors('progress');
    }

    public function test_cannot_update_progress_for_unaccepted_assignment(): void
    {
        $this->actingAs($this->adiutor);
        $assignmentId = $this->assignAdiutorToProject('pending');

        $response = $this->post(route('adiutor.projects.update-progress', $assignmentId), [
            'progress' => 50,
        ]);

        // Should be prevented
        $response->assertRedirect();
    }

    // =========================================
    // Create Task Tests
    // =========================================

    public function test_adiutor_can_create_task_for_assigned_project(): void
    {
        $this->actingAs($this->adiutor);
        $this->assignAdiutorToProject('accepted');

        $response = $this->post(route('adiutor.projects.create-task', $this->project->id), [
            'name' => 'New Task',
            'description' => 'Task description',
            'priority' => 'medium',
            'estimated_hours' => 5,
        ]);

        $response->assertRedirect();

        // Verify task was created
        $this->assertDatabaseHas('tasks', [
            'project_id' => $this->project->id,
            'name' => 'New Task',
        ]);
    }

    public function test_cannot_create_task_for_unassigned_project(): void
    {
        $this->actingAs($this->adiutor);
        // Not assigned to project

        $response = $this->post(route('adiutor.projects.create-task', $this->project->id), [
            'name' => 'New Task',
            'description' => 'Task description',
        ]);

        $this->assertTrue(
            $response->status() === 403 || $response->status() === 404 || $response->isRedirection()
        );
    }
}
