<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\NewUserCredentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\NewServiceRequestNotification;
use App\Services\CloudflareR2Service;
use App\Services\CouponService;
use App\Services\LoyaltyService;
use App\Traits\ValidatesDocuments;

class ServiceRequestController extends Controller
{
    use ValidatesDocuments;
    
    protected CouponService $couponService;
    protected LoyaltyService $loyaltyService;

    public function __construct(CouponService $couponService, LoyaltyService $loyaltyService)
    {
        $this->couponService = $couponService;
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Show service request creation form
     */
    public function create()
    {
        $user = Auth::user();
        $isLoggedIn = $user !== null;
        return view('client.requests.create', compact('user', 'isLoggedIn'));
    }

    /**
     * Store a new service request
     * Handles both authenticated and non-authenticated users
     */
    public function store(Request $request)
    {
        // Get allowed file extensions from config
        $extensions = $this->getAllowedExtensions();
        $maxSize = $this->getMaxFileSize();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            // User fields (only required if not logged in)
            'full_name' => 'required_without_all:user_id|string|max:255',
            'email' => 'required_without_all:user_id|email|max:255',
            'password' => 'required_without_all:user_id|string|min:8',
            'phone' => 'nullable|string|max:255',
            
            // Service request fields
            'contact_method' => 'required|in:email,messenger,phone',
            'contact_details' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'request_description' => 'required|string|max:2000',
            'deadline' => 'nullable|date|after:today',
            'expectations' => 'nullable|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
            'estimated_budget' => 'nullable|numeric|min:0|max:999999.99',
            'attachments.*' => "nullable|file|max:{$maxSize}|mimes:{$extensions}",
        ], [
            'attachments.*.mimes' => 'Unsupported file type. ' . $this->getHumanReadableFileTypes() . ' are allowed.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userId = null;
        $isNewUser = false;
        $generatedPassword = null;

        // If user is not logged in, create or find user account
        if (!Auth::check()) {
            $email = $request->email;
            
            // Check if user already exists
            $existingUser = DB::table('users')->where('email', $email)->first();
            
            if ($existingUser) {
                // User exists, use their ID
                $userId = $existingUser->id ?? $existingUser->userID;
            } else {
                // Create new user account
                $generatedPassword = $request->password;
                
                try {
                    $userId = DB::table('users')->insertGetId([
                        'fullName' => $request->full_name,
                        'email' => $email,
                        'password' => Hash::make($generatedPassword),
                        'role' => 'client',
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $isNewUser = true;
                    
                    // Create client profile
                    DB::table('client_profiles')->insert([
                        'user_id' => $userId,
                        'phone' => $request->phone,
                        'preferred_contact_method' => $request->contact_method,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // If there's a duplicate entry error, fetch the user
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $existingUser = DB::table('users')->where('email', $email)->first();
                        $userId = $existingUser->id ?? $existingUser->userID;
                        $isNewUser = false;
                    } else {
                        throw $e;
                    }
                }
            }
        } else {
            // User is logged in
            $userId = Auth::id();
        }

        // Create service request
        $serviceRequestId = DB::table('service_requests')->insertGetId([
            'client_id' => $userId,
            'contact_method' => $request->contact_method,
            'contact_details' => $request->contact_details,
            'service_type' => $request->service_type,
            'project_name' => $request->project_name,
            'request_description' => $request->request_description,
            'deadline' => $request->deadline,
            'expectations' => $request->expectations,
            'additional_notes' => $request->additional_notes,
            'estimated_budget' => $request->estimated_budget,
            'status' => 'pending',
            'priority' => $request->priority ?? 'medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle file uploads using Cloudflare R2
        if ($request->hasFile('attachments')) {
            $r2Service = new CloudflareR2Service();
            
            foreach ($request->file('attachments') as $file) {
                $uploadResult = $r2Service->uploadAttachment($file, $serviceRequestId);
                
                if ($uploadResult['success']) {
                    DB::table('request_attachments')->insert([
                        'service_request_id' => $serviceRequestId,
                        'original_filename' => $uploadResult['original_name'],
                        'stored_filename' => $uploadResult['stored_name'],
                        'file_path' => $uploadResult['path'], // Store R2 path
                        'file_url' => $uploadResult['url'], // Store R2 URL for easy access
                        'mime_type' => $uploadResult['mime_type'],
                        'file_size' => $uploadResult['size'],
                        'file_hash' => hash('md5', file_get_contents($file->getRealPath())),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    Log::error('Failed to upload attachment to R2: ' . $uploadResult['error'], [
                        'file' => $file->getClientOriginalName(),
                        'service_request_id' => $serviceRequestId,
                        'user_id' => Auth::id(),
                    ]);
                    // Continue with other files, don't fail the entire request
                }
            }
        }

        // Create notification for admins using Laravel's notification structure
        $serviceRequest = \App\Models\ServiceRequest::find($serviceRequestId);
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewServiceRequestNotification(
                $serviceRequest,
                $isNewUser
            ));
        }

        // If new user was created, send credentials via email
        if ($isNewUser && $generatedPassword) {
            try {
                // Send email with credentials
                Mail::to($request->email)->send(new NewUserCredentials(
                    $request->full_name,
                    $request->email,
                    $generatedPassword
                ));
                
                // Show success message with credentials
                return redirect()->route('client.requests.create')->with([
                    'success' => 'Your service request has been submitted successfully!',
                    'credentials' => [
                        'email' => $request->email,
                        'password' => $generatedPassword,
                    ]
                ]);
            } catch (\Exception $e) {
                // If email fails, still show credentials on screen
                Log::error('Failed to send credentials email: ' . $e->getMessage());
                
                return redirect()->route('client.requests.create')->with([
                    'success' => 'Your service request has been submitted successfully!',
                    'email_failed' => true,
                    'credentials' => [
                        'email' => $request->email,
                        'password' => $generatedPassword,
                    ]
                ]);
            }
        }

        // Regular success message for existing users or logged-in users
        if (Auth::check()) {
            return redirect()->route('client.requests')->with('success', 'Your service request has been submitted successfully! We will review it and get back to you soon.');
        } else {
            return redirect()->route('client.requests.create')->with('success', 'Your service request has been submitted successfully! We will review it and get back to you soon.');
        }
    }

    /**
     * Show a specific service request
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Use Eloquent to access relationships and methods
        $request = \App\Models\ServiceRequest::with(['project.milestones', 'approvedBy', 'appliedCoupon', 'couponUsage'])
            ->where('id', $id)
            ->where('client_id', $user->id)
            ->first();

        if (!$request) {
            return redirect()->route('client.requests')->with('error', 'Service request not found.');
        }

        // Get attachments
        $attachments = DB::table('request_attachments')
            ->where('service_request_id', $id)
            ->get();

        // Get associated project (already loaded via relationship)
        $project = $request->project;

        // Get available coupons if request is approved
        $availableCoupons = collect();
        if (in_array($request->status, ['approved', 'pending_payment']) && !$request->applied_coupon_id) {
            $availableCoupons = $this->couponService->getAvailableCouponsForUser($user);
        }

        // Get loyalty points info
        $loyaltyPoints = $user->getOrCreateLoyaltyPoints();
        $loyaltyStats = null;
        if (in_array($request->status, ['approved', 'pending_payment'])) {
            $loyaltyStats = $this->loyaltyService->getUserStatistics($user);
        }

        return view('client.requests.show', compact(
            'user',
            'request',
            'attachments',
            'project',
            'availableCoupons',
            'loyaltyPoints',
            'loyaltyStats'
        ));
    }

    /**
     * Show payment page for a service request
     * Now redirects to Maya payment checkout
     */
    public function showPayment($id)
    {
        // Redirect to Maya checkout instead of showing manual payment form
        return redirect()->route('client.maya.checkout', ['serviceRequestId' => $id]);
    }

    /**
     * Download attachment
     */
    public function downloadAttachment($requestId, $attachmentId)
    {
        $user = Auth::user();
        
        $attachment = DB::table('request_attachments')
            ->join('service_requests', 'request_attachments.service_request_id', '=', 'service_requests.id')
            ->where('request_attachments.id', $attachmentId)
            ->where('service_requests.client_id', $user->id)
            ->select('request_attachments.*')
            ->first();

        if (!$attachment) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Convert to model instance to use helper methods
        $attachmentModel = new \App\Models\RequestAttachment((array) $attachment);
        
        // Use the model's method to get the proper download URL
        return redirect($attachmentModel->getDownloadUrl());
    }
}
