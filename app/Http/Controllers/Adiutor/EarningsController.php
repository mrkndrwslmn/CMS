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
        
        // Get pending payout IDs (requested but not yet paid/rejected)
        $pendingPayoutIds = Payout::where('adiutor_id', $adiutorId)
            ->whereIn('status', ['pending', 'processing'])
            ->pluck('id');
        
        // --- HOURLY EARNINGS ---
        // Available: approved, not paid, AND not in any pending payout
        $hourlyApprovedEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->whereNull('payout_id')
            ->sum('calculated_amount');

        // In payout request: approved, not paid, but in a pending payout
        $hourlyInPayoutRequest = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->whereIn('payout_id', $pendingPayoutIds)
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
        $fixedRateInPayoutRequest = 0;

        foreach ($fixedRateAssignments as $assignment) {
            $amount = (float) $assignment->agreed_rate;
            $projectCompleted = $assignment->project && $assignment->project->status === 'completed';
            
            if ($assignment->fixed_rate_paid) {
                $fixedRatePaidEarnings += $amount;
            } elseif ($assignment->fixed_rate_payout_id && $pendingPayoutIds->contains($assignment->fixed_rate_payout_id)) {
                // In a pending payout request
                $fixedRateInPayoutRequest += $amount;
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

        // Get milestone IDs that are already in pending payouts
        $milestonesInPendingPayouts = PayoutItem::whereIn('payout_id', $pendingPayoutIds)
            ->where('item_type', 'milestone')
            ->whereNotNull('milestone_id')
            ->pluck('milestone_id');

        // Get milestone IDs that have already been paid
        $milestonesPaid = PayoutItem::where('item_type', 'milestone')
            ->whereNotNull('milestone_id')
            ->whereHas('payout', function($query) {
                $query->where('status', 'paid');
            })
            ->pluck('milestone_id');

        $milestonesWithAssignments = ProjectMilestone::whereIn('project_id', $assignedProjectIds)
            ->with(['project', 'tasks' => function($query) use ($adiutorId) {
                $query->where('assignedTo', $adiutorId);
            }])
            ->get();

        $milestoneWithdrawableEarnings = 0;
        $milestonePaidEarnings = 0;
        $milestonePendingEarnings = 0;
        $milestoneInPayoutRequest = 0;

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
                if ($milestonesPaid->contains($milestone->id)) {
                    // Already paid to adiutor
                    $milestonePaidEarnings += $adiutorShare;
                } elseif ($milestonesInPendingPayouts->contains($milestone->id)) {
                    // Already in a pending payout request
                    $milestoneInPayoutRequest += $adiutorShare;
                } elseif ($milestone->is_paid) {
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
        
        // Work earnings in payout request (requested but not yet paid)
        $workEarningsInRequest = $hourlyInPayoutRequest + $fixedRateInPayoutRequest + $milestoneInPayoutRequest;
        
        // Work earnings already withdrawn/paid
        $workEarningsWithdrawn = $hourlyPaidEarnings + $fixedRatePaidEarnings + $milestonePaidEarnings;

        // Referral credits - need to exclude those in pending payouts
        $referralCreditsInPendingPayouts = PayoutItem::whereIn('payout_id', $pendingPayoutIds)
            ->where('item_type', 'referral')
            ->sum('amount');

        $referralCreditsBalance = max(0, ($adiutor->referral_credits ?? 0) - $referralCreditsInPendingPayouts);
        $referralCreditsInRequest = $referralCreditsInPendingPayouts;
        $referralCreditsPending = $adiutor->referral_credits_pending ?? 0;
        $referralCreditsWithdrawn = $adiutor->referral_credits_withdrawn ?? 0;

        // Totals
        $totalAvailable = $workEarningsBalance + $referralCreditsBalance;
        $totalPending = $workEarningsPending + $referralCreditsPending;
        $totalInRequest = $workEarningsInRequest + $referralCreditsInRequest;
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
            'workEarningsInRequest',
            'totalInRequest',
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
            'include_time_entries' => 'nullable|boolean',
            'include_fixed_rate' => 'nullable|boolean',
            'include_milestones' => 'nullable|boolean',
            'include_referral_credits' => 'nullable|boolean',
        ]);

        $adiutorId = Auth::id();
        $adiutor = Auth::user();
        
        $totalAmount = 0;
        $hasEarnings = false;

        // Get pending payout IDs to exclude earnings already in request
        $pendingPayoutIds = Payout::where('adiutor_id', $adiutorId)
            ->whereIn('status', ['pending', 'processing'])
            ->pluck('id');

        // ===========================================
        // TIME ENTRY EARNINGS
        // ===========================================
        $timeEntries = collect();
        if ($request->input('include_time_entries', true)) {
            $timeEntries = TimeEntry::where('adiutor_id', $adiutorId)
                ->whereNotNull('end_time')
                ->where('is_approved', true)
                ->where('is_paid', false)
                ->whereNull('payout_id') // Exclude those already in a payout
                ->whereBetween('start_time', [$request->period_start, $request->period_end])
                ->whereNotNull('calculated_amount')
                ->with(['task.project'])
                ->get();
            
            $totalAmount += $timeEntries->sum('calculated_amount');
            if ($timeEntries->isNotEmpty()) $hasEarnings = true;
        }

        // ===========================================
        // FIXED RATE PROJECT EARNINGS
        // ===========================================
        $fixedRateAssignments = collect();
        if ($request->input('include_fixed_rate', true)) {
            $fixedRateAssignments = ProjectAssignment::where('adiutor_id', $adiutorId)
                ->where('payment_type', ProjectAssignment::PAYMENT_TYPE_FIXED)
                ->where('fixed_rate_approved', true)
                ->where('fixed_rate_paid', false)
                ->whereNull('fixed_rate_payout_id') // Exclude those already in a payout
                ->whereHas('project', function($query) {
                    $query->where('status', 'completed');
                })
                ->with(['project'])
                ->get();
            
            $totalAmount += $fixedRateAssignments->sum('agreed_rate');
            if ($fixedRateAssignments->isNotEmpty()) $hasEarnings = true;
        }

        // ===========================================
        // MILESTONE EARNINGS
        // ===========================================
        // Get milestone IDs already in pending payouts
        $milestonesInPendingPayouts = PayoutItem::whereIn('payout_id', $pendingPayoutIds)
            ->where('item_type', 'milestone')
            ->whereNotNull('milestone_id')
            ->pluck('milestone_id');

        $milestoneEarningsData = collect();
        if ($request->input('include_milestones', true)) {
            $assignedProjectIds = ProjectAssignment::where('adiutor_id', $adiutorId)
                ->pluck('project_id');

            $milestonesWithAssignments = ProjectMilestone::whereIn('project_id', $assignedProjectIds)
                ->where('is_paid', true)
                ->whereNotIn('id', $milestonesInPendingPayouts) // Exclude those already in a payout
                ->with(['project', 'tasks' => function($query) use ($adiutorId) {
                    $query->where('assignedTo', $adiutorId);
                }])
                ->get();

            foreach ($milestonesWithAssignments as $milestone) {
                if ($milestone->tasks->isEmpty()) continue;

                $totalTasksInMilestone = Task::where('phase_id', $milestone->id)->count();
                $adiutorTasksInMilestone = $milestone->tasks->count();
                
                if ($totalTasksInMilestone > 0) {
                    $adiutorShare = ($adiutorTasksInMilestone / $totalTasksInMilestone) * (float) $milestone->amount;
                    
                    // Check if already paid (in a completed payout)
                    $alreadyPaid = PayoutItem::where('milestone_id', $milestone->id)
                        ->whereHas('payout', function($query) {
                            $query->where('status', 'paid');
                        })
                        ->exists();

                    if (!$alreadyPaid && $adiutorShare > 0) {
                        $totalAmount += $adiutorShare;
                        $milestoneEarningsData->push([
                            'milestone' => $milestone,
                            'amount' => $adiutorShare,
                            'tasks_count' => $adiutorTasksInMilestone,
                            'total_tasks' => $totalTasksInMilestone,
                        ]);
                        $hasEarnings = true;
                    }
                }
            }
        }

        // ===========================================
        // REFERRAL CREDITS
        // ===========================================
        $referralCreditsToInclude = 0;
        if ($request->input('include_referral_credits', true)) {
            $referralCreditsToInclude = (float) $adiutor->referral_credits;
            if ($referralCreditsToInclude > 0) {
                $totalAmount += $referralCreditsToInclude;
                $hasEarnings = true;
            }
        }

        if (!$hasEarnings) {
            return redirect()->back()->withErrors(['error' => 'No approved earnings found for the selected period.']);
        }

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

            // Create payout items for TIME ENTRIES
            foreach ($timeEntries as $entry) {
                PayoutItem::create([
                    'payout_id' => $payout->id,
                    'time_entry_id' => $entry->id,
                    'task_id' => $entry->task_id,
                    'project_id' => $entry->project_id,
                    'item_type' => 'time_entry',
                    'description' => ($entry->task->taskTitle ?? 'Task') . ' - ' . ($entry->task->project->title ?? 'Project'),
                    'amount' => $entry->calculated_amount,
                    'hours' => $entry->duration_minutes / 60,
                    'rate' => $entry->hourly_rate,
                ]);

                // Link time entry to payout
                $entry->update(['payout_id' => $payout->id]);
            }

            // Create payout items for FIXED RATE PROJECTS
            foreach ($fixedRateAssignments as $assignment) {
                PayoutItem::create([
                    'payout_id' => $payout->id,
                    'project_id' => $assignment->project_id,
                    'item_type' => 'fixed_task',
                    'description' => 'Fixed Rate: ' . $assignment->project->title,
                    'amount' => $assignment->agreed_rate,
                ]);

                // Mark assignment as paid
                $assignment->update([
                    'fixed_rate_paid' => true,
                    'fixed_rate_payout_id' => $payout->id,
                ]);
            }

            // Create payout items for MILESTONES
            foreach ($milestoneEarningsData as $milestoneData) {
                $milestone = $milestoneData['milestone'];
                PayoutItem::create([
                    'payout_id' => $payout->id,
                    'project_id' => $milestone->project_id,
                    'milestone_id' => $milestone->id,
                    'item_type' => 'milestone',
                    'description' => 'Milestone: ' . $milestone->phase_name . ' - ' . $milestone->project->title . ' (' . $milestoneData['tasks_count'] . '/' . $milestoneData['total_tasks'] . ' tasks)',
                    'amount' => $milestoneData['amount'],
                ]);
            }

            // Create payout item for REFERRAL CREDITS
            if ($referralCreditsToInclude > 0) {
                PayoutItem::create([
                    'payout_id' => $payout->id,
                    'item_type' => 'referral',
                    'description' => 'Referral Credits',
                    'amount' => $referralCreditsToInclude,
                ]);

                // DON'T move credits yet - only when payout is paid
                // Credits are "locked" by being in the PayoutItem, but still in referral_credits balance
            }

            DB::commit();

            // Send notifications to admins
            $admins = User::where('role', 'admin')->where('status', 'active')->get();
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

        // Get pending payout IDs (requested but not yet paid/rejected)
        $pendingPayoutIds = Payout::where('adiutor_id', $adiutorId)
            ->whereIn('status', ['pending', 'processing'])
            ->pluck('id');

        // ===========================================
        // TIME ENTRY EARNINGS (Hourly)
        // ===========================================
        // Exclude time entries already in pending payouts
        $unpaidTimeEntries = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->whereNull('payout_id')
            ->whereNotNull('calculated_amount')
            ->with(['task.project'])
            ->get();

        $timeEntryTotal = $unpaidTimeEntries->sum('calculated_amount');
        $totalHours = $unpaidTimeEntries->sum('duration_minutes') / 60;

        // ===========================================
        // FIXED RATE PROJECT EARNINGS
        // ===========================================
        // Exclude fixed rate assignments already in pending payouts
        $fixedRateAssignments = ProjectAssignment::where('adiutor_id', $adiutorId)
            ->where('payment_type', ProjectAssignment::PAYMENT_TYPE_FIXED)
            ->where('fixed_rate_approved', true)
            ->where('fixed_rate_paid', false)
            ->whereNull('fixed_rate_payout_id')
            ->whereHas('project', function($query) {
                $query->where('status', 'completed');
            })
            ->with(['project'])
            ->get();

        $fixedRateTotal = $fixedRateAssignments->sum('agreed_rate');

        // ===========================================
        // MILESTONE EARNINGS
        // ===========================================
        // Get projects where adiutor is assigned
        $assignedProjectIds = ProjectAssignment::where('adiutor_id', $adiutorId)
            ->pluck('project_id');

        // Get milestone IDs already in pending payouts
        $milestonesInPendingPayouts = PayoutItem::whereIn('payout_id', $pendingPayoutIds)
            ->where('item_type', 'milestone')
            ->whereNotNull('milestone_id')
            ->pluck('milestone_id');

        // Get paid milestones that haven't been paid out to adiutor
        $milestoneEarnings = collect();
        $milestoneTotal = 0;

        $milestonesWithAssignments = ProjectMilestone::whereIn('project_id', $assignedProjectIds)
            ->where('is_paid', true)
            ->with(['project', 'tasks' => function($query) use ($adiutorId) {
                $query->where('assignedTo', $adiutorId);
            }])
            ->get();

        foreach ($milestonesWithAssignments as $milestone) {
            if ($milestone->tasks->isEmpty()) {
                continue;
            }

            // Skip if milestone is already in a pending payout
            if ($milestonesInPendingPayouts->contains($milestone->id)) {
                continue;
            }

            $totalTasksInMilestone = Task::where('phase_id', $milestone->id)->count();
            $adiutorTasksInMilestone = $milestone->tasks->count();
            
            if ($totalTasksInMilestone > 0) {
                $adiutorShare = ($adiutorTasksInMilestone / $totalTasksInMilestone) * (float) $milestone->amount;
                
                // Check if this milestone payout was already paid (in completed payout)
                $alreadyPaid = PayoutItem::where('milestone_id', $milestone->id)
                    ->whereHas('payout', function($query) {
                        $query->where('status', 'paid');
                    })
                    ->exists();

                if (!$alreadyPaid && $adiutorShare > 0) {
                    $milestoneTotal += $adiutorShare;
                    $milestoneEarnings->push([
                        'milestone' => $milestone,
                        'project' => $milestone->project,
                        'tasks_count' => $adiutorTasksInMilestone,
                        'total_tasks' => $totalTasksInMilestone,
                        'amount' => $adiutorShare,
                    ]);
                }
            }
        }

        // ===========================================
        // REFERRAL CREDITS
        // ===========================================
        $referralCreditsAvailable = (float) $adiutor->referral_credits;

        // ===========================================
        // COMBINED TOTAL
        // ===========================================
        $totalUnpaid = $timeEntryTotal + $fixedRateTotal + $milestoneTotal + $referralCreditsAvailable;

        // Suggest period (from earliest unpaid earning to today)
        $earliestDate = $unpaidTimeEntries->min('start_time');
        $suggestedStart = $earliestDate ? Carbon::parse($earliestDate)->startOfMonth() : Carbon::now()->subMonth()->startOfMonth();
        $suggestedEnd = Carbon::now();

        return view('adiutor.earnings.request-payout', compact(
            'unpaidTimeEntries',
            'timeEntryTotal',
            'fixedRateAssignments',
            'fixedRateTotal',
            'milestoneEarnings',
            'milestoneTotal',
            'referralCreditsAvailable',
            'totalUnpaid',
            'totalHours',
            'suggestedStart',
            'suggestedEnd',
            'adiutor'
        ));
    }
}
