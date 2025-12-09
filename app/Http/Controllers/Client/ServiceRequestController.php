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
            
            // Customization fields
            'requested_features' => 'nullable|string',
            'requested_skills' => 'nullable|string',
            'template_service_id' => 'nullable|integer|exists:services,id',
            'template_features' => 'nullable|string',
            'template_skills' => 'nullable|string',
            'template_duration' => 'nullable|integer',
            'template_price' => 'nullable|numeric',
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

        // Parse customization fields
        $requestedFeatures = $request->requested_features ? json_decode($request->requested_features, true) : null;
        $requestedSkills = $request->requested_skills ? json_decode($request->requested_skills, true) : null;
        $templateFeatures = $request->template_features ? json_decode($request->template_features, true) : null;
        $templateSkills = $request->template_skills ? json_decode($request->template_skills, true) : null;
        
        // Determine if client made customizations
        $hasCustomizations = false;
        if ($templateFeatures || $templateSkills) {
            // Check if features were modified
            if ($requestedFeatures !== null && $templateFeatures !== null) {
                $hasCustomizations = $requestedFeatures !== $templateFeatures;
            }
            // Check if skills were modified
            if (!$hasCustomizations && $requestedSkills !== null && $templateSkills !== null) {
                $hasCustomizations = $requestedSkills !== $templateSkills;
            }
        }

        // Create service request
        $serviceRequestId = DB::table('service_requests')->insertGetId([
            'client_id' => $userId,
            'contact_method' => $request->contact_method,
            'contact_details' => $request->contact_details,
            'service_type' => $request->service_type,
            'template_service_id' => $request->template_service_id,
            'project_name' => $request->project_name,
            'request_description' => $request->request_description,
            'deadline' => $request->deadline,
            'expectations' => $request->expectations,
            'additional_notes' => $request->additional_notes,
            'estimated_budget' => $request->estimated_budget,
            'requested_features' => $requestedFeatures ? json_encode($requestedFeatures) : null,
            'requested_skills' => $requestedSkills ? json_encode($requestedSkills) : null,
            'template_features' => $templateFeatures ? json_encode($templateFeatures) : null,
            'template_skills' => $templateSkills ? json_encode($templateSkills) : null,
            'has_customizations' => $hasCustomizations,
            'estimated_duration_days' => $request->template_duration,
            'template_base_price' => $request->template_price,
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

    /**
     * Show edit form for a service request
     * Only allowed for pending requests
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        $request = \App\Models\ServiceRequest::where('id', $id)
            ->where('client_id', $user->id)
            ->first();

        if (!$request) {
            return redirect()->route('client.requests')->with('error', 'Service request not found.');
        }

        // Only allow editing pending requests
        if (!$request->isPending()) {
            return redirect()->route('client.requests.show', $id)
                ->with('error', 'This request can no longer be edited. Only pending requests can be modified.');
        }

        // Get attachments
        $attachments = DB::table('request_attachments')
            ->where('service_request_id', $id)
            ->get();

        return view('client.requests.edit', compact('user', 'request', 'attachments'));
    }

    /**
     * Update a service request
     * Only allowed for pending requests
     */
    public function update(Request $httpRequest, $id)
    {
        $user = Auth::user();
        
        $serviceRequest = \App\Models\ServiceRequest::where('id', $id)
            ->where('client_id', $user->id)
            ->first();

        if (!$serviceRequest) {
            return redirect()->route('client.requests')->with('error', 'Service request not found.');
        }

        // Only allow updating pending requests
        if (!$serviceRequest->isPending()) {
            return redirect()->route('client.requests.show', $id)
                ->with('error', 'This request can no longer be edited. Only pending requests can be modified.');
        }

        // Get allowed file extensions from config
        $extensions = $this->getAllowedExtensions();
        $maxSize = $this->getMaxFileSize();

        // Validate the request
        $validator = Validator::make($httpRequest->all(), [
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
            'requested_features' => 'nullable|string',
            'requested_skills' => 'nullable|string',
        ], [
            'attachments.*.mimes' => 'Unsupported file type. ' . $this->getHumanReadableFileTypes() . ' are allowed.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Update the service request
            $serviceRequest->update([
                'contact_method' => $httpRequest->contact_method,
                'contact_details' => $httpRequest->contact_details,
                'service_type' => $httpRequest->service_type,
                'project_name' => $httpRequest->project_name,
                'request_description' => $httpRequest->request_description,
                'deadline' => $httpRequest->deadline,
                'expectations' => $httpRequest->expectations,
                'additional_notes' => $httpRequest->additional_notes,
                'estimated_budget' => $httpRequest->estimated_budget,
                'requested_features' => $httpRequest->requested_features,
                'requested_skills' => $httpRequest->requested_skills,
            ]);

            // Handle new attachments if any
            if ($httpRequest->hasFile('attachments')) {
                $r2Service = app(CloudflareR2Service::class);
                
                foreach ($httpRequest->file('attachments') as $file) {
                    $path = $r2Service->uploadFile($file, 'attachments');
                    
                    DB::table('request_attachments')->insert([
                        'service_request_id' => $serviceRequest->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getClientMimeType(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('client.requests.show', $id)
                ->with('success', 'Service request updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update service request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update service request. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete a service request
     * Only allowed for pending requests
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        $serviceRequest = \App\Models\ServiceRequest::where('id', $id)
            ->where('client_id', $user->id)
            ->first();

        if (!$serviceRequest) {
            return redirect()->route('client.requests')->with('error', 'Service request not found.');
        }

        // Only allow deleting pending requests
        if (!$serviceRequest->isPending()) {
            return redirect()->route('client.requests.show', $id)
                ->with('error', 'This request can no longer be deleted. Only pending requests can be removed.');
        }

        try {
            DB::beginTransaction();

            // Delete attachments from R2
            $attachments = DB::table('request_attachments')
                ->where('service_request_id', $id)
                ->get();

            $r2Service = app(CloudflareR2Service::class);
            foreach ($attachments as $attachment) {
                try {
                    $r2Service->deleteFile($attachment->file_path);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete attachment from R2: ' . $e->getMessage());
                }
            }

            // Delete attachment records
            DB::table('request_attachments')->where('service_request_id', $id)->delete();

            // Delete the service request
            $serviceRequest->delete();

            DB::commit();

            return redirect()->route('client.requests')
                ->with('success', 'Service request deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete service request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete service request. Please try again.');
        }
    }
}
