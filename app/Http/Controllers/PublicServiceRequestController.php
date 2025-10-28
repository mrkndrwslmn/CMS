<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\NewUserCredentials;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\NewServiceRequestNotification;
use App\Rules\RecaptchaValidation;

class PublicServiceRequestController extends Controller
{
    /**
     * Show the public service request form (Get Started page)
     */
    public function create()
    {
        // Check if user is already logged in
        $user = Auth::user();
        $isLoggedIn = $user !== null;
        
        return view('get-started', compact('user', 'isLoggedIn'));
    }

    /**
     * Store a new service request from public form
     * Automatically creates user account if they're not logged in
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            // User fields (only required if not logged in)
            'full_name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255',
            'password' => 'required_without:user_id|string|min:8',
            
            // Service request fields
            'contact_method' => 'required|in:email,messenger,phone',
            'contact_details' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'request_description' => 'required|string|max:2000',
            'deadline' => 'nullable|date|after:today',
            'expectations' => 'nullable|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
            'file_upload.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip,rar',
            
            // reCAPTCHA validation
            'g-recaptcha-response' => ['required', new RecaptchaValidation()],
        ], [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
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
                $isNewUser = false;
            } else {
                // Create new user account
                $generatedPassword = $request->password;
                
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
                    'contact_phone' => $request->contact_method === 'phone' ? $request->contact_details : null,
                    'contact_email' => $request->contact_method === 'email' ? $request->contact_details : null,
                    'preferred_contact_methods' => json_encode([$request->contact_method]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // User is logged in
            $userId = Auth::id();
        }

        // Insert service request data into the service_requests table
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
            'status' => 'pending',
            'priority' => 'medium',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle file uploads (TODO: Implement documents table)
        if ($request->hasFile('file_upload')) {
            $uploadDir = storage_path('app/public/documents/user-uploads/');
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($request->file('file_upload') as $file) {
                $originalName = $file->getClientOriginalName();
                $uniqueName = uniqid() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $relativePath = 'documents/user-uploads/' . $uniqueName;
                
                // Store the file
                $file->storeAs('public/' . dirname($relativePath), basename($relativePath));

                // Insert into documents table
                DB::table('documents')->insert([
                    'service_request_id' => $serviceRequestId,
                    'client_id' => $userId,
                    'uploaded_by' => $userId,
                    'fileName' => $originalName,
                    'filePath' => '/storage/' . $relativePath,
                    'fileType' => $file->getClientMimeType(),
                    'fileSize' => $file->getSize(),
                    'document_type' => 'requirement',
                    'is_public' => false,
                    'is_archived' => false,
                    'uploadedAt' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create notifications for admins
        $serviceRequest = \App\Models\ServiceRequest::find($serviceRequestId);
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewServiceRequestNotification(
                $serviceRequest,
                $isNewUser ?? false
            ));
        }

        // If new user was created, send credentials via email
        if ($isNewUser && $generatedPassword) {
            Log::info('Attempting to send credentials email to: ' . $request->email);
            
            try {
                // Send email with credentials
                Mail::to($request->email)->send(new NewUserCredentials(
                    $request->full_name,
                    $request->email,
                    $generatedPassword
                ));
                
                Log::info('Credentials email sent successfully to: ' . $request->email);
                
                // Show success message with credentials
                return redirect()->route('get-started')->with([
                    'success' => 'Your service request has been submitted successfully!',
                    'credentials' => [
                        'email' => $request->email,
                        'password' => $generatedPassword,
                    ]
                ]);
            } catch (\Exception $e) {
                // If email fails, still show credentials on screen
                Log::error('Failed to send credentials email to ' . $request->email . ': ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                
                return redirect()->route('get-started')->with([
                    'success' => 'Your service request has been submitted successfully!',
                    'email_failed' => true,
                    'credentials' => [
                        'email' => $request->email,
                        'password' => $generatedPassword,
                    ],
                    'warning' => 'We couldn\'t send the email with your credentials. Please save them now!'
                ]);
            }
        }

        // Regular success message for existing users or logged-in users
        if (Auth::check()) {
            return redirect()->route('client.requests')->with('success', 'Your service request has been submitted successfully! We will review it and get back to you soon.');
        } else {
            return redirect()->route('get-started')->with('success', 'Your service request has been submitted successfully! We will review it and get back to you soon.');
        }
    }
}
