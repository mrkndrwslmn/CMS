<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    protected ReferralService $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display referral analytics dashboard
     */
    public function index(): View
    {
        // Overall statistics
        $totalReferrals = Referral::count();
        $pendingReferrals = Referral::where('status', 'pending')->count();
        $successfulReferrals = Referral::where('status', 'rewarded')->count();
        
        // Total points distributed
        $totalRewards = Referral::whereIn('status', ['completed', 'rewarded'])
            ->sum('referrer_points_earned');
        
        // Conversion rate
        $conversionRate = $totalReferrals > 0 
            ? ($successfulReferrals / $totalReferrals) * 100 
            : 0;
        
        // Calculate growth (compare this month vs last month)
        $thisMonthCount = Referral::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $lastMonthCount = Referral::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $referralGrowth = $lastMonthCount > 0 
            ? (($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100 
            : 0;
        
        // Prepare stats array for view
        $stats = [
            'total_referrals' => $totalReferrals,
            'pending_referrals' => $pendingReferrals,
            'successful_referrals' => $successfulReferrals,
            'total_rewards' => $totalRewards,
            'conversion_rate' => $conversionRate,
            'referral_growth' => round($referralGrowth, 1),
        ];
        
        // Top referrers (last 30 days)
        $topReferrers = User::withCount(['referralsMade as successful_referrals' => function($query) {
                $query->where('status', 'rewarded')
                      ->where('created_at', '>=', now()->subDays(30));
            }])
            ->having('successful_referrals', '>', 0)
            ->orderBy('successful_referrals', 'desc')
            ->limit(10)
            ->get();
        
        // Recent referrals
        $recentReferrals = Referral::with(['referrer', 'referred', 'referrerCoupon', 'referredCoupon'])
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
        
        // Monthly statistics (last 6 months)
        $monthlyStats = Referral::select(
                DB::raw('DATE_FORMAT(created_at, "%b %Y") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "rewarded" THEN 1 ELSE 0 END) as completed')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), 'asc')
            ->get();
        
        return view('admin.referrals.index', compact(
            'stats',
            'topReferrers',
            'recentReferrals',
            'monthlyStats'
        ));
    }

    /**
     * Display all referrals with filtering
     */
    public function list(Request $request): View
    {
        $query = Referral::with(['referrer', 'referred', 'referrerCoupon', 'referredCoupon']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by referrer or referred name/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('referrer', function($q2) use ($search) {
                    $q2->where('fullName', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('referred', function($q2) use ($search) {
                    $q2->where('fullName', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }
        
        $referrals = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.referrals.list', compact('referrals'));
    }

    /**
     * View detailed referral information
     */
    public function show(Referral $referral): View
    {
        $referral->load([
            'referrer.referralCode',
            'referred',
            'referrerCoupon',
            'referredCoupon',
            'firstPayment'
        ]);
        
        return view('admin.referrals.show', compact('referral'));
    }

    /**
     * Display referral codes management
     */
    public function codes(Request $request): View
    {
        $query = ReferralCode::with('user');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('fullName', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }
        
        // Min referrals filter
        if ($request->filled('min_referrals')) {
            $query->where('total_referrals', '>=', $request->min_referrals);
        }
        
        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $codes = $query->paginate(20);
        
        // Statistics
        $stats = [
            'total_codes' => ReferralCode::count(),
            'active_codes' => ReferralCode::where('is_active', 1)->count(),
            'total_uses' => ReferralCode::sum('total_referrals'),
            'avg_conversion' => ReferralCode::where('total_referrals', '>', 0)
                ->get()
                ->avg(function($code) {
                    return $code->total_referrals > 0 
                        ? ($code->successful_referrals / $code->total_referrals) * 100 
                        : 0;
                }) ?? 0,
        ];
        
        return view('admin.referrals.codes', compact('codes', 'stats'));
    }

    /**
     * Toggle referral code active status
     */
    public function toggleCodeStatus(ReferralCode $code): JsonResponse
    {
        try {
            $code->update(['is_active' => !$code->is_active]);
            
            return response()->json([
                'success' => true,
                'is_active' => $code->is_active,
                'message' => $code->is_active 
                    ? 'Referral code activated successfully.' 
                    : 'Referral code deactivated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update referral code status.',
            ], 500);
        }
    }

    /**
     * Get referral analytics data (AJAX)
     */
    public function analytics(): JsonResponse
    {
        // Daily referrals for the last 30 days
        $dailyReferrals = Referral::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "rewarded" THEN 1 ELSE 0 END) as successful')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Status distribution
        $statusDistribution = Referral::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Conversion funnel
        $totalSignups = Referral::count();
        $completedPayments = Referral::whereIn('status', ['completed', 'rewarded'])->count();
        $rewardedCount = Referral::where('status', 'rewarded')->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'daily_referrals' => $dailyReferrals,
                'status_distribution' => $statusDistribution,
                'conversion_funnel' => [
                    'signups' => $totalSignups,
                    'payments' => $completedPayments,
                    'rewarded' => $rewardedCount,
                ],
            ],
        ]);
    }

    /**
     * Export referrals data to CSV
     */
    public function export(Request $request)
    {
        $query = Referral::with(['referrer', 'referred', 'referralCode']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('referrer', function ($q) use ($search) {
                    $q->where('fullName', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('referred', function ($q) use ($search) {
                    $q->where('fullName', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $referrals = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'referrals_export_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($referrals) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Referrer Name',
                'Referrer Email',
                'Referred Name',
                'Referred Email',
                'Referral Code',
                'Status',
                'Points Earned',
                'Points Pending',
                'Referred User Signup Date',
                'Referred User Payment Date',
                'Rewarded Date',
                'Created At',
            ]);

            // Data rows
            foreach ($referrals as $referral) {
                fputcsv($file, [
                    $referral->id,
                    $referral->referrer->fullName ?? 'N/A',
                    $referral->referrer->email ?? 'N/A',
                    $referral->referred->fullName ?? 'N/A',
                    $referral->referred->email ?? 'N/A',
                    $referral->referralCode->code ?? 'N/A',
                    ucfirst($referral->status),
                    $referral->referrer_points_earned ?? 0,
                    $referral->referrer_points_pending ?? 0,
                    $referral->referred_user_signup_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $referral->referred_user_payment_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $referral->rewarded_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $referral->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Manually process a pending referral (admin override)
     */
    public function processPending(Referral $referral): JsonResponse
    {
        if ($referral->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending referrals can be processed.',
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            // Create a fake payment record or use null
            // For admin override, we'll mark it as completed without payment
            $referral->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            // Award points to referrer
            $loyaltyPoint = $referral->referrer->getOrCreateLoyaltyPoints();
            $loyaltyPoint->earnPoints(
                $referral->referrer_points_pending,
                'referral_completion_admin',
                "Admin-processed referral bonus for {$referral->referred->fullName}",
                $referral
            );
            
            $referral->update(['referrer_points_earned' => $referral->referrer_points_pending]);
            
            // Mark as rewarded
            $referral->markRewarded();
            
            // Update referral code stats
            $referrerCode = $referral->referrer->referralCode;
            if ($referrerCode) {
                $referrerCode->incrementReferral('completed');
                $referrerCode->addEarnings($referral->referrer_points_earned);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Referral processed successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process referral: ' . $e->getMessage(),
            ], 500);
        }
    }
}
