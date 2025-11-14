<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ReferralInvitationMail;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ReferralController extends Controller
{
    protected ReferralService $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
        $this->middleware('auth');
        $this->middleware('role:client');
    }

    /**
     * Display the referral dashboard
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        
        // Get or create referral code
        $referralCode = $user->getOrCreateReferralCode();
        
        // Get referral statistics
        $stats = $this->referralService->getReferralStats($user);
        
        // Get referral history
        $referrals = $user->referralsMade()
            ->with(['referred', 'referredCoupon', 'referrerCoupon'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get pending referrals (not yet completed payment)
        $pendingReferrals = $user->referralsMade()
            ->where('status', 'pending')
            ->with('referred')
            ->get();
        
        // Get completed referrals (payment completed, rewards given)
        $completedReferrals = $user->referralsMade()
            ->whereIn('status', ['completed', 'rewarded'])
            ->with(['referred', 'referrerCoupon'])
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate potential earnings
        $potentialEarnings = $pendingReferrals->count() * config('referral.rewards.referrer.completion_points', 1000);
        
        return view('client.referrals.dashboard', compact(
            'referralCode',
            'stats',
            'referrals',
            'pendingReferrals',
            'completedReferrals',
            'potentialEarnings'
        ));
    }

    /**
     * Display the sharing page
     */
    public function share(): View
    {
        $user = Auth::user();
        $referralCode = $user->getOrCreateReferralCode();
        
        // Generate referral URL
        $referralUrl = route('register', ['ref' => $referralCode->code]);
        
        // Generate share texts
        $shareTexts = [
            'email' => "Hi! I've been using this amazing service and thought you'd love it too. Join using my referral code {$referralCode->code} and get 500 bonus points + a 15% discount coupon!",
            'sms' => "Check out this service! Use my code {$referralCode->code} to get 500 points + 15% off. Sign up: {$referralUrl}",
            'social' => "Join me and get rewarded! 🎁 Use code {$referralCode->code} for 500 bonus points + 15% discount! 🚀",
        ];
        
        return view('client.referrals.share', compact(
            'referralCode',
            'referralUrl',
            'shareTexts'
        ));
    }

    /**
     * Get referral code as JSON (for AJAX)
     */
    public function getCode(): JsonResponse
    {
        $user = Auth::user();
        $referralCode = $user->getOrCreateReferralCode();
        
        return response()->json([
            'success' => true,
            'code' => $referralCode->code,
            'url' => route('register', ['ref' => $referralCode->code]),
            'stats' => $this->referralService->getReferralStats($user),
        ]);
    }

    /**
     * Validate a referral code (AJAX endpoint)
     */
    public function validateCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:20',
        ]);
        
        $result = $this->referralService->validateReferralCode($request->code);
        
        return response()->json($result);
    }

    /**
     * Get referral statistics (AJAX endpoint)
     */
    public function getStats(): JsonResponse
    {
        $user = Auth::user();
        $stats = $this->referralService->getReferralStats($user);
        
        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    /**
     * Display referral history
     */
    public function history(Request $request): View
    {
        $user = Auth::user();
        
        $query = $user->referralsMade()
            ->with(['referred', 'referredCoupon', 'referrerCoupon', 'firstPayment']);
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('referred', function ($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                  ->orWhere('lastName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $referrals = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('client.referrals.history', compact('referrals'));
    }

    /**
     * Send referral invitation via email
     */
    public function sendInvitation(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'nullable|string|max:500',
        ]);
        
        $user = Auth::user();
        $referralCode = $user->getOrCreateReferralCode();
        
        try {
            // Send invitation email
            Mail::to($request->email)->queue(
                new ReferralInvitationMail($user, $referralCode, $request->message)
            );
            
            Log::info('Referral invitation sent', [
                'referrer_id' => $user->id,
                'referrer_email' => $user->email,
                'invited_email' => $request->email,
                'referral_code' => $referralCode->code,
                'has_personal_message' => !empty($request->message),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Invitation sent successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send referral invitation', [
                'referrer_id' => $user->id,
                'invited_email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invitation. Please try again.',
            ], 500);
        }
    }

    /**
     * Generate referral link with tracking parameters
     */
    public function generateLink(Request $request): JsonResponse
    {
        $user = Auth::user();
        $referralCode = $user->getOrCreateReferralCode();
        
        $source = $request->input('source', 'direct'); // email, facebook, twitter, etc.
        
        $url = route('register', [
            'ref' => $referralCode->code,
            'source' => $source,
        ]);
        
        return response()->json([
            'success' => true,
            'url' => $url,
            'code' => $referralCode->code,
        ]);
    }
}
