<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTransaction;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Display loyalty program overview
     */
    public function index(Request $request)
    {
        $query = LoyaltyPoint::with('user');

        // Filter by tier
        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        // Search by user name/email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('fullName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'lifetime_earned');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $loyaltyPoints = $query->paginate(20);

        // Get global statistics
        $stats = $this->loyaltyService->getGlobalStatistics();

        // Get tier information
        $tiers = $this->loyaltyService->getAllTiers();

        return view('admin.loyalty.index', compact('loyaltyPoints', 'stats', 'tiers'));
    }

    /**
     * Display specific user's loyalty details
     */
    public function show(User $user)
    {
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // Get user statistics from service
        $serviceStats = $this->loyaltyService->getUserStatistics($user);
        
        // Calculate additional stats for the view
        $totalEarned = $user->loyaltyTransactions()->earned()->sum('points');
        $totalRedeemed = abs($user->loyaltyTransactions()->redeemed()->sum('points'));
        $totalExpired = abs($user->loyaltyTransactions()->expired()->sum('points'));
        
        // Calculate tier progress percentage
        $allTiers = $this->loyaltyService->getAllTiers();
        $tierProgress = 0;
        if ($serviceStats['next_tier']) {
            $currentTierPoints = $allTiers[$loyaltyPoint->tier]['points'] ?? 0;
            $nextTierPoints = $allTiers[$serviceStats['next_tier']]['points'] ?? 0;
            $tierRange = $nextTierPoints - $currentTierPoints;
            $progressInTier = $loyaltyPoint->lifetime_earned - $currentTierPoints;
            $tierProgress = $tierRange > 0 ? min(100, ($progressInTier / $tierRange) * 100) : 100;
        }
        
        // Build stats array matching what the view expects
        $stats = [
            'total_earned' => $totalEarned,
            'total_redeemed' => $totalRedeemed,
            'total_expired' => $totalExpired,
            'expiring_soon' => $serviceStats['expiring_soon'],
            'next_tier' => $serviceStats['next_tier'],
            'points_to_next_tier' => $serviceStats['points_to_next_tier'],
            'tier_progress' => $tierProgress,
        ];

        // Get recent transactions (view expects $recentTransactions)
        $recentTransactions = $user->loyaltyTransactions()
            ->with(['serviceRequest', 'payment', 'coupon'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get expiring points (view expects $expiringPoints)
        $expiringPoints = $user->loyaltyTransactions()
            ->earned()
            ->where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc')
            ->get();

        // Get tier benefits
        $currentTierBenefits = $this->loyaltyService->getTierBenefits($loyaltyPoint->tier);

        return view('admin.loyalty.show', compact(
            'user',
            'loyaltyPoint',
            'stats',
            'recentTransactions',
            'expiringPoints',
            'currentTierBenefits',
            'allTiers'
        ));
    }

    /**
     * Manual points adjustment
     */
    public function adjustPoints(Request $request, User $user)
    {
        $validated = $request->validate([
            'points' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:500',
        ]);

        try {
            $this->loyaltyService->adjustPoints(
                $user,
                $validated['points'],
                $validated['reason'],
                Auth::user()
            );

            $action = $validated['points'] > 0 ? 'added to' : 'deducted from';
            $pointsAbs = abs($validated['points']);

            return back()->with('success', "{$pointsAbs} points {$action} {$user->fullName}'s account.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to adjust points: ' . $e->getMessage()]);
        }
    }

    /**
     * Show tier settings
     */
    public function tierSettings()
    {
        $tiers = $this->loyaltyService->getAllTiers();
        
        $tierBenefits = [];
        foreach (array_keys($tiers) as $tierName) {
            $tierBenefits[$tierName] = $this->loyaltyService->getTierBenefits($tierName);
        }

        return view('admin.loyalty.tier-settings', compact('tiers', 'tierBenefits'));
    }

    /**
     * Update tier settings
     */
    public function updateTierSettings(Request $request)
    {
        // Note: This would typically update config or database settings
        // For now, we'll return a message that settings are in config file
        
        return back()->with('info', 'Tier settings are configured in config/loyalty.php. Please update the file directly and clear cache.');
    }

    /**
     * Export loyalty report
     */
    public function exportLoyaltyReport(Request $request)
    {
        $format = $request->get('format', 'csv');

        try {
            $loyaltyPoints = LoyaltyPoint::with('user')->get();

            if ($format === 'csv') {
                $filename = 'loyalty-report-' . now()->format('Y-m-d') . '.csv';
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                ];

                $callback = function () use ($loyaltyPoints) {
                    $file = fopen('php://output', 'w');
                    
                    // Header row
                    fputcsv($file, [
                        'User ID',
                        'Name',
                        'Email',
                        'Tier',
                        'Available Points',
                        'Lifetime Earned',
                        'Lifetime Redeemed',
                        'Member Since',
                    ]);

                    // Data rows
                    foreach ($loyaltyPoints as $lp) {
                        fputcsv($file, [
                            $lp->user_id,
                            $lp->user->fullName,
                            $lp->user->email,
                            ucfirst($lp->tier),
                            $lp->available_points,
                            $lp->lifetime_earned,
                            $lp->lifetime_redeemed,
                            $lp->created_at->format('Y-m-d'),
                        ]);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            return back()->withErrors(['error' => 'Unsupported export format.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to export report: ' . $e->getMessage()]);
        }
    }

    /**
     * View all transactions (admin overview)
     */
    public function transactions(Request $request)
    {
        $query = LoyaltyTransaction::with(['user', 'serviceRequest']);

        // Filter by transaction type
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Search by user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('fullName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.loyalty.transactions', compact('transactions'));
    }

    /**
     * Dashboard widget data (AJAX)
     */
    public function dashboardWidget()
    {
        $stats = $this->loyaltyService->getGlobalStatistics();

        return response()->json($stats);
    }

    /**
     * Leaderboard view
     */
    public function leaderboard(Request $request)
    {
        $period = $request->get('period', 'all_time');

        $query = LoyaltyPoint::with('user');

        // Filter by period for earned points
        if ($period === 'this_month') {
            // Get users who earned points this month
            $userIds = LoyaltyTransaction::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('transaction_type', 'earned')
                ->distinct('user_id')
                ->pluck('user_id');

            $query->whereIn('user_id', $userIds);
        } elseif ($period === 'this_year') {
            $userIds = LoyaltyTransaction::whereYear('created_at', now()->year)
                ->where('transaction_type', 'earned')
                ->distinct('user_id')
                ->pluck('user_id');

            $query->whereIn('user_id', $userIds);
        }

        // Sort by lifetime earned (leaderboard)
        $leaderboard = $query->orderBy('lifetime_earned', 'desc')
            ->limit(50)
            ->get();

        return view('admin.loyalty.leaderboard', compact('leaderboard', 'period'));
    }

    /**
     * Send expiry warning emails manually
     */
    public function sendExpiryWarnings()
    {
        try {
            $expiringDate = now()->addDays(config('loyalty.points.expiry_warning_days', 30));
            
            // Get all expiring transactions grouped by user
            $expiringTransactions = LoyaltyTransaction::where('transaction_type', 'earned')
                ->where('expires_at', '<=', $expiringDate)
                ->where('expires_at', '>', now())
                ->whereNull('expiry_warning_sent_at')
                ->with('user.loyaltyPoints')
                ->get()
                ->groupBy('user_id');

            $sentCount = 0;

            foreach ($expiringTransactions as $userId => $userTransactions) {
                $user = $userTransactions->first()->user;
                
                // Skip if user doesn't exist
                if (!$user) {
                    continue;
                }

                // Send email notification with all expiring points for this user
                \Mail::to($user->email)->send(
                    new \App\Mail\PointsExpiringMail($user, $userTransactions)
                );
                
                // Mark all transactions as having warning sent
                LoyaltyTransaction::whereIn('id', $userTransactions->pluck('id'))
                    ->update(['expiry_warning_sent_at' => now()]);
                
                $sentCount++;
            }

            return back()->with('success', "Expiry warnings sent to {$sentCount} user(s).");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send warnings: ' . $e->getMessage()]);
        }
    }
}
