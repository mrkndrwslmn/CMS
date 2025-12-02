<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\WalletTransaction;
use Tests\TestCase;

class UserWalletTest extends TestCase
{
    // Using UseCmsSqlSchema from TestCase (no migrations needed)

    /**
     * Test add work earnings increases balance
     */
    public function test_add_work_earnings_increases_balance(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 1000,
        ]);

        $transaction = $user->addWorkEarnings(
            500,
            WalletTransaction::SOURCE_TIME_ENTRY,
            1,
            'Test earning'
        );

        $user->refresh();

        $this->assertEquals(1500, $user->work_earnings_balance);
        $this->assertInstanceOf(WalletTransaction::class, $transaction);
        $this->assertEquals(500, $transaction->amount);
        $this->assertEquals(WalletTransaction::TYPE_WORK_EARNED, $transaction->transaction_type);
    }

    /**
     * Test add work earnings creates transaction record
     */
    public function test_add_work_earnings_creates_transaction(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 0,
        ]);

        $user->addWorkEarnings(
            1000,
            WalletTransaction::SOURCE_TIME_ENTRY,
            1,
            'Approved time entry',
            null,
            ['project_id' => 1, 'hours' => 2]
        );

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'amount' => 1000,
            'transaction_type' => WalletTransaction::TYPE_WORK_EARNED,
            'source_type' => WalletTransaction::SOURCE_TIME_ENTRY,
            'wallet_type' => WalletTransaction::WALLET_WORK_EARNINGS,
        ]);
    }

    /**
     * Test transaction tracks balance before and after
     */
    public function test_transaction_tracks_balance_changes(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 5000,
        ]);

        $transaction = $user->addWorkEarnings(
            2000,
            WalletTransaction::SOURCE_TIME_ENTRY,
            1,
            'Test'
        );

        $this->assertEquals(5000, $transaction->balance_before);
        $this->assertEquals(7000, $transaction->balance_after);
    }

    /**
     * Test can withdraw work earnings check
     */
    public function test_can_withdraw_work_earnings_check(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 1000,
        ]);

        // Can withdraw if balance >= amount and amount >= minimum
        $this->assertTrue($user->canWithdrawWorkEarnings(500));
        $this->assertTrue($user->canWithdrawWorkEarnings(1000));
        
        // Cannot withdraw more than balance
        $this->assertFalse($user->canWithdrawWorkEarnings(1500));
    }

    /**
     * Test formatted work earnings attribute
     */
    public function test_formatted_work_earnings_attribute(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 12500.75,
        ]);

        $this->assertEquals('₱12,500.75', $user->formatted_work_earnings);
    }

    /**
     * Test zero balance formatting
     */
    public function test_zero_balance_formatting(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 0,
        ]);

        $this->assertEquals('₱0.00', $user->formatted_work_earnings);
    }

    /**
     * Test wallet transactions relationship
     */
    public function test_has_many_wallet_transactions(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 0,
        ]);

        // Add multiple earnings
        $user->addWorkEarnings(1000, WalletTransaction::SOURCE_TIME_ENTRY, 1, 'Entry 1');
        $user->addWorkEarnings(500, WalletTransaction::SOURCE_TIME_ENTRY, 2, 'Entry 2');
        $user->addWorkEarnings(750, WalletTransaction::SOURCE_TIME_ENTRY, 3, 'Entry 3');

        $this->assertEquals(3, $user->walletTransactions()->count());
    }

    /**
     * Test recent wallet transactions
     */
    public function test_get_recent_wallet_transactions(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 0,
        ]);

        // Add 15 transactions
        for ($i = 1; $i <= 15; $i++) {
            $user->addWorkEarnings(100, WalletTransaction::SOURCE_TIME_ENTRY, $i, "Entry $i");
        }

        $recent = $user->getRecentWalletTransactions(10);

        $this->assertEquals(10, $recent->count());
    }

    /**
     * Test adding earnings with metadata
     */
    public function test_add_earnings_with_metadata(): void
    {
        $user = User::factory()->adiutor()->create([
            'work_earnings_balance' => 0,
        ]);

        $metadata = [
            'project_id' => 5,
            'task_id' => 10,
            'hours' => 4.5,
            'rate' => 500,
        ];

        $transaction = $user->addWorkEarnings(
            2250, // 4.5 hours * 500
            WalletTransaction::SOURCE_TIME_ENTRY,
            1,
            'Work on project',
            null,
            $metadata
        );

        $this->assertEquals($metadata, $transaction->metadata);
    }

    /**
     * Test role checks
     */
    public function test_role_checks(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->client()->create();
        $adiutor = User::factory()->adiutor()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isClient());
        $this->assertFalse($admin->isAdiutor());

        $this->assertFalse($client->isAdmin());
        $this->assertTrue($client->isClient());
        $this->assertFalse($client->isAdiutor());

        $this->assertFalse($adiutor->isAdmin());
        $this->assertFalse($adiutor->isClient());
        $this->assertTrue($adiutor->isAdiutor());
    }

    /**
     * Test active status check
     */
    public function test_is_active_check(): void
    {
        $activeUser = User::factory()->create(['status' => 'active']);
        $inactiveUser = User::factory()->inactive()->create();

        $this->assertTrue($activeUser->isActive());
        $this->assertFalse($inactiveUser->isActive());
    }

    /**
     * Test dashboard route by role
     */
    public function test_returns_correct_dashboard_route_by_role(): void
    {
        $admin = User::factory()->admin()->create();
        $client = User::factory()->client()->create();
        $adiutor = User::factory()->adiutor()->create();

        $this->assertEquals('admin.dashboard', $admin->getDashboardRoute());
        $this->assertEquals('client.dashboard', $client->getDashboardRoute());
        $this->assertEquals('adiutor.dashboard', $adiutor->getDashboardRoute());
    }
}
