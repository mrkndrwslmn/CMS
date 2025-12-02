<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\Project;
use App\Models\WalletTransaction;
use Tests\TestCase;

class TimeEntryApprovalTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase

    protected User $admin;
    protected User $adiutor;
    protected Project $project;
    protected Task $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->adiutor = User::factory()->adiutor()->create([
            'work_earnings_balance' => 0,
        ]);
        $this->project = Project::factory()->create();
        $this->task = Task::factory()->create([
            'project_id' => $this->project->id,
        ]);
    }

    /**
     * Test approving time entry credits wallet
     */
    public function test_approving_time_entry_credits_wallet(): void
    {
        $timeEntry = TimeEntry::factory()->forAdiutor($this->adiutor)->create([
            'task_id' => $this->task->taskID,
            'project_id' => $this->project->id,
            'duration_minutes' => 120,
            'billable_minutes' => 120,
            'hourly_rate' => 500,
            'calculated_amount' => 1000,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.approve', $timeEntry->id));

        $response->assertSessionHas('success');

        $timeEntry->refresh();
        $this->assertTrue($timeEntry->is_approved);
        $this->assertEquals($this->admin->id, $timeEntry->approved_by);
        $this->assertNotNull($timeEntry->approved_at);

        $this->adiutor->refresh();
        $this->assertEquals(1000, $this->adiutor->work_earnings_balance);

        // Verify wallet transaction was created
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->adiutor->id,
            'amount' => 1000,
            'transaction_type' => WalletTransaction::TYPE_WORK_EARNED,
            'source_type' => WalletTransaction::SOURCE_TIME_ENTRY,
            'source_id' => $timeEntry->id,
        ]);
    }

    /**
     * Test approving with adjustment uses adjusted hours
     */
    public function test_approving_with_adjustment_uses_adjusted_hours(): void
    {
        $timeEntry = TimeEntry::factory()->forAdiutor($this->adiutor)->create([
            'duration_minutes' => 300, // 5 hours
            'billable_minutes' => 300,
            'hourly_rate' => 100,
            'calculated_amount' => 500,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.approve', $timeEntry->id), [
                'adjust' => true,
                'adjusted_hours' => 3,
                'adjustment_reason' => 'Reduced due to break time not logged',
            ]);

        $response->assertSessionHas('success');

        $timeEntry->refresh();
        $this->assertTrue($timeEntry->is_approved);
        $this->assertTrue($timeEntry->admin_adjusted);
        $this->assertEquals(180, $timeEntry->duration_minutes); // 3 hours
        $this->assertEquals(300, $timeEntry->calculated_amount); // 3 * 100
        $this->assertNotNull($timeEntry->adjustment_reason);

        $this->adiutor->refresh();
        $this->assertEquals(300, $this->adiutor->work_earnings_balance); // Adjusted amount
    }

    /**
     * Test cannot approve already approved entry
     */
    public function test_cannot_approve_already_approved_entry(): void
    {
        $timeEntry = TimeEntry::factory()->approved()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.approve', $timeEntry->id));

        $response->assertSessionHas('error');
    }

    /**
     * Test adjustment requires reason
     */
    public function test_adjustment_requires_reason(): void
    {
        $timeEntry = TimeEntry::factory()->forAdiutor($this->adiutor)->create([
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.approve', $timeEntry->id), [
                'adjust' => true,
                'adjusted_hours' => 3,
                // Missing adjustment_reason
            ]);

        $response->assertSessionHasErrors('adjustment_reason');
    }

    /**
     * Test bulk approve time entries
     */
    public function test_can_bulk_approve_time_entries(): void
    {
        $entries = TimeEntry::factory()->count(5)->forAdiutor($this->adiutor)->create([
            'is_approved' => false,
        ]);

        $entryIds = $entries->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.bulk-approve'), [
                'time_entry_ids' => $entryIds,
            ]);

        $response->assertSessionHas('success');

        foreach ($entries as $entry) {
            $entry->refresh();
            $this->assertTrue($entry->is_approved);
        }
    }

    /**
     * Test reject time entry
     */
    public function test_can_reject_time_entry(): void
    {
        $timeEntry = TimeEntry::factory()->forAdiutor($this->adiutor)->create([
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.reject', $timeEntry->id), [
                'rejection_reason' => 'Work not verified',
            ]);

        $response->assertSessionHas('success');

        // Entry should be deleted
        $this->assertDatabaseMissing('time_entries', ['id' => $timeEntry->id]);
    }

    /**
     * Test cannot reject approved entry
     */
    public function test_cannot_reject_approved_entry(): void
    {
        $timeEntry = TimeEntry::factory()->approved()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.reject', $timeEntry->id), [
                'rejection_reason' => 'Test rejection',
            ]);

        $response->assertSessionHas('error');
    }

    /**
     * Test get time entry details for modal
     */
    public function test_can_get_time_entry_details(): void
    {
        $timeEntry = TimeEntry::factory()->forAdiutor($this->adiutor)->create([
            'task_id' => $this->task->taskID,
            'duration_minutes' => 120,
            'hourly_rate' => 500,
            'calculated_amount' => 1000,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.time-entries.details', $timeEntry->id));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $timeEntry->id,
            'duration_minutes' => 120,
            'hourly_rate' => 500,
            'calculated_amount' => 1000,
        ]);
    }

    /**
     * Test non-admin cannot approve time entries
     */
    public function test_non_admin_cannot_approve_entries(): void
    {
        $client = User::factory()->client()->create();
        $timeEntry = TimeEntry::factory()->create(['is_approved' => false]);

        $response = $this->actingAs($client)
            ->post(route('admin.time-entries.approve', $timeEntry->id));

        $response->assertStatus(403);
    }

    /**
     * Test approving zero amount entry
     */
    public function test_approving_zero_amount_entry_does_not_credit_wallet(): void
    {
        $timeEntry = TimeEntry::factory()->forAdiutor($this->adiutor)->create([
            'calculated_amount' => 0,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.time-entries.approve', $timeEntry->id));

        $timeEntry->refresh();
        $this->assertTrue($timeEntry->is_approved);

        $this->adiutor->refresh();
        $this->assertEquals(0, $this->adiutor->work_earnings_balance);

        // No wallet transaction should be created for zero amount
        $this->assertDatabaseMissing('wallet_transactions', [
            'user_id' => $this->adiutor->id,
            'source_id' => $timeEntry->id,
        ]);
    }
}
