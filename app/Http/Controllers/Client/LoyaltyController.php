<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
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
     * Display loyalty dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // Get user statistics
        $stats = $this->loyaltyService->getUserStatistics($user);

        // Get current tier benefits
        $currentTierBenefits = $this->loyaltyService->getTierBenefits($loyaltyPoint->tier);

        // Get next tier benefits if not at max tier
        $nextTierBenefits = null;
        if ($stats['next_tier']) {
            $nextTierBenefits = $this->loyaltyService->getTierBenefits($stats['next_tier']);
        }

        // Get all tiers for progress display
        $allTiers = $this->loyaltyService->getAllTiers();

        // Get recent transactions
        $recentTransactions = $user->loyaltyTransactions()
            ->with(['serviceRequest', 'payment'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Points expiring soon
        $expiringPoints = $user->loyaltyTransactions()
            ->earned()
            ->where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>', now())
            ->orderBy('expires_at')
            ->get();

        return view('client.loyalty.dashboard', compact(
            'loyaltyPoint',
            'stats',
            'currentTierBenefits',
            'nextTierBenefits',
            'allTiers',
            'recentTransactions',
            'expiringPoints'
        ));
    }

    /**
     * Display transaction history
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        $query = $user->loyaltyTransactions()
            ->with(['serviceRequest', 'payment', 'coupon']);

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

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get summary statistics (named $stats to match what the view expects)
        $stats = [
            'total_earned' => $user->loyaltyTransactions()->earned()->sum('points'),
            'total_redeemed' => abs($user->loyaltyTransactions()->redeemed()->sum('points')),
            'total_expired' => abs($user->loyaltyTransactions()->where('transaction_type', 'expired')->sum('points')),
            'available_points' => $loyaltyPoint->available_points,
        ];

        return view('client.loyalty.transactions', compact('transactions', 'stats'));
    }

    /**
     * Redeem loyalty points for discount
     */
    public function redeemPoints(Request $request, ServiceRequest $serviceRequest)
    {
        // Verify ownership
        if ($serviceRequest->client_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if request is in correct status
        if (!in_array($serviceRequest->status, ['approved', 'pending_payment'])) {
            return back()->withErrors(['error' => 'Points can only be redeemed for approved requests.']);
        }

        // Check if points already redeemed
        if ($serviceRequest->loyalty_points_used > 0) {
            return back()->withErrors(['error' => 'Loyalty points have already been redeemed for this request.']);
        }

        $request->validate([
            'points' => 'required|integer|min:1',
        ]);

        try {
            $user = Auth::user();
            $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

            // Validate points availability
            if ($request->points > $loyaltyPoint->available_points) {
                return back()->withErrors(['points' => 'You do not have enough points available.']);
            }

            // Check minimum redemption
            $minRedemption = config('loyalty.points.minimum_redemption', 100);
            if ($request->points < $minRedemption) {
                return back()->withErrors(['points' => "Minimum redemption is {$minRedemption} points."]);
            }

            // Apply loyalty discount
            $this->loyaltyService->applyLoyaltyDiscount($serviceRequest, $request->points);

            $discount = $this->loyaltyService->convertPointsToDiscount($request->points);

            return back()->with('success', "{$request->points} points redeemed! You save ₱" . number_format($discount, 2));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove redeemed points from service request
     */
    public function removeRedemption(ServiceRequest $serviceRequest)
    {
        // Verify ownership
        if ($serviceRequest->client_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if request has redeemed points
        if ($serviceRequest->loyalty_points_used <= 0) {
            return back()->withErrors(['error' => 'No loyalty points redeemed for this request.']);
        }

        // Check if payment is already in progress
        if ($serviceRequest->status === 'paid' || $serviceRequest->payments()->completed()->exists()) {
            return back()->withErrors(['error' => 'Cannot remove points after payment is completed.']);
        }

        try {
            // Refund the points
            $this->loyaltyService->refundLoyaltyPoints($serviceRequest);

            // Restore the original budget
            $serviceRequest->update([
                'approved_budget' => $serviceRequest->approved_budget + $serviceRequest->loyalty_discount_amount,
                'loyalty_points_used' => 0,
                'loyalty_discount_amount' => 0,
                'total_discount_amount' => max(0, ($serviceRequest->total_discount_amount ?? 0) - $serviceRequest->loyalty_discount_amount),
                'loyalty_discount_applied_at' => null,
            ]);

            return back()->with('success', 'Loyalty points redemption removed and points refunded.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to remove redemption: ' . $e->getMessage()]);
        }
    }

    /**
     * Get loyalty dashboard widget data (AJAX)
     */
    public function widgetData()
    {
        $user = Auth::user();
        $stats = $this->loyaltyService->getUserStatistics($user);

        return response()->json($stats);
    }

    /**
     * Calculate points that would be earned for a given amount (AJAX)
     */
    public function calculateEarning(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();
        $earningRate = $loyaltyPoint->getEarningRate();

        // Calculate points
        $basePoints = floor($request->amount / 100);
        $earnedPoints = floor($basePoints * ($earningRate / 100));

        return response()->json([
            'points' => max(1, $earnedPoints),
            'rate' => $earningRate . '%',
            'tier' => ucfirst($loyaltyPoint->tier),
        ]);
    }

    /**
     * Calculate discount for given points (AJAX)
     */
    public function calculateDiscount(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'order_amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $loyaltyPoint = $user->getOrCreateLoyaltyPoints();

        // Check points availability
        if ($request->points > $loyaltyPoint->available_points) {
            return response()->json([
                'valid' => false,
                'message' => 'You do not have enough points available.',
            ]);
        }

        // Check minimum redemption
        $minRedemption = config('loyalty.points.minimum_redemption', 100);
        if ($request->points < $minRedemption) {
            return response()->json([
                'valid' => false,
                'message' => "Minimum redemption is {$minRedemption} points.",
            ]);
        }

        // Calculate discount
        $discount = $this->loyaltyService->convertPointsToDiscount($request->points);

        // Check maximum redemption percentage
        $maxRedemptionPercentage = config('loyalty.points.maximum_redemption_percentage', 50);
        $maxDiscount = $request->order_amount * ($maxRedemptionPercentage / 100);

        if ($discount > $maxDiscount) {
            $maxPoints = floor($maxDiscount / config('loyalty.points.conversion_rate', 1));
            return response()->json([
                'valid' => false,
                'message' => "Maximum {$maxRedemptionPercentage}% of order value can be redeemed. Use up to {$maxPoints} points.",
                'max_points' => $maxPoints,
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'final_amount' => $request->order_amount - $discount,
            'message' => "You will save ₱" . number_format($discount, 2),
        ]);
    }
}
