<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Payout;
use App\Models\TimeEntry;
use App\Mail\PayoutPaidMail;
use App\Mail\PayoutRejectedMail;
use App\Notifications\PayoutPaidNotification;
use App\Notifications\PayoutRejectedNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PayoutManagementTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase

    protected User $admin;
    protected User $adiutor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->adiutor = User::factory()->adiutor()->create([
            'work_earnings_balance' => 10000,
        ]);
    }

    /**
     * Test admin can view payouts index
     */
    public function test_admin_can_view_payouts_index(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payouts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.payouts.index');
    }

    /**
     * Test non-admin cannot access payouts
     */
    public function test_non_admin_cannot_access_payouts(): void
    {
        $client = User::factory()->client()->create();

        $response = $this->actingAs($client)
            ->get(route('admin.payouts.index'));

        $response->assertStatus(403);
    }

    /**
     * Test adiutor cannot access payouts management
     */
    public function test_adiutor_cannot_access_payouts_management(): void
    {
        $response = $this->actingAs($this->adiutor)
            ->get(route('admin.payouts.index'));

        $response->assertStatus(403);
    }

    /**
     * Test admin can view payout details
     */
    public function test_admin_can_view_payout_details(): void
    {
        $payout = Payout::factory()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.payouts.show', $payout->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.payouts.show');
        $response->assertViewHas('payout');
    }

    /**
     * Test admin can mark payout as processing
     */
    public function test_admin_can_mark_payout_as_processing(): void
    {
        $payout = Payout::factory()->pending()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.mark-processing', $payout->id));

        $response->assertRedirect();

        $payout->refresh();
        $this->assertEquals('processing', $payout->status);
        $this->assertEquals($this->admin->id, $payout->processed_by);
    }

    /**
     * Test cannot mark non-pending payout as processing
     */
    public function test_cannot_mark_non_pending_payout_as_processing(): void
    {
        $payout = Payout::factory()->completed()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.mark-processing', $payout->id));

        $response->assertSessionHasErrors('error');
    }

    /**
     * Test admin can complete payout
     */
    public function test_admin_can_complete_payout(): void
    {
        Mail::fake();
        Notification::fake();

        $payout = Payout::factory()->pending()->forAdiutor($this->adiutor)->create([
            'amount' => 5000,
        ]);

        $timeEntry = TimeEntry::factory()->approved()->forAdiutor($this->adiutor)->create([
            'payout_id' => $payout->id,
            'is_paid' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.complete', $payout->id), [
                'reference_number' => 'REF-123456',
                'notes' => 'Payment processed via bank transfer',
            ]);

        $response->assertRedirect(route('admin.payouts.show', $payout->id));
        $response->assertSessionHas('success');

        $payout->refresh();
        $this->assertEquals('completed', $payout->status);
        $this->assertEquals('REF-123456', $payout->reference_number);
        $this->assertNotNull($payout->completed_at);

        $timeEntry->refresh();
        $this->assertTrue($timeEntry->is_paid);

        // Verify notifications were sent
        Mail::assertSent(PayoutPaidMail::class, function ($mail) {
            return $mail->hasTo($this->adiutor->email);
        });

        Notification::assertSentTo($this->adiutor, PayoutPaidNotification::class);
    }

    /**
     * Test completing payout with proof of payment upload
     */
    public function test_can_complete_payout_with_proof_upload(): void
    {
        Storage::fake('public');
        Mail::fake();
        Notification::fake();

        $payout = Payout::factory()->pending()->forAdiutor($this->adiutor)->create();

        $file = UploadedFile::fake()->image('proof.jpg');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.complete', $payout->id), [
                'reference_number' => 'REF-789',
                'proof_of_payment' => $file,
            ]);

        $response->assertRedirect();

        $payout->refresh();
        $this->assertNotNull($payout->proof_of_payment);
        
        Storage::disk('public')->assertExists($payout->proof_of_payment);
    }

    /**
     * Test cannot complete already completed payout
     */
    public function test_cannot_complete_already_completed_payout(): void
    {
        $payout = Payout::factory()->completed()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.complete', $payout->id), [
                'reference_number' => 'REF-123456',
            ]);

        $response->assertSessionHasErrors('error');
    }

    /**
     * Test completing payout requires reference number
     */
    public function test_completing_payout_requires_reference_number(): void
    {
        $payout = Payout::factory()->pending()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.complete', $payout->id), [
                // Missing reference_number
            ]);

        $response->assertSessionHasErrors('reference_number');
    }

    /**
     * Test admin can cancel pending payout
     */
    public function test_admin_can_cancel_pending_payout(): void
    {
        Mail::fake();
        Notification::fake();

        $payout = Payout::factory()->pending()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.cancel', $payout->id), [
                'reason' => 'Invalid bank details provided',
            ]);

        $response->assertRedirect(route('admin.payouts.show', $payout->id));
        $response->assertSessionHas('success');

        $payout->refresh();
        $this->assertEquals('cancelled', $payout->status);

        // Verify rejection notification sent
        Mail::assertSent(PayoutRejectedMail::class);
        Notification::assertSentTo($this->adiutor, PayoutRejectedNotification::class);
    }

    /**
     * Test cannot cancel completed payout
     */
    public function test_cannot_cancel_completed_payout(): void
    {
        $payout = Payout::factory()->completed()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.cancel', $payout->id), [
                'reason' => 'Test cancellation',
            ]);

        $response->assertSessionHasErrors('error');
    }

    /**
     * Test cancelling payout requires reason
     */
    public function test_cancelling_payout_requires_reason(): void
    {
        $payout = Payout::factory()->pending()->forAdiutor($this->adiutor)->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.payouts.cancel', $payout->id), [
                // Missing reason
            ]);

        $response->assertSessionHasErrors('reason');
    }

    /**
     * Test payouts can be filtered by status
     */
    public function test_payouts_can_be_filtered_by_status(): void
    {
        Payout::factory()->pending()->forAdiutor($this->adiutor)->count(3)->create();
        Payout::factory()->completed()->forAdiutor($this->adiutor)->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.payouts.index', ['status' => 'pending']));

        $response->assertStatus(200);
        // The view should show filtered payouts
    }

    /**
     * Test payouts can be filtered by date range
     */
    public function test_payouts_can_be_filtered_by_date(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payouts.index', [
                'date_from' => now()->subMonth()->format('Y-m-d'),
                'date_to' => now()->format('Y-m-d'),
            ]));

        $response->assertStatus(200);
    }

    /**
     * Test admin can view adiutor earnings
     */
    public function test_admin_can_view_adiutor_earnings(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.payouts.adiutor-earnings', $this->adiutor->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.payouts.adiutor-earnings');
    }

    /**
     * Test admin can export payouts
     */
    public function test_admin_can_export_payouts(): void
    {
        Payout::factory()->forAdiutor($this->adiutor)->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.payouts.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /**
     * Test Firebase notification is sent on payout completion
     */
    public function test_firebase_notification_sent_on_completion(): void
    {
        Mail::fake();
        Notification::fake();

        $this->adiutor->update(['fcm_token' => 'test_fcm_token']);

        $payout = Payout::factory()->pending()->forAdiutor($this->adiutor)->create();

        $this->actingAs($this->admin)
            ->post(route('admin.payouts.complete', $payout->id), [
                'reference_number' => 'REF-123',
            ]);

        // Check that fake Firebase service received the notification
        $this->fakeFirebase->assertSentTo($this->adiutor);
    }
}
