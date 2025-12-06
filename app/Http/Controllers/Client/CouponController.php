<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\ServiceRequest;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display available coupons for the client
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get available coupons
        $availableCoupons = $this->couponService->getAvailableCouponsForUser($user);

        // Get user's coupon usage history
        $usageHistory = $user->couponUsages()
            ->with(['coupon', 'serviceRequest'])
            ->orderBy('used_at', 'desc')
            ->paginate(10);

        // Get statistics
        $stats = [
            'total_coupons_available' => $availableCoupons->count(),
            'total_coupons_used' => $user->couponUsages()->completed()->count(),
            'total_savings' => $user->couponUsages()->completed()->sum('discount_amount'),
        ];

        return view('client.coupons.index', compact('availableCoupons', 'usageHistory', 'stats'));
    }

    /**
     * Validate coupon code via AJAX
     */
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $validation = $this->couponService->validateCoupon(
                $request->code,
                Auth::user(),
                $request->amount
            );

            return response()->json($validation);
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'An error occurred while validating the coupon.',
            ], 500);
        }
    }

    /**
     * Apply coupon to service request
     */
    public function applyCoupon(Request $request, ServiceRequest $serviceRequest)
    {
        // Verify ownership
        if ($serviceRequest->client_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        // Check if request is in correct status
        if (!in_array($serviceRequest->status, ['approved', 'pending_payment'])) {
            $message = 'Coupon can only be applied to approved requests.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->withErrors(['error' => $message]);
        }

        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        try {
            // Find the coupon
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

            if (!$coupon) {
                $message = 'Invalid coupon code.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return back()->withErrors(['coupon_code' => $message]);
            }

            // Validate coupon
            $validation = $this->couponService->validateCoupon(
                $request->coupon_code,
                Auth::user(),
                $serviceRequest->approved_budget
            );

            if (!$validation['valid']) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $validation['message']], 422);
                }
                return back()->withErrors(['coupon_code' => $validation['message']]);
            }

            // Apply coupon
            $this->couponService->applyCouponToRequest($serviceRequest, $coupon);

            $successMessage = "Coupon applied! You save ₱" . number_format($validation['discount'], 2);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => $successMessage,
                    'discount' => $validation['discount'],
                ]);
            }
            
            return back()->with('success', $successMessage);
        } catch (\Exception $e) {
            $message = 'Failed to apply coupon: ' . $e->getMessage();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->withErrors(['error' => $message]);
        }
    }

    /**
     * Remove coupon from service request
     */
    public function removeCoupon(ServiceRequest $serviceRequest)
    {
        // Verify ownership
        if ($serviceRequest->client_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if request has a coupon
        if (!$serviceRequest->applied_coupon_id) {
            return back()->withErrors(['error' => 'No coupon applied to this request.']);
        }

        // Check if coupon was auto-applied (admin bundled)
        if ($serviceRequest->coupon_auto_applied) {
            return back()->withErrors(['error' => 'This coupon was bundled by admin and cannot be removed.']);
        }

        try {
            $this->couponService->removeCouponFromRequest($serviceRequest);

            return back()->with('success', 'Coupon removed successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to remove coupon: ' . $e->getMessage()]);
        }
    }

    /**
     * View specific coupon details
     */
    public function show(Coupon $coupon)
    {
        $user = Auth::user();

        // Check if user can view this coupon
        if (!$coupon->canBeUsedBy($user)) {
            abort(403, 'This coupon is not available for you.');
        }

        // Get user's usage of this coupon
        $userUsages = $user->couponUsages()
            ->where('coupon_id', $coupon->id)
            ->with('serviceRequest')
            ->orderBy('used_at', 'desc')
            ->get();

        return view('client.coupons.show', compact('coupon', 'userUsages'));
    }

    /**
     * Copy coupon code to clipboard (returns JSON for AJAX)
     */
    public function copyCode(Coupon $coupon)
    {
        $user = Auth::user();

        // Check if user can view this coupon
        if (!$coupon->canBeUsedBy($user)) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not available for you.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'message' => 'Coupon code copied to clipboard!',
        ]);
    }
}
