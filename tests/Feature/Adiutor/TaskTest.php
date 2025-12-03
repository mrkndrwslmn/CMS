<?php

namespace Tests\Feature\Adiutor;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\UseCmsSqlSchema;

/**
 * Adiutor Task Tests
 * 
 * Tests the task management functionality for adiutors:
 * - Viewing assigned tasks
 * - Updating task status
 * - Marking tasks complete
 * - Adding notes and files
 */
class TaskTest extends TestCase
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

        // Create task assigned to adiutor
        $this->task = Task::factory()->create([
            'project_id' => $this->project->id,
            'assigned_to' => $this->adiutor->id,
            'status' => 'in_progress',
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
    // Task List Tests
    // =========================================

    public function test_adiutor_can_view_tasks_list(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.tasks.index'));

        $response->assertStatus(200);
    }

    public function test_non_adiutor_cannot_access_adiutor_tasks(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($client);

        $response = $this->get(route('adiutor.tasks.index'));

        $this->assertTrue(
            $response->isRedirection() || $response->status() === 403
        );
    }

    // =========================================
    // Task Details Tests
    // =========================================

    public function test_adiutor_can_view_assigned_task(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->get(route('adiutor.tasks.show', $this->task->taskID));

        $response->assertStatus(200);
    }

    public function test_adiutor_cannot_view_unassigned_task(): void
    {
        $this->actingAs($this->adiutor);

        // Create task assigned to different adiutor
        $otherAdiutor = User::factory()->create(['role' => 'adiutor', 'status' => 'active']);
        $otherTask = Task::factory()->create([
            'project_id' => $this->project->id,
            'assigned_to' => $otherAdiutor->id,
        ]);

        $response = $this->get(route('adiutor.tasks.show', $otherTask->taskID));

        $this->assertTrue(
            $response->status() === 403 || $response->status() === 404 || $response->isRedirection()
        );
    }

    // =========================================
    // Update Task Status Tests
    // =========================================

    public function test_adiutor_can_update_task_status(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.update-status', $this->task->taskID), [
            'status' => 'in_progress',
        ]);

        $response->assertRedirect();

        $this->task->refresh();
        $this->assertEquals('in_progress', $this->task->status);
    }

    public function test_status_must_be_valid(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.update-status', $this->task->taskID), [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    }

    // =========================================
    // Mark Task Complete Tests
    // =========================================

    public function test_adiutor_can_mark_task_complete(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.complete', $this->task->taskID), [
            'notes' => 'Task completed successfully',
        ]);

        $response->assertRedirect();

        $this->task->refresh();
        $this->assertEquals('completed', $this->task->status);
    }

    public function test_cannot_mark_already_completed_task_complete(): void
    {
        $this->task->update(['status' => 'completed']);
        
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.complete', $this->task->taskID), [
            'notes' => 'Trying to complete again',
        ]);

        // Should redirect with error
        $response->assertRedirect();
    }

    // =========================================
    // Add Note Tests
    // =========================================

    public function test_adiutor_can_add_note_to_task(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.add-note', $this->task->taskID), [
            'note' => 'This is a progress note',
        ]);

        $response->assertRedirect();
    }

    public function test_note_cannot_be_empty(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.add-note', $this->task->taskID), [
            'note' => '',
        ]);

        $response->assertSessionHasErrors('note');
    }

    // =========================================
    // Upload File Tests
    // =========================================

    public function test_adiutor_can_upload_file_to_task(): void
    {
        Storage::fake('local');
        
        $this->actingAs($this->adiutor);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post(route('adiutor.tasks.upload-file', $this->task->taskID), [
            'file' => $file,
            'description' => 'Project document',
        ]);

        $response->assertRedirect();
    }

    public function test_file_size_is_limited(): void
    {
        Storage::fake('local');
        
        $this->actingAs($this->adiutor);

        // Create a file larger than allowed (assuming 10MB limit)
        $file = UploadedFile::fake()->create('large_file.pdf', 20000); // 20MB

        $response = $this->post(route('adiutor.tasks.upload-file', $this->task->taskID), [
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
    }

    // =========================================
    // Update Task Progress Tests
    // =========================================

    public function test_adiutor_can_update_task_progress(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.update-progress', $this->task->taskID), [
            'progress' => 75,
        ]);

        $response->assertRedirect();

        $this->task->refresh();
        $this->assertEquals(75, $this->task->progress);
    }

    public function test_progress_must_be_between_0_and_100(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.update-progress', $this->task->taskID), [
            'progress' => 150,
        ]);

        $response->assertSessionHasErrors('progress');
    }

    // =========================================
    // Budget Change Request Tests
    // =========================================

    public function test_adiutor_can_request_budget_change(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.request-budget-change', $this->task->taskID), [
            'new_budget' => 10000,
            'reason' => 'Additional requirements discovered',
        ]);

        $response->assertRedirect();
    }

    public function test_budget_change_requires_reason(): void
    {
        $this->actingAs($this->adiutor);

        $response = $this->post(route('adiutor.tasks.request-budget-change', $this->task->taskID), [
            'new_budget' => 10000,
            // No reason provided
        ]);

        $response->assertSessionHasErrors('reason');
    }
}
