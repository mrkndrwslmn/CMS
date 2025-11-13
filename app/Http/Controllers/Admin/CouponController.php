<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Display a listing of coupons
     */
    public function index(Request $request)
    {
        $query = Coupon::with(['creator', 'specificUser', 'specificRequest']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by coupon type
        if ($request->filled('type')) {
            $query->where('coupon_type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Search by code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $coupons = $query->paginate(15);

        // Get statistics
        $stats = $this->couponService->getCouponStatistics();

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create()
    {
        $clients = User::where('role', 'client')
            ->orderBy('first_name')
            ->get();

        $serviceRequests = ServiceRequest::where('status', 'approved')
            ->whereNull('applied_coupon_id')
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.coupons.create', compact('clients', 'serviceRequests'));
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code|alpha_dash',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'coupon_type' => 'required|in:public,user_specific,request_specific',
            'specific_user_id' => 'required_if:coupon_type,user_specific|nullable|exists:users,id',
            'specific_request_id' => 'required_if:coupon_type,request_specific|nullable|exists:service_requests,id',
            'max_total_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'status' => 'required|in:active,inactive',
            'admin_notes' => 'nullable|string',
            'stackable_with_loyalty_tier' => 'boolean',
            'stackable_with_points' => 'boolean',
        ]);

        // Validate percentage discount
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Percentage discount cannot exceed 100%'])->withInput();
        }

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);
        $validated['created_by'] = Auth::id();

        try {
            $coupon = Coupon::create($validated);

            return redirect()
                ->route('admin.coupons.show', $coupon)
                ->with('success', "Coupon '{$coupon->code}' created successfully!");
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to create coupon: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified coupon
     */
    public function show(Coupon $coupon)
    {
        $coupon->load(['creator', 'specificUser', 'specificRequest', 'usages.user', 'usages.serviceRequest']);

        $stats = $this->couponService->getCouponStatistics($coupon);

        // Get recent usages
        $recentUsages = $coupon->usages()
            ->with(['user', 'serviceRequest'])
            ->orderBy('used_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.coupons.show', compact('coupon', 'stats', 'recentUsages'));
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit(Coupon $coupon)
    {
        $clients = User::where('role', 'client')
            ->orderBy('first_name')
            ->get();

        $serviceRequests = ServiceRequest::where('status', 'approved')
            ->where(function ($q) use ($coupon) {
                $q->whereNull('applied_coupon_id')
                  ->orWhere('applied_coupon_id', $coupon->id);
            })
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.coupons.edit', compact('coupon', 'clients', 'serviceRequests'));
    }

    /**
     * Update the specified coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|alpha_dash|unique:coupons,code,' . $coupon->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'coupon_type' => 'required|in:public,user_specific,request_specific',
            'specific_user_id' => 'required_if:coupon_type,user_specific|nullable|exists:users,id',
            'specific_request_id' => 'required_if:coupon_type,request_specific|nullable|exists:service_requests,id',
            'max_total_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'status' => 'required|in:active,inactive,expired',
            'admin_notes' => 'nullable|string',
            'stackable_with_loyalty_tier' => 'boolean',
            'stackable_with_points' => 'boolean',
        ]);

        // Validate percentage discount
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Percentage discount cannot exceed 100%'])->withInput();
        }

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);

        try {
            $coupon->update($validated);

            return redirect()
                ->route('admin.coupons.show', $coupon)
                ->with('success', "Coupon '{$coupon->code}' updated successfully!");
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to update coupon: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified coupon (soft delete)
     */
    public function destroy(Coupon $coupon)
    {
        try {
            // Check if coupon is being used
            if ($coupon->usages()->pending()->exists()) {
                return back()->withErrors(['error' => 'Cannot delete coupon with pending usage.']);
            }

            $code = $coupon->code;
            $coupon->delete();

            return redirect()
                ->route('admin.coupons.index')
                ->with('success', "Coupon '{$code}' deleted successfully!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete coupon: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle coupon status (active/inactive)
     */
    public function toggleStatus(Coupon $coupon)
    {
        try {
            $newStatus = $coupon->status === 'active' ? 'inactive' : 'active';
            $coupon->update(['status' => $newStatus]);

            return back()->with('success', "Coupon status changed to {$newStatus}.");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to toggle status: ' . $e->getMessage()]);
        }
    }

    /**
     * View coupon usage history
     */
    public function usageHistory(Coupon $coupon)
    {
        $usages = $coupon->usages()
            ->with(['user', 'serviceRequest', 'payment'])
            ->orderBy('used_at', 'desc')
            ->paginate(20);

        $stats = $this->couponService->getCouponStatistics($coupon);

        return view('admin.coupons.usage-history', compact('coupon', 'usages', 'stats'));
    }

    /**
     * Bulk generate coupons
     */
    public function bulkGenerate(Request $request)
    {
        $validated = $request->validate([
            'prefix' => 'required|string|max:20|alpha_dash',
            'count' => 'required|integer|min:1|max:100',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'coupon_type' => 'required|in:public',
            'max_total_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'required|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Percentage discount cannot exceed 100%'])->withInput();
        }

        try {
            $generated = [];
            $prefix = strtoupper($validated['prefix']);

            for ($i = 0; $i < $validated['count']; $i++) {
                $attempts = 0;
                do {
                    $code = $prefix . '-' . strtoupper(Str::random(6));
                    $exists = Coupon::where('code', $code)->exists();
                    $attempts++;
                } while ($exists && $attempts < 10);

                if ($attempts >= 10) {
                    continue; // Skip if couldn't generate unique code
                }

                $coupon = Coupon::create([
                    'code' => $code,
                    'name' => "Bulk Generated - {$code}",
                    'description' => $validated['description'] ?? null,
                    'discount_type' => $validated['discount_type'],
                    'discount_value' => $validated['discount_value'],
                    'max_discount_amount' => $validated['max_discount_amount'] ?? null,
                    'min_purchase_amount' => $validated['min_purchase_amount'] ?? 0,
                    'coupon_type' => 'public',
                    'max_total_uses' => $validated['max_total_uses'] ?? null,
                    'max_uses_per_user' => $validated['max_uses_per_user'],
                    'valid_from' => $validated['valid_from'] ?? null,
                    'valid_until' => $validated['valid_until'] ?? null,
                    'status' => $validated['status'],
                    'created_by' => Auth::id(),
                    'stackable_with_loyalty_tier' => $validated['stackable_with_loyalty_tier'] ?? false,
                    'stackable_with_points' => $validated['stackable_with_points'] ?? true,
                ]);

                $generated[] = $coupon->code;
            }

            return back()->with('success', count($generated) . ' coupons generated successfully!')->with('generated_codes', $generated);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to generate coupons: ' . $e->getMessage()]);
        }
    }

    /**
     * Check if coupon code is available (AJAX)
     */
    public function checkCode(Request $request)
    {
        $code = strtoupper($request->input('code'));
        $exists = Coupon::where('code', $code)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'This code is already taken.' : 'Code is available.',
        ]);
    }
}
