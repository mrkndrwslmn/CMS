<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\Project;
use App\Models\ProjectAssignment;
use App\Models\ProjectMilestone;
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
        $typeFilter = $request->get('type', 'all'); // all, hourly, fixed_rate, milestone

        // ===========================================
        // TIME TRACKING / HOURLY EARNINGS
        // ===========================================
        $timeEntriesQuery = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->with(['task.project', 'task.project.assignments' => function($query) use ($adiutorId) {
                $query->where('adiutor_id', $adiutorId);
            }, 'payout']);

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

        // Apply status filter for time entries
        if ($statusFilter === 'approved') {
            $timeEntriesQuery->where('is_approved', true)->where('is_paid', false);
        } elseif ($statusFilter === 'pending') {
            $timeEntriesQuery->where('is_approved', false);
        } elseif ($statusFilter === 'paid') {
            $timeEntriesQuery->where('is_paid', true);
        }

        $timeEntries = $timeEntriesQuery->orderBy('start_time', 'desc')->get();

        // Hourly earnings calculations
        $hourlyTotalEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->whereNotNull('calculated_amount')
            ->sum('calculated_amount');

        $hourlyApprovedEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->sum('calculated_amount');

        $hourlyPaidEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->where('is_paid', true)
            ->sum('calculated_amount');

        $hourlyPendingApproval = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', false)
            ->sum('calculated_amount');

        // Get total hours
        $totalHours = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->sum('duration_minutes') / 60;

        // ===========================================
        // FIXED RATE PROJECT EARNINGS
        // ===========================================
        // Fixed rate: Only withdrawable when PROJECT is completed
        $fixedRateAssignments = ProjectAssignment::where('adiutor_id', $adiutorId)
            ->where('payment_type', ProjectAssignment::PAYMENT_TYPE_FIXED)
            ->with(['project'])
            ->get();

        $fixedRateTotalEarnings = 0;
        $fixedRateApprovedEarnings = 0; // Approved by admin but project not complete
        $fixedRateWithdrawableEarnings = 0; // Project completed, approved, and withdrawable
        $fixedRatePaidEarnings = 0;
        $fixedRatePendingApproval = 0;
        $fixedRateEarningsDetails = [];

        foreach ($fixedRateAssignments as $assignment) {
            $amount = (float) $assignment->agreed_rate;
            $fixedRateTotalEarnings += $amount;

            $projectCompleted = $assignment->project && $assignment->project->status === 'completed';
            
            if ($assignment->fixed_rate_paid) {
                $fixedRatePaidEarnings += $amount;
            } elseif ($assignment->fixed_rate_approved) {
                // Approved by admin
                if ($projectCompleted) {
                    // Project completed - can withdraw
                    $fixedRateWithdrawableEarnings += $amount;
                } else {
                    // Approved but project not complete - not yet withdrawable
                    $fixedRateApprovedEarnings += $amount;
                }
            } else {
                // Not yet approved
                $fixedRatePendingApproval += $amount;
            }

            // Add to details for display
            $fixedRateEarningsDetails[] = [
                'type' => 'fixed_rate',
                'project' => $assignment->project,
                'assignment' => $assignment,
                'amount' => $amount,
                'status' => $assignment->fixed_rate_paid ? 'paid' : 
                           ($assignment->fixed_rate_approved ? ($projectCompleted ? 'withdrawable' : 'approved') : 'pending'),
                'project_completed' => $projectCompleted,
                'date' => $assignment->created_at,
            ];
        }

        // ===========================================
        // MILESTONE PAYMENT EARNINGS
        // ===========================================
        // Get milestones for projects where adiutor is assigned
        $assignedProjectIds = ProjectAssignment::where('adiutor_id', $adiutorId)
            ->pluck('project_id');

        // Get milestones with their task assignments
        $milestonesWithAssignments = ProjectMilestone::whereIn('project_id', $assignedProjectIds)
            ->with(['project', 'tasks' => function($query) use ($adiutorId) {
                $query->where('assignedTo', $adiutorId);
            }])
            ->get();

        $milestoneTotalEarnings = 0;
        $milestoneWithdrawableEarnings = 0; // Milestone is paid by client
        $milestonePendingEarnings = 0; // Milestone not yet paid
        $milestonePaidEarnings = 0; // Already paid to adiutor
        $milestoneEarningsDetails = [];

        foreach ($milestonesWithAssignments as $milestone) {
            // Only count if adiutor has tasks in this milestone
            if ($milestone->tasks->isEmpty()) {
                continue;
            }

            // Calculate adiutor's share based on tasks assigned to them
            // This is a simplified calculation - in production you might want
            // a more sophisticated share calculation
            $totalTasksInMilestone = Task::where('phase_id', $milestone->id)->count();
            $adiutorTasksInMilestone = $milestone->tasks->count();
            
            if ($totalTasksInMilestone > 0) {
                $adiutorShare = ($adiutorTasksInMilestone / $totalTasksInMilestone) * (float) $milestone->amount;
            } else {
                $adiutorShare = 0;
            }

            if ($adiutorShare > 0) {
                $milestoneTotalEarnings += $adiutorShare;

                // Check if milestone is paid by client
                if ($milestone->is_paid) {
                    // Milestone is paid - adiutor can withdraw
                    $milestoneWithdrawableEarnings += $adiutorShare;
                } else {
                    $milestonePendingEarnings += $adiutorShare;
                }

                $milestoneEarningsDetails[] = [
                    'type' => 'milestone',
                    'milestone' => $milestone,
                    'project' => $milestone->project,
                    'tasks_count' => $adiutorTasksInMilestone,
                    'total_tasks' => $totalTasksInMilestone,
                    'amount' => $adiutorShare,
                    'status' => $milestone->is_paid ? 'withdrawable' : 'pending',
                    'date' => $milestone->completed_date ?? $milestone->created_at,
                ];
            }
        }

        // ===========================================
        // FIXED BUDGET TASK EARNINGS
        // ===========================================
        $fixedBudgetEarnings = Task::where('assignedTo', $adiutorId)
            ->where('use_fixed_budget', true)
            ->where('status', 'completed')
            ->sum('allocated_budget');

        // ===========================================
        // COMBINED TOTALS
        // ===========================================
        // Total earnings across all types
        $totalEarnings = $hourlyTotalEarnings + $fixedRateTotalEarnings + $milestoneTotalEarnings + $fixedBudgetEarnings;

        // Approved/Withdrawable earnings (available for payout)
        $approvedEarnings = $hourlyApprovedEarnings + $fixedRateWithdrawableEarnings + $milestoneWithdrawableEarnings;

        // Paid earnings
        $paidEarnings = $hourlyPaidEarnings + $fixedRatePaidEarnings + $milestonePaidEarnings;

        // Pending approval (not yet approved OR approved but not yet withdrawable)
        $pendingApproval = $hourlyPendingApproval + $fixedRatePendingApproval + $fixedRateApprovedEarnings + $milestonePendingEarnings;

        // Get earnings by project (hourly only for now)
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

        // Combine all earnings for display
        $allEarningsDetails = collect($fixedRateEarningsDetails)
            ->merge($milestoneEarningsDetails)
            ->sortByDesc('date');

        // Paginate time entries for display
        $timeEntriesPaginated = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->with(['task.project', 'payout'])
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return view('adiutor.earnings.index', compact(
            'timeEntries',
            'timeEntriesPaginated',
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
            'statusFilter',
            'typeFilter',
            // Hourly breakdown
            'hourlyTotalEarnings',
            'hourlyApprovedEarnings',
            'hourlyPaidEarnings',
            'hourlyPendingApproval',
            // Fixed rate breakdown
            'fixedRateTotalEarnings',
            'fixedRateApprovedEarnings',
            'fixedRateWithdrawableEarnings',
            'fixedRatePaidEarnings',
            'fixedRatePendingApproval',
            'fixedRateEarningsDetails',
            // Milestone breakdown
            'milestoneTotalEarnings',
            'milestoneWithdrawableEarnings',
            'milestonePendingEarnings',
            'milestonePaidEarnings',
            'milestoneEarningsDetails',
            // Combined details
            'allEarningsDetails'
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
        $adiutorId = $adiutor->id;

        // ===========================================
        // CALCULATE WORK EARNINGS USING SAME LOGIC AS INDEX
        // ===========================================
        
        // --- HOURLY EARNINGS ---
        $hourlyApprovedEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->sum('calculated_amount');

        $hourlyPaidEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->where('is_paid', true)
            ->sum('calculated_amount');

        $hourlyPendingApproval = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', false)
            ->sum('calculated_amount');

        // --- FIXED RATE EARNINGS ---
        // Fixed rate: Only withdrawable when PROJECT is completed
        $fixedRateAssignments = ProjectAssignment::where('adiutor_id', $adiutorId)
            ->where('payment_type', ProjectAssignment::PAYMENT_TYPE_FIXED)
            ->with(['project'])
            ->get();

        $fixedRateWithdrawableEarnings = 0;
        $fixedRatePaidEarnings = 0;
        $fixedRatePendingEarnings = 0;

        foreach ($fixedRateAssignments as $assignment) {
            $amount = (float) $assignment->agreed_rate;
            $projectCompleted = $assignment->project && $assignment->project->status === 'completed';
            
            if ($assignment->fixed_rate_paid) {
                $fixedRatePaidEarnings += $amount;
            } elseif ($assignment->fixed_rate_approved && $projectCompleted) {
                // Approved AND project completed - can withdraw
                $fixedRateWithdrawableEarnings += $amount;
            } else {
                // Not approved OR approved but project not complete - pending
                $fixedRatePendingEarnings += $amount;
            }
        }

        // --- MILESTONE EARNINGS ---
        // Milestone: Withdrawable when milestone is paid by client
        $assignedProjectIds = ProjectAssignment::where('adiutor_id', $adiutorId)
            ->pluck('project_id');

        $milestonesWithAssignments = ProjectMilestone::whereIn('project_id', $assignedProjectIds)
            ->with(['project', 'tasks' => function($query) use ($adiutorId) {
                $query->where('assignedTo', $adiutorId);
            }])
            ->get();

        $milestoneWithdrawableEarnings = 0;
        $milestonePaidEarnings = 0;
        $milestonePendingEarnings = 0;

        foreach ($milestonesWithAssignments as $milestone) {
            if ($milestone->tasks->isEmpty()) {
                continue;
            }

            $totalTasksInMilestone = Task::where('phase_id', $milestone->id)->count();
            $adiutorTasksInMilestone = $milestone->tasks->count();
            
            if ($totalTasksInMilestone > 0) {
                $adiutorShare = ($adiutorTasksInMilestone / $totalTasksInMilestone) * (float) $milestone->amount;
            } else {
                $adiutorShare = 0;
            }

            if ($adiutorShare > 0) {
                if ($milestone->is_paid) {
                    // Milestone is paid by client - adiutor can withdraw
                    $milestoneWithdrawableEarnings += $adiutorShare;
                } else {
                    $milestonePendingEarnings += $adiutorShare;
                }
            }
        }

        // --- COMBINED WALLET BALANCES ---
        // Work earnings available for withdrawal (approved hourly + fixed rate with completed project + paid milestones)
        $workEarningsBalance = $hourlyApprovedEarnings + $fixedRateWithdrawableEarnings + $milestoneWithdrawableEarnings;
        
        // Work earnings pending (not yet withdrawable)
        $workEarningsPending = $hourlyPendingApproval + $fixedRatePendingEarnings + $milestonePendingEarnings;
        
        // Work earnings already withdrawn/paid
        $workEarningsWithdrawn = $hourlyPaidEarnings + $fixedRatePaidEarnings + $milestonePaidEarnings;

        // Referral credits (keep from user model as these are separate)
        $referralCreditsBalance = $adiutor->referral_credits ?? 0;
        $referralCreditsPending = $adiutor->referral_credits_pending ?? 0;
        $referralCreditsWithdrawn = $adiutor->referral_credits_withdrawn ?? 0;

        // Totals
        $totalAvailable = $workEarningsBalance + $referralCreditsBalance;
        $totalPending = $workEarningsPending + $referralCreditsPending;
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
