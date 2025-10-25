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

class ServiceRequestController extends Controller
{
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
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip,rar',
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

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('service-requests/' . $serviceRequestId, $storedName, 'public');

                DB::table('request_attachments')->insert([
                    'service_request_id' => $serviceRequestId,
                    'original_filename' => $originalName,
                    'stored_filename' => $storedName,
                    'file_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'file_hash' => hash_file('md5', $file->getPathname()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create notification for admins using Laravel's notification structure
        $userFullName = $isNewUser ? $request->full_name : (Auth::user()->fullName ?? 'Unknown');
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewServiceRequestNotification(
                $serviceRequestId,
                $request->project_name,
                $userFullName,
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
        $request = \App\Models\ServiceRequest::with(['project.milestones', 'approvedBy'])
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

        return view('client.requests.show', compact('user', 'request', 'attachments', 'project'));
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

        $filePath = storage_path('app/public/' . $attachment->file_path);
        return response()->download($filePath, $attachment->original_filename);
    }
}
