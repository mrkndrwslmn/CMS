<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\TimeEntry;
use App\Models\ProjectAssignment;
use App\Models\ProjectMilestone;
use App\Models\Payout;
use App\Models\PayoutItem;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Transaction types
     */
    const TYPE_WORK_EARNED = 'work_earned';
    const TYPE_REFERRAL_EARNED = 'referral_earned';
    const TYPE_WITHDRAWN = 'withdrawn';
    const TYPE_ADJUSTED = 'adjusted';
    const TYPE_REFUNDED = 'refunded';
    const TYPE_PENDING = 'pending';
    const TYPE_CANCELLED = 'cancelled';

    /**
     * Source types
     */
    const SOURCE_TIME_ENTRY = 'time_entry';
    const SOURCE_FIXED_RATE = 'project_fixed_rate';
    const SOURCE_MILESTONE = 'milestone';
    const SOURCE_REFERRAL = 'referral_completion';
    const SOURCE_WITHDRAWAL = 'withdrawal_request';
    const SOURCE_ADMIN_ADJUSTMENT = 'admin_adjustment';
    const SOURCE_REVERSAL = 'reversal';

    /**
     * Wallet types
     */
    const WALLET_WORK = 'work_earnings';
    const WALLET_REFERRAL = 'referral_credits';

    /**
     * Record earnings from an approved time entry
     */
    public function recordTimeEntryEarnings(TimeEntry $timeEntry, ?int $performedBy = null): WalletTransaction
    {
        $user = $timeEntry->adiutor;
        
        $taskTitle = $timeEntry->task->taskTitle ?? 'Task';
        
        return $this->createTransaction([
            'user_id' => $user->id,
            'transaction_type' => self::TYPE_WORK_EARNED,
            'source_type' => self::SOURCE_TIME_ENTRY,
            'source_id' => $timeEntry->id,
            'amount' => $timeEntry->calculated_amount,
            'wallet_type' => self::WALLET_WORK,
            'description' => "Time entry approved: {$taskTitle} ({$timeEntry->duration_minutes} mins)",
            'metadata' => [
                'task_id' => $timeEntry->task_id,
                'project_id' => $timeEntry->project_id,
                'duration_minutes' => $timeEntry->duration_minutes,
                'hourly_rate' => $timeEntry->hourly_rate,
            ],
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    /**
     * Record earnings from an approved fixed rate project
     */
    public function recordFixedRateEarnings(ProjectAssignment $assignment, ?int $performedBy = null): WalletTransaction
    {
        $user = $assignment->adiutor;
        
        return $this->createTransaction([
            'user_id' => $user->id,
            'transaction_type' => self::TYPE_WORK_EARNED,
            'source_type' => self::SOURCE_FIXED_RATE,
            'source_id' => $assignment->id,
            'amount' => $assignment->agreed_rate,
            'wallet_type' => self::WALLET_WORK,
            'description' => "Fixed rate approved: {$assignment->project->title}",
            'metadata' => [
                'project_id' => $assignment->project_id,
                'agreed_rate' => $assignment->agreed_rate,
            ],
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    /**
     * Record earnings from a milestone payment
     */
    public function recordMilestoneEarnings(ProjectMilestone $milestone, User $adiutor, float $amount, ?int $performedBy = null): WalletTransaction
    {
        return $this->createTransaction([
            'user_id' => $adiutor->id,
            'transaction_type' => self::TYPE_WORK_EARNED,
            'source_type' => self::SOURCE_MILESTONE,
            'source_id' => $milestone->id,
            'amount' => $amount,
            'wallet_type' => self::WALLET_WORK,
            'description' => "Milestone payment: {$milestone->phase_name} - {$milestone->project->title}",
            'metadata' => [
                'project_id' => $milestone->project_id,
                'milestone_id' => $milestone->id,
                'phase_name' => $milestone->phase_name,
                'total_milestone_amount' => $milestone->amount,
            ],
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    /**
     * Record referral credit earnings
     */
    public function recordReferralEarnings(User $user, float $amount, string $description, array $metadata = [], ?int $performedBy = null): WalletTransaction
    {
        return $this->createTransaction([
            'user_id' => $user->id,
            'transaction_type' => self::TYPE_REFERRAL_EARNED,
            'source_type' => self::SOURCE_REFERRAL,
            'source_id' => $metadata['referral_id'] ?? null,
            'amount' => $amount,
            'wallet_type' => self::WALLET_REFERRAL,
            'description' => $description,
            'metadata' => $metadata,
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    /**
     * Record a payout withdrawal (when payout is marked as paid)
     */
    public function recordPayoutWithdrawal(Payout $payout, ?int $performedBy = null): array
    {
        $transactions = [];
        $user = $payout->adiutor;

        // Calculate totals by wallet type
        $workEarningsTotal = 0;
        $referralCreditsTotal = 0;

        foreach ($payout->items as $item) {
            if ($item->item_type === 'referral') {
                $referralCreditsTotal += $item->amount;
            } else {
                $workEarningsTotal += $item->amount;
            }
        }

        // Create withdrawal transaction for work earnings
        if ($workEarningsTotal > 0) {
            $transactions[] = $this->createTransaction([
                'user_id' => $user->id,
                'transaction_type' => self::TYPE_WITHDRAWN,
                'source_type' => self::SOURCE_WITHDRAWAL,
                'source_id' => $payout->id,
                'reference_type' => 'payout',
                'reference_id' => $payout->id,
                'amount' => -$workEarningsTotal,
                'wallet_type' => self::WALLET_WORK,
                'description' => "Payout {$payout->payout_number} - Work Earnings",
                'metadata' => [
                    'payout_number' => $payout->payout_number,
                    'reference_number' => $payout->reference_number,
                    'payout_method' => $payout->payout_method,
                ],
                'performed_by' => $performedBy,
                'payout_id' => $payout->id,
                'status' => 'completed',
            ]);
        }

        // Create withdrawal transaction for referral credits
        if ($referralCreditsTotal > 0) {
            $transactions[] = $this->createTransaction([
                'user_id' => $user->id,
                'transaction_type' => self::TYPE_WITHDRAWN,
                'source_type' => self::SOURCE_WITHDRAWAL,
                'source_id' => $payout->id,
                'reference_type' => 'payout',
                'reference_id' => $payout->id,
                'amount' => -$referralCreditsTotal,
                'wallet_type' => self::WALLET_REFERRAL,
                'description' => "Payout {$payout->payout_number} - Referral Credits",
                'metadata' => [
                    'payout_number' => $payout->payout_number,
                    'reference_number' => $payout->reference_number,
                    'payout_method' => $payout->payout_method,
                ],
                'performed_by' => $performedBy,
                'payout_id' => $payout->id,
                'status' => 'completed',
            ]);
        }

        return $transactions;
    }

    /**
     * Record an admin adjustment
     */
    public function recordAdjustment(User $user, string $walletType, float $amount, string $description, ?int $performedBy = null): WalletTransaction
    {
        return $this->createTransaction([
            'user_id' => $user->id,
            'transaction_type' => self::TYPE_ADJUSTED,
            'source_type' => self::SOURCE_ADMIN_ADJUSTMENT,
            'source_id' => null,
            'amount' => $amount,
            'wallet_type' => $walletType,
            'description' => $description,
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    /**
     * Reverse a transaction (for cancelled payouts, etc.)
     */
    public function reverseTransaction(WalletTransaction $originalTransaction, string $reason, ?int $performedBy = null): WalletTransaction
    {
        return $this->createTransaction([
            'user_id' => $originalTransaction->user_id,
            'transaction_type' => self::TYPE_REFUNDED,
            'source_type' => self::SOURCE_REVERSAL,
            'source_id' => $originalTransaction->id,
            'amount' => -$originalTransaction->amount, // Opposite of original
            'wallet_type' => $originalTransaction->wallet_type,
            'description' => "Reversal: {$reason}",
            'metadata' => [
                'original_transaction_id' => $originalTransaction->id,
                'original_description' => $originalTransaction->description,
                'reason' => $reason,
            ],
            'performed_by' => $performedBy,
            'status' => 'completed',
        ]);
    }

    /**
     * Get user's current wallet balance
     */
    public function getBalance(User $user, string $walletType): float
    {
        return WalletTransaction::where('user_id', $user->id)
            ->where('wallet_type', $walletType)
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Get user's available balance (excluding pending withdrawals)
     */
    public function getAvailableBalance(User $user, string $walletType): float
    {
        // Get total earned
        $totalEarned = WalletTransaction::where('user_id', $user->id)
            ->where('wallet_type', $walletType)
            ->where('status', 'completed')
            ->where('amount', '>', 0)
            ->sum('amount');

        // Get total withdrawn (completed)
        $totalWithdrawn = abs(WalletTransaction::where('user_id', $user->id)
            ->where('wallet_type', $walletType)
            ->where('status', 'completed')
            ->where('amount', '<', 0)
            ->sum('amount'));

        // Get pending withdrawals (in pending payouts)
        $pendingWithdrawals = PayoutItem::whereHas('payout', function($query) use ($user) {
            $query->where('adiutor_id', $user->id)
                ->whereIn('status', ['pending', 'processing']);
        })->when($walletType === self::WALLET_REFERRAL, function($query) {
            $query->where('item_type', 'referral');
        })->when($walletType === self::WALLET_WORK, function($query) {
            $query->where('item_type', '!=', 'referral');
        })->sum('amount');

        return $totalEarned - $totalWithdrawn - $pendingWithdrawals;
    }

    /**
     * Update user's cached wallet balances
     */
    public function updateUserWalletBalances(User $user): void
    {
        // Calculate work earnings
        $workEarned = WalletTransaction::where('user_id', $user->id)
            ->where('wallet_type', self::WALLET_WORK)
            ->where('status', 'completed')
            ->where('amount', '>', 0)
            ->sum('amount');

        $workWithdrawn = abs(WalletTransaction::where('user_id', $user->id)
            ->where('wallet_type', self::WALLET_WORK)
            ->where('status', 'completed')
            ->where('amount', '<', 0)
            ->sum('amount'));

        $workPending = PayoutItem::whereHas('payout', function($query) use ($user) {
            $query->where('adiutor_id', $user->id)
                ->whereIn('status', ['pending', 'processing']);
        })->where('item_type', '!=', 'referral')->sum('amount');

        // Calculate referral credits
        $referralEarned = WalletTransaction::where('user_id', $user->id)
            ->where('wallet_type', self::WALLET_REFERRAL)
            ->where('status', 'completed')
            ->where('amount', '>', 0)
            ->sum('amount');

        $referralWithdrawn = abs(WalletTransaction::where('user_id', $user->id)
            ->where('wallet_type', self::WALLET_REFERRAL)
            ->where('status', 'completed')
            ->where('amount', '<', 0)
            ->sum('amount'));

        $referralPending = PayoutItem::whereHas('payout', function($query) use ($user) {
            $query->where('adiutor_id', $user->id)
                ->whereIn('status', ['pending', 'processing']);
        })->where('item_type', 'referral')->sum('amount');

        // Update user balances
        $user->update([
            'work_earnings_balance' => $workEarned - $workWithdrawn - $workPending,
            'work_earnings_pending' => $workPending,
            'work_earnings_withdrawn' => $workWithdrawn,
            'referral_credits' => $referralEarned - $referralWithdrawn - $referralPending,
            'referral_credits_pending' => $referralPending,
            'referral_credits_withdrawn' => $referralWithdrawn,
        ]);
    }

    /**
     * Create a wallet transaction with balance tracking
     */
    protected function createTransaction(array $data): WalletTransaction
    {
        $user = User::find($data['user_id']);
        
        // Get current balance before this transaction
        $balanceBefore = WalletTransaction::where('user_id', $data['user_id'])
            ->where('wallet_type', $data['wallet_type'])
            ->where('status', 'completed')
            ->sum('amount');

        $balanceAfter = $balanceBefore + $data['amount'];

        $transaction = WalletTransaction::create([
            'user_id' => $data['user_id'],
            'transaction_type' => $data['transaction_type'],
            'source_type' => $data['source_type'],
            'source_id' => $data['source_id'] ?? null,
            'reference_type' => $data['reference_type'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'amount' => $data['amount'],
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'wallet_type' => $data['wallet_type'],
            'description' => $data['description'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'performed_by' => $data['performed_by'] ?? null,
            'payout_id' => $data['payout_id'] ?? null,
            'status' => $data['status'] ?? 'completed',
        ]);

        // Update user's cached balances
        $this->updateUserWalletBalances($user);

        return $transaction;
    }

    /**
     * Get transaction history for a user
     */
    public function getTransactionHistory(User $user, ?string $walletType = null, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        $query = WalletTransaction::where('user_id', $user->id)
            ->with('performer')
            ->orderBy('created_at', 'desc');

        if ($walletType) {
            $query->where('wallet_type', $walletType);
        }

        return $query->limit($limit)->get();
    }
}
