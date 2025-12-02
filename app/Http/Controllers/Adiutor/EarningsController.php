<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\ProjectAssignment;
use App\Models\Payout;
use App\Models\PayoutItem;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Mail\PayoutRequestedMail;
use App\Notifications\PayoutRequestedNotification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EarningsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'adiutor') {
                abort(403, 'Unauthorized access. Adiutors only.');
            }
            return $next($request);
        });
    }

    /**
     * Display earnings dashboard
     */
    public function index(Request $request)
    {
        $adiutorId = Auth::id();
        $adiutor = Auth::user();

        // Get filter parameters
        $periodFilter = $request->get('period', 'all'); // all, month, week
        $statusFilter = $request->get('status', 'all'); // all, approved, pending, paid

        // Base query for time entries
        $timeEntriesQuery = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->with(['task.project', 'payout']);

        // Apply period filter
        if ($periodFilter === 'month') {
            $timeEntriesQuery->whereMonth('start_time', Carbon::now()->month)
                ->whereYear('start_time', Carbon::now()->year);
        } elseif ($periodFilter === 'week') {
            $timeEntriesQuery->whereBetween('start_time', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        }

        // Apply status filter
        if ($statusFilter === 'approved') {
            $timeEntriesQuery->where('is_approved', true)->where('is_paid', false);
        } elseif ($statusFilter === 'pending') {
            $timeEntriesQuery->where('is_approved', false);
        } elseif ($statusFilter === 'paid') {
            $timeEntriesQuery->where('is_paid', true);
        }

        $timeEntries = $timeEntriesQuery->orderBy('start_time', 'desc')->paginate(20);

        // Calculate earnings summary
        $totalEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->whereNotNull('calculated_amount')
            ->sum('calculated_amount');

        $approvedEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->sum('calculated_amount');

        $paidEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->where('is_paid', true)
            ->sum('calculated_amount');

        $pendingApproval = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', false)
            ->sum('calculated_amount');

        // Get total hours
        $totalHours = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->sum('duration_minutes') / 60;

        // Get earnings by project
        $earningsByProject = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->whereNotNull('calculated_amount')
            ->select('project_id', DB::raw('SUM(calculated_amount) as total_earnings'), DB::raw('SUM(duration_minutes) as total_minutes'))
            ->groupBy('project_id')
            ->with('project:id,title')
            ->get();

        // Get recent payouts
        $recentPayouts = Payout::where('adiutor_id', $adiutorId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get fixed budget tasks earnings
        $fixedBudgetEarnings = Task::where('assignedTo', $adiutorId)
            ->where('use_fixed_budget', true)
            ->where('status', 'completed')
            ->sum('allocated_budget');

        return view('adiutor.earnings.index', compact(
            'timeEntries',
            'totalEarnings',
            'approvedEarnings',
            'paidEarnings',
            'pendingApproval',
            'totalHours',
            'earningsByProject',
            'recentPayouts',
            'fixedBudgetEarnings',
            'adiutor',
            'periodFilter',
            'statusFilter'
        ));
    }

    /**
     * Display payout history
     */
    public function payouts(Request $request)
    {
        $adiutorId = Auth::id();

        $payouts = Payout::where('adiutor_id', $adiutorId)
            ->with(['items.task', 'items.project', 'processedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adiutor.earnings.payouts', compact('payouts'));
    }

    /**
     * Display unified wallet dashboard
     */
    public function wallet(Request $request)
    {
        $adiutor = Auth::user();

        // Wallet balances
        $workEarningsBalance = $adiutor->work_earnings_balance ?? 0;
        $referralCreditsBalance = $adiutor->referral_credits ?? 0;
        $totalAvailable = $workEarningsBalance + $referralCreditsBalance;
        
        $workEarningsPending = $adiutor->work_earnings_pending ?? 0;
        $referralCreditsPending = $adiutor->referral_credits_pending ?? 0;
        $totalPending = $workEarningsPending + $referralCreditsPending;
        
        $workEarningsWithdrawn = $adiutor->work_earnings_withdrawn ?? 0;
        $referralCreditsWithdrawn = $adiutor->referral_credits_withdrawn ?? 0;
        $totalWithdrawn = $workEarningsWithdrawn + $referralCreditsWithdrawn;

        // Get wallet transactions with filters
        $walletType = $request->get('wallet_type', 'all'); // all, work_earnings, referral_credits
        $transactionType = $request->get('transaction_type', 'all');
        
        $transactionsQuery = WalletTransaction::where('user_id', $adiutor->id)
            ->with('performer')
            ->orderBy('created_at', 'desc');

        if ($walletType !== 'all') {
            $transactionsQuery->where('wallet_type', $walletType);
        }

        if ($transactionType !== 'all') {
            $transactionsQuery->where('transaction_type', $transactionType);
        }

        $transactions = $transactionsQuery->paginate(15);

        // Stats for charts
        $monthlyEarnings = WalletTransaction::where('user_id', $adiutor->id)
            ->where('amount', '>', 0)
            ->where('wallet_type', 'work_earnings')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        $lastMonthEarnings = WalletTransaction::where('user_id', $adiutor->id)
            ->where('amount', '>', 0)
            ->where('wallet_type', 'work_earnings')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('amount');

        $earningsChange = $lastMonthEarnings > 0 
            ? round((($monthlyEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100, 1)
            : ($monthlyEarnings > 0 ? 100 : 0);

        return view('adiutor.earnings.wallet', compact(
            'adiutor',
            'workEarningsBalance',
            'referralCreditsBalance',
            'totalAvailable',
            'workEarningsPending',
            'referralCreditsPending',
            'totalPending',
            'workEarningsWithdrawn',
            'referralCreditsWithdrawn',
            'totalWithdrawn',
            'transactions',
            'walletType',
            'transactionType',
            'monthlyEarnings',
            'lastMonthEarnings',
            'earningsChange'
        ));
    }

    /**
     * Show payout details
     */
    public function showPayout($id)
    {
        $adiutorId = Auth::id();

        $payout = Payout::where('adiutor_id', $adiutorId)
            ->with(['items.task', 'items.project', 'items.timeEntry', 'processedBy'])
            ->findOrFail($id);

        return view('adiutor.earnings.payout-detail', compact('payout'));
    }

    /**
     * Request a payout
     */
    public function requestPayout(Request $request)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'payout_method' => 'nullable|in:bank_transfer,paypal,gcash,paymaya,cash,check,other',
            'notes' => 'nullable|string|max:1000',
        ]);

        $adiutorId = Auth::id();
        $adiutor = Auth::user();

        // Get approved and unpaid time entries within the period
        $timeEntries = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->whereBetween('start_time', [$request->period_start, $request->period_end])
            ->whereNotNull('calculated_amount')
            ->get();

        if ($timeEntries->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'No approved earnings found for the selected period.']);
        }

        $totalAmount = $timeEntries->sum('calculated_amount');

        // Check minimum payout amount
        $minimumAmount = $adiutor->adiutorProfile->minimum_payout_amount ?? 500;
        if ($totalAmount < $minimumAmount) {
            return redirect()->back()->withErrors([
                'error' => "Total amount (₱" . number_format($totalAmount, 2) . ") is below minimum payout amount (₱" . number_format($minimumAmount, 2) . ")"
            ]);
        }

        DB::beginTransaction();
        try {
            // Create payout
            $payout = Payout::create([
                'payout_number' => Payout::generatePayoutNumber(),
                'adiutor_id' => $adiutorId,
                'amount' => $totalAmount,
                'currency' => $adiutor->adiutorProfile->currency ?? 'PHP',
                'status' => 'pending',
                'payout_method' => $request->payout_method ?? $adiutor->adiutorProfile->preferred_payout_method,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
                'payout_details' => $adiutor->adiutorProfile->payout_details,
                'adiutor_notes' => $request->notes,
                'requested_at' => now(),
            ]);

            // Create payout items and link time entries
            foreach ($timeEntries as $entry) {
                PayoutItem::create([
                    'payout_id' => $payout->id,
                    'time_entry_id' => $entry->id,
                    'task_id' => $entry->task_id,
                    'project_id' => $entry->project_id,
                    'item_type' => 'time_entry',
                    'description' => $entry->task->taskTitle . ' - ' . $entry->task->project->title,
                    'amount' => $entry->calculated_amount,
                    'hours' => $entry->duration_minutes / 60,
                    'rate' => $entry->hourly_rate,
                ]);

                // Link time entry to payout
                $entry->update(['payout_id' => $payout->id]);
            }

            DB::commit();

            // Send notifications to admins
            $admins = User::where('role', 'admin')->where('isActive', true)->get();
            $firebaseService = app(FirebaseService::class);
            
            foreach ($admins as $admin) {
                try {
                    // Send email notification
                    Mail::to($admin->email)->send(new PayoutRequestedMail($payout, $adiutor, $admin));
                    
                    // Send in-app notification
                    $admin->notify(new PayoutRequestedNotification($payout, $adiutor));
                    
                    // Send Firebase push notification
                    if ($admin->fcm_token) {
                        $notificationData = (new PayoutRequestedNotification($payout, $adiutor))->toFirebase($admin);
                        $firebaseService->sendToUser($admin, $notificationData['data'], $notificationData['notification']);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send payout request notification to admin: ' . $e->getMessage());
                }
            }

            return redirect()->route('adiutor.earnings.payout.show', $payout->id)
                ->with('success', 'Payout request submitted successfully. Total: ₱' . number_format($totalAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create payout request: ' . $e->getMessage()]);
        }
    }

    /**
     * Show payout request form
     */
    public function showRequestForm()
    {
        $adiutorId = Auth::id();
        $adiutor = Auth::user();

        // Get unpaid approved earnings
        $unpaidEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->whereNotNull('calculated_amount')
            ->get();

        $totalUnpaid = $unpaidEarnings->sum('calculated_amount');
        $totalHours = $unpaidEarnings->sum('duration_minutes') / 60;

        // Suggest period (last month if enough earnings)
        $suggestedStart = Carbon::now()->subMonth()->startOfMonth();
        $suggestedEnd = Carbon::now()->subMonth()->endOfMonth();

        return view('adiutor.earnings.request-payout', compact(
            'unpaidEarnings',
            'totalUnpaid',
            'totalHours',
            'suggestedStart',
            'suggestedEnd',
            'adiutor'
        ));
    }
}
