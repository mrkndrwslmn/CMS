<?php

namespace Tests\Unit\Models;

use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\ProjectAssignment;
use Tests\TestCase;

class TimeEntryTest extends TestCase
{
    
    /**
     * Test that duration is calculated correctly
     */
    public function test_calculates_duration_correctly(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'start_time' => now()->subHours(2),
            'end_time' => now(),
            'duration_minutes' => 120,
            'billable_minutes' => 120,
            'hourly_rate' => 500,
            'calculated_amount' => 1000,
        ]);

        $this->assertEquals(120, $timeEntry->duration_minutes);
        $this->assertEquals(1000, $timeEntry->calculated_amount);
    }

    /**
     * Test formatted duration output
     */
    public function test_formats_duration_correctly(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'duration_minutes' => 150, // 2h 30m
        ]);

        $this->assertEquals('2h 30m', $timeEntry->getFormattedDuration());
    }

    /**
     * Test formatted duration for less than an hour
     */
    public function test_formats_short_duration_correctly(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'duration_minutes' => 45, // 45 minutes
        ]);

        $this->assertEquals('0h 45m', $timeEntry->getFormattedDuration());
    }

    /**
     * Test formatted amount output
     */
    public function test_formats_amount_correctly(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'calculated_amount' => 1250.50,
        ]);

        $this->assertEquals('₱1,250.50', $timeEntry->getFormattedAmount());
    }

    /**
     * Test running timer (no end time) returns "Running..."
     */
    public function test_running_timer_shows_running_status(): void
    {
        $timeEntry = TimeEntry::factory()->running()->create();

        $this->assertNull($timeEntry->end_time);
        $this->assertEquals('Running...', $timeEntry->getFormattedDuration());
    }

    /**
     * Test billable check
     */
    public function test_is_billable_when_approved_and_not_paid(): void
    {
        $admin = User::factory()->admin()->create();
        $timeEntry = TimeEntry::factory()->approved()->create();

        $this->assertTrue($timeEntry->isBillable());
    }

    /**
     * Test not billable when paid
     */
    public function test_is_not_billable_when_paid(): void
    {
        $timeEntry = TimeEntry::factory()->paid()->create();

        $this->assertFalse($timeEntry->isBillable());
    }

    /**
     * Test not billable when not approved
     */
    public function test_is_not_billable_when_not_approved(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'is_approved' => false,
            'is_paid' => false,
        ]);

        $this->assertFalse($timeEntry->isBillable());
    }

    /**
     * Test capped time entry has correct billable minutes
     */
    public function test_capped_entry_respects_max_hours(): void
    {
        $timeEntry = TimeEntry::factory()->capped()->create();

        $this->assertTrue($timeEntry->wasCapped());
        $this->assertEquals(480, $timeEntry->billable_minutes); // 8 hours max
        $this->assertGreaterThan(0, $timeEntry->non_billable_minutes);
    }

    /**
     * Test billable hours calculation
     */
    public function test_calculates_billable_hours(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'billable_minutes' => 180, // 3 hours
        ]);

        $this->assertEquals(3.0, $timeEntry->getBillableHours());
    }

    /**
     * Test non-billable hours calculation
     */
    public function test_calculates_non_billable_hours(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'non_billable_minutes' => 60, // 1 hour
        ]);

        $this->assertEquals(1.0, $timeEntry->getNonBillableHours());
    }

    /**
     * Test billable amount calculation
     */
    public function test_calculates_billable_amount(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'billable_minutes' => 120, // 2 hours
            'hourly_rate' => 500,
        ]);

        $this->assertEquals(1000.00, $timeEntry->calculateBillableAmount());
    }

    /**
     * Test applying adjustment
     */
    public function test_can_apply_adjustment(): void
    {
        $admin = User::factory()->admin()->create();
        $timeEntry = TimeEntry::factory()->create([
            'duration_minutes' => 300, // 5 hours
            'calculated_amount' => 2500,
            'hourly_rate' => 500,
        ]);

        $timeEntry->applyAdjustment(3.0, 'Break time not logged', $admin->id);

        $timeEntry->refresh();

        $this->assertTrue($timeEntry->wasAdjusted());
        $this->assertEquals(180, $timeEntry->duration_minutes); // 3 hours
        $this->assertEquals(1500, $timeEntry->calculated_amount);
        $this->assertEquals(300, $timeEntry->original_duration_minutes);
        $this->assertEquals(2500, $timeEntry->original_calculated_amount);
        $this->assertEquals('Break time not logged', $timeEntry->adjustment_reason);
        $this->assertEquals($admin->id, $timeEntry->adjusted_by);
    }

    /**
     * Test adjustment difference calculation
     */
    public function test_calculates_adjustment_difference(): void
    {
        $timeEntry = TimeEntry::factory()->adjusted()->create();

        $this->assertLessThan(0, $timeEntry->getAdjustmentDifferenceMinutes());
        $this->assertLessThan(0, $timeEntry->getAdjustmentDifferenceAmount());
    }

    /**
     * Test approve method
     */
    public function test_can_approve_time_entry(): void
    {
        $admin = User::factory()->admin()->create();
        $timeEntry = TimeEntry::factory()->create([
            'is_approved' => false,
        ]);

        $timeEntry->approve($admin->id);

        $timeEntry->refresh();

        $this->assertTrue($timeEntry->is_approved);
        $this->assertEquals($admin->id, $timeEntry->approved_by);
        $this->assertNotNull($timeEntry->approved_at);
    }

    /**
     * Test scope for approved entries
     */
    public function test_scope_approved_returns_only_approved(): void
    {
        TimeEntry::factory()->count(3)->approved()->create();
        TimeEntry::factory()->count(2)->create(['is_approved' => false]);

        $approved = TimeEntry::approved()->count();

        $this->assertEquals(3, $approved);
    }

    /**
     * Test scope for pending entries
     */
    public function test_scope_pending_returns_only_pending(): void
    {
        TimeEntry::factory()->count(2)->approved()->create();
        TimeEntry::factory()->count(4)->create(['is_approved' => false]);

        $pending = TimeEntry::pending()->count();

        $this->assertEquals(4, $pending);
    }

    /**
     * Test scope for active (running) entries
     */
    public function test_scope_active_returns_running_entries(): void
    {
        TimeEntry::factory()->count(2)->running()->create();
        TimeEntry::factory()->count(3)->create(); // Completed

        $active = TimeEntry::active()->count();

        $this->assertEquals(2, $active);
    }

    /**
     * Test scope for completed entries
     */
    public function test_scope_completed_returns_completed_entries(): void
    {
        TimeEntry::factory()->count(2)->running()->create();
        TimeEntry::factory()->count(5)->create(); // Completed

        $completed = TimeEntry::completed()->count();

        $this->assertEquals(5, $completed);
    }

    /**
     * Test scope for capped entries
     */
    public function test_scope_capped_returns_capped_entries(): void
    {
        TimeEntry::factory()->count(2)->capped()->create();
        TimeEntry::factory()->count(3)->create(['is_capped' => false]);

        $capped = TimeEntry::capped()->count();

        $this->assertEquals(2, $capped);
    }

    /**
     * Test duration hours attribute
     */
    public function test_duration_hours_attribute(): void
    {
        $timeEntry = TimeEntry::factory()->create([
            'duration_minutes' => 150, // 2.5 hours
        ]);

        $this->assertEquals(2.5, $timeEntry->duration_hours);
    }

    /**
     * Test relationship to adiutor
     */
    public function test_belongs_to_adiutor(): void
    {
        $adiutor = User::factory()->adiutor()->create();
        $timeEntry = TimeEntry::factory()->forAdiutor($adiutor)->create();

        $this->assertEquals($adiutor->id, $timeEntry->adiutor->id);
    }

    /**
     * Test relationship to project
     */
    public function test_belongs_to_project(): void
    {
        $project = Project::factory()->create();
        $timeEntry = TimeEntry::factory()->forProject($project)->create();

        $this->assertEquals($project->id, $timeEntry->project->id);
    }
}
