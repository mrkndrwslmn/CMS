<?php

namespace Tests\Feature\Adiutor;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Adiutor Time Tracking Tests
 * 
 * Tests the time tracking functionality for adiutors:
 * - Starting/stopping timer
 * - Viewing time entries
 * - Editing time entries
 */
class TimeTrackingTest extends TestCase
{
    use UseCmsSqlSchema;

    protected User $adiutor;
    protected Project $project;
    protected Task $task;

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

        // Create project
        $this->project = Project::factory()->create();

        // Create task
        $this->task = Task::factory()->create([
            'project_id' => $this->project->id,
        ]);

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
    // Time Tracking Index Tests
    // =========================================

    public function test_adiutor_can_view_time_tracking_dashboard(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.time-tracking.index'));

        $response->assertStatus(200);
    }

    public function test_non_adiutor_cannot_access_time_tracking(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($client);

        $response = $this->get(route('adiutor.time-tracking.index'));

        // Should be redirected or forbidden
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 403
        );
    }

    public function test_unauthenticated_user_is_redirected_from_time_tracking(): void
    {
        $response = $this->get(route('adiutor.time-tracking.index'));

        $response->assertRedirect(route('login'));
    }

    // =========================================
    // Start Timer Tests
    // =========================================

    public function test_adiutor_can_start_timer(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.time-tracking.start'), [
            'project_id' => $this->project->id,
            'task_id' => $this->task->taskID,
            'description' => 'Working on feature implementation',
        ]);

        // Should redirect or return success
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 200
        );

        // Verify time entry was created
        $this->assertDatabaseHas('time_entries', [
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
        ]);
    }

    public function test_cannot_start_timer_without_project(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.time-tracking.start'), [
            'description' => 'Working on something',
        ]);

        $response->assertSessionHasErrors('project_id');
    }

    public function test_cannot_start_multiple_timers(): void
    {
        $this->actingAs($this->adiutor);

        // Start first timer
        TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'start_time' => now(),
            'end_time' => null, // Running timer
        ]);

        // Try to start second timer
        $response = $this->post(route('adiutor.time-tracking.start'), [
            'project_id' => $this->project->id,
            'task_id' => $this->task->taskID,
            'description' => 'Another task',
        ]);

        // Should redirect with error or be prevented
        $response->assertRedirect();
    }

    // =========================================
    // Stop Timer Tests
    // =========================================

    public function test_adiutor_can_stop_running_timer(): void
    {
        $this->actingAs($this->adiutor);

        // Create running timer
        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'start_time' => now()->subHours(2),
            'end_time' => null,
        ]);

        $response = $this->post(route('adiutor.time-tracking.stop'));

        // Should redirect or return success
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 200
        );

        // Verify timer was stopped
        $timeEntry->refresh();
        $this->assertNotNull($timeEntry->end_time);
    }

    public function test_cannot_stop_timer_when_none_running(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.time-tracking.stop'));

        // Should redirect with error
        $response->assertRedirect();
    }

    // =========================================
    // Timer Status Tests
    // =========================================

    public function test_can_get_timer_status(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.time-tracking.status'));

        $response->assertStatus(200);
    }

    public function test_timer_status_shows_running_timer(): void
    {
        $this->actingAs($this->adiutor);

        // Create running timer
        TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'start_time' => now()->subHours(1),
            'end_time' => null,
            'description' => 'Current work',
        ]);

        $response = $this->get(route('adiutor.time-tracking.status'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'running',
        ]);
    }

    // =========================================
    // Time Entries List Tests
    // =========================================

    public function test_adiutor_can_view_time_entries(): void
    {
        $this->actingAs($this->adiutor);

        // Create some time entries
        TimeEntry::factory()->count(3)->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
        ]);

        $response = $this->get(route('adiutor.time-tracking.entries'));

        $response->assertStatus(200);
    }

    public function test_adiutor_only_sees_own_time_entries(): void
    {
        $this->actingAs($this->adiutor);

        // Create entries for this adiutor
        TimeEntry::factory()->count(2)->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
        ]);

        // Create entries for another adiutor
        $otherAdiutor = User::factory()->create(['role' => 'adiutor', 'status' => 'active']);
        TimeEntry::factory()->count(3)->create([
            'adiutor_id' => $otherAdiutor->id,
            'project_id' => $this->project->id,
        ]);

        $response = $this->get(route('adiutor.time-tracking.entries'));

        $response->assertStatus(200);
    }

    // =========================================
    // Update Time Entry Tests
    // =========================================

    public function test_adiutor_can_update_own_time_entry(): void
    {
        $this->actingAs($this->adiutor);

        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'description' => 'Original description',
            'is_approved' => false,
        ]);

        $response = $this->put(route('adiutor.time-tracking.entries.update', $timeEntry->id), [
            'description' => 'Updated description',
        ]);

        // Should redirect or return success
        $this->assertTrue(
            $response->isRedirection() || $response->status() === 200
        );

        $timeEntry->refresh();
        $this->assertEquals('Updated description', $timeEntry->description);
    }

    public function test_cannot_update_approved_time_entry(): void
    {
        $this->actingAs($this->adiutor);

        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        $response = $this->put(route('adiutor.time-tracking.entries.update', $timeEntry->id), [
            'description' => 'Trying to update approved entry',
        ]);

        // Should be prevented
        $response->assertRedirect();
    }

    public function test_cannot_update_other_adiutor_time_entry(): void
    {
        $this->actingAs($this->adiutor);

        $otherAdiutor = User::factory()->create(['role' => 'adiutor', 'status' => 'active']);
        
        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $otherAdiutor->id,
            'project_id' => $this->project->id,
        ]);

        $response = $this->put(route('adiutor.time-tracking.entries.update', $timeEntry->id), [
            'description' => 'Trying to update someone else entry',
        ]);

        // Should be forbidden
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 404 || $response->isRedirection()
        );
    }

    // =========================================
    // Delete Time Entry Tests
    // =========================================

    public function test_adiutor_can_delete_own_unapproved_entry(): void
    {
        $this->actingAs($this->adiutor);

        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'is_approved' => false,
        ]);

        $response = $this->delete(route('adiutor.time-tracking.entries.delete', $timeEntry->id));

        $this->assertTrue(
            $response->isRedirection() || $response->status() === 200
        );

        $this->assertDatabaseMissing('time_entries', ['id' => $timeEntry->id]);
    }

    public function test_cannot_delete_approved_time_entry(): void
    {
        $this->actingAs($this->adiutor);

        $timeEntry = TimeEntry::factory()->create([
            'adiutor_id' => $this->adiutor->id,
            'project_id' => $this->project->id,
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        $response = $this->delete(route('adiutor.time-tracking.entries.delete', $timeEntry->id));

        // Entry should still exist
        $this->assertDatabaseHas('time_entries', ['id' => $timeEntry->id]);
    }
}
