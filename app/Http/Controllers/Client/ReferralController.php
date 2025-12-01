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
        $this->middleware('role:client,adiutor'); // Allow both clients and adiutors
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

    /**
     * Display referral credits and withdrawal page
     */
    public function credits(): View
    {
        $user = Auth::user();
        
        // Get credits info
        $availableCredits = $user->referral_credits ?? 0;
        $pendingCredits = $user->referral_credits_pending ?? 0;
        $withdrawnCredits = $user->referral_credits_withdrawn ?? 0;
        
        // Get withdrawal history
        $withdrawals = $user->referralCreditWithdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get credit transactions
        $transactions = $user->referralCreditTransactions()
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        // Get withdrawal config
        $minWithdrawal = config('referral.benefits.credits.minimum_withdrawal', 1000);
        $withdrawalMethods = config('referral.benefits.credits.withdrawal_methods', []);
        
        return view('client.referrals.credits', compact(
            'availableCredits',
            'pendingCredits',
            'withdrawnCredits',
            'withdrawals',
            'transactions',
            'minWithdrawal',
            'withdrawalMethods'
        ));
    }

    /**
     * Request withdrawal of referral credits
     */
    public function requestWithdrawal(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'withdrawal_method' => 'required|string',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        try {
            $withdrawalDetails = [
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'method' => $request->withdrawal_method,
            ];

            // Add bank details if bank transfer
            if ($request->withdrawal_method === 'bank_transfer') {
                $request->validate([
                    'bank_name' => 'required|string|max:255',
                ]);
                $withdrawalDetails['bank_name'] = $request->bank_name;
            }

            $withdrawal = $this->referralService->requestWithdrawal(
                $user,
                $request->amount,
                $request->withdrawal_method,
                $withdrawalDetails,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal request submitted successfully!',
                'withdrawal' => [
                    'number' => $withdrawal->withdrawal_number,
                    'amount' => $withdrawal->amount,
                    'status' => $withdrawal->status,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * View specific withdrawal details
     */
    public function showWithdrawal($id): View
    {
        $user = Auth::user();
        
        $withdrawal = $user->referralCreditWithdrawals()
            ->with('transactions')
            ->findOrFail($id);
        
        return view('client.referrals.withdrawal-details', compact('withdrawal'));
    }

    /**
     * Cancel pending withdrawal
     */
    public function cancelWithdrawal($id): JsonResponse
    {
        $user = Auth::user();
        
        $withdrawal = $user->referralCreditWithdrawals()->findOrFail($id);
        
        if (!$withdrawal->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Only pending withdrawals can be cancelled.',
            ], 400);
        }

        try {
            $this->referralService->rejectWithdrawal(
                $withdrawal,
                'Cancelled by user',
                $user
            );

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal cancelled successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel withdrawal.',
            ], 500);
        }
    }
}
