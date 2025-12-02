<?php

namespace Tests\Unit\Models;

use App\Models\Payout;
use App\Models\User;
use App\Models\TimeEntry;
use Tests\TestCase;

class PayoutTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase (no migrations needed)

    /**
     * Test payout number generation
     */
    public function test_generates_unique_payout_number(): void
    {
        $payoutNumber1 = Payout::generatePayoutNumber();
        
        Payout::factory()->create(['payout_number' => $payoutNumber1]);
        
        $payoutNumber2 = Payout::generatePayoutNumber();

        $this->assertNotEquals($payoutNumber1, $payoutNumber2);
        $this->assertStringStartsWith('PAYOUT-', $payoutNumber1);
    }

    /**
     * Test payout number format includes year
     */
    public function test_payout_number_includes_year(): void
    {
        $payoutNumber = Payout::generatePayoutNumber();
        $currentYear = date('Y');

        $this->assertStringContains($currentYear, $payoutNumber);
    }

    /**
     * Test formatted amount
     */
    public function test_formats_amount_correctly(): void
    {
        $payout = Payout::factory()->create([
            'amount' => 25000.50,
        ]);

        $this->assertEquals('₱25,000.50', $payout->getFormattedAmount());
    }

    /**
     * Test isPending method
     */
    public function test_is_pending_returns_correct_status(): void
    {
        $pendingPayout = Payout::factory()->pending()->create();
        $completedPayout = Payout::factory()->completed()->create();

        $this->assertTrue($pendingPayout->isPending());
        $this->assertFalse($completedPayout->isPending());
    }

    /**
     * Test isProcessing method
     */
    public function test_is_processing_returns_correct_status(): void
    {
        $processingPayout = Payout::factory()->processing()->create();
        $pendingPayout = Payout::factory()->pending()->create();

        $this->assertTrue($processingPayout->isProcessing());
        $this->assertFalse($pendingPayout->isProcessing());
    }

    /**
     * Test isCompleted method
     */
    public function test_is_completed_returns_correct_status(): void
    {
        $completedPayout = Payout::factory()->completed()->create();
        $pendingPayout = Payout::factory()->pending()->create();

        $this->assertTrue($completedPayout->isCompleted());
        $this->assertFalse($pendingPayout->isCompleted());
    }

    /**
     * Test isCancelled method
     */
    public function test_is_cancelled_returns_correct_status(): void
    {
        $cancelledPayout = Payout::factory()->cancelled()->create();
        $pendingPayout = Payout::factory()->pending()->create();

        $this->assertTrue($cancelledPayout->isCancelled());
        $this->assertFalse($pendingPayout->isCancelled());
    }

    /**
     * Test markAsProcessing
     */
    public function test_can_mark_as_processing(): void
    {
        $admin = User::factory()->admin()->create();
        $payout = Payout::factory()->pending()->create();

        $payout->markAsProcessing($admin->id);
        $payout->refresh();

        $this->assertEquals('processing', $payout->status);
        $this->assertEquals($admin->id, $payout->processed_by);
        $this->assertNotNull($payout->processed_at);
    }

    /**
     * Test markAsCompleted
     */
    public function test_can_mark_as_completed(): void
    {
        $payout = Payout::factory()->processing()->create();

        $payout->markAsCompleted('REF-123456', '/path/to/proof.pdf');
        $payout->refresh();

        $this->assertEquals('completed', $payout->status);
        $this->assertEquals('REF-123456', $payout->reference_number);
        $this->assertNotNull($payout->completed_at);
    }

    /**
     * Test markAsCompleted marks time entries as paid
     */
    public function test_completing_payout_marks_time_entries_as_paid(): void
    {
        $adiutor = User::factory()->adiutor()->create();
        $payout = Payout::factory()->processing()->forAdiutor($adiutor)->create();
        
        $timeEntry = TimeEntry::factory()->approved()->forAdiutor($adiutor)->create([
            'payout_id' => $payout->id,
            'is_paid' => false,
        ]);

        $payout->markAsCompleted('REF-123');
        
        $timeEntry->refresh();

        $this->assertTrue($timeEntry->is_paid);
    }

    /**
     * Test cancel method
     */
    public function test_can_cancel_payout(): void
    {
        $payout = Payout::factory()->pending()->create();

        $payout->cancel('Invalid bank details');
        $payout->refresh();

        $this->assertEquals('cancelled', $payout->status);
        $this->assertStringContains('Invalid bank details', $payout->notes);
    }

    /**
     * Test cancel unlinks time entries
     */
    public function test_cancelling_payout_unlinks_time_entries(): void
    {
        $adiutor = User::factory()->adiutor()->create();
        $payout = Payout::factory()->pending()->forAdiutor($adiutor)->create();
        
        $timeEntry = TimeEntry::factory()->approved()->forAdiutor($adiutor)->create([
            'payout_id' => $payout->id,
            'is_paid' => true,
        ]);

        $payout->cancel('Cancelled by admin');
        
        $timeEntry->refresh();

        $this->assertNull($timeEntry->payout_id);
        $this->assertFalse($timeEntry->is_paid);
    }

    /**
     * Test status badge classes
     */
    public function test_returns_correct_status_badge_class(): void
    {
        $pending = Payout::factory()->pending()->create();
        $processing = Payout::factory()->processing()->create();
        $completed = Payout::factory()->completed()->create();
        $cancelled = Payout::factory()->cancelled()->create();

        $this->assertStringContains('yellow', $pending->getStatusBadgeClass());
        $this->assertStringContains('blue', $processing->getStatusBadgeClass());
        $this->assertStringContains('green', $completed->getStatusBadgeClass());
        $this->assertStringContains('red', $cancelled->getStatusBadgeClass());
    }

    /**
     * Test relationship to adiutor
     */
    public function test_belongs_to_adiutor(): void
    {
        $adiutor = User::factory()->adiutor()->create();
        $payout = Payout::factory()->forAdiutor($adiutor)->create();

        $this->assertEquals($adiutor->id, $payout->adiutor->id);
    }

    /**
     * Test relationship to processed by
     */
    public function test_belongs_to_processed_by(): void
    {
        $admin = User::factory()->admin()->create();
        $payout = Payout::factory()->completed()->create([
            'processed_by' => $admin->id,
        ]);

        $this->assertEquals($admin->id, $payout->processedBy->id);
    }

    /**
     * Test has many time entries
     */
    public function test_has_many_time_entries(): void
    {
        $adiutor = User::factory()->adiutor()->create();
        $payout = Payout::factory()->forAdiutor($adiutor)->create();
        
        TimeEntry::factory()->count(3)->forAdiutor($adiutor)->create([
            'payout_id' => $payout->id,
        ]);

        $this->assertEquals(3, $payout->timeEntries->count());
    }

    /**
     * Test payout details casting to array
     */
    public function test_payout_details_casts_to_array(): void
    {
        $details = [
            'bank_name' => 'BDO',
            'account_number' => '123456789',
            'account_name' => 'John Doe',
        ];

        $payout = Payout::factory()->create([
            'payout_details' => $details,
        ]);

        $this->assertIsArray($payout->payout_details);
        $this->assertEquals('BDO', $payout->payout_details['bank_name']);
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
