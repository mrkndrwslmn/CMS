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
use App\Models\ServiceRequest;
use App\Notifications\NewServiceRequestNotification;
use App\Rules\RecaptchaValidation;
use App\Services\CloudflareR2Service;
use App\Traits\ValidatesDocuments;

class PublicServiceRequestController extends Controller
{
    use ValidatesDocuments;
    /**
     * Show the public service request form (Get Started page)
     */
    public function create()
    {
        // Check if user is already logged in
        $user = Auth::user();
        $isLoggedIn = $user !== null;
        
        return view('public.get-started', compact('user', 'isLoggedIn'));
    }

    /**
     * Store a new service request from public form
     * Automatically creates user account if they're not logged in
     */
    public function store(Request $request)
    {
        // Get allowed file extensions from config
        $extensions = $this->getAllowedExtensions();
        $maxSize = $this->getMaxFileSize();
        
        // If user is logged in, add user_id to request data
        if (Auth::check()) {
            $request->merge(['user_id' => Auth::id()]);
        }
        
        // Build validation rules dynamically
        $rules = [
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
            'file_upload.*' => "nullable|file|max:{$maxSize}|mimes:{$extensions}",
            
            // Template data
            'template_service_id' => 'nullable|integer',
            'template_features' => 'nullable|string',
            'template_skills' => 'nullable|string',
            'template_duration' => 'nullable|integer',
            'template_price' => 'nullable|numeric',
            
            // Requested/customized features and skills
            'requested_features' => 'nullable|string',
            'requested_skills' => 'nullable|string',
        ];
        
        // Only require reCAPTCHA for non-authenticated users
        if (!Auth::check()) {
            $rules['g-recaptcha-response'] = ['required', new RecaptchaValidation()];
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), $rules, [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
            'file_upload.*.mimes' => 'Unsupported file type. ' . $this->getHumanReadableFileTypes() . ' are allowed.',
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

        // Debug: Log what we're receiving
        Log::info('Service Request Form Data', [
            'requested_features' => $request->requested_features,
            'requested_skills' => $request->requested_skills,
            'template_features' => $request->template_features,
            'template_skills' => $request->template_skills,
        ]);

        // Create service request using Eloquent model (handles JSON casting automatically)
        $serviceRequest = ServiceRequest::create([
            'client_id' => $userId,
            'contact_method' => $request->contact_method,
            'contact_details' => $request->contact_details,
            'service_type' => $request->service_type,
            'project_name' => $request->project_name,
            'request_description' => $request->request_description,
            'deadline' => $request->deadline,
            'expectations' => $request->expectations,
            'additional_notes' => $request->additional_notes,
            
            // Template data (original values from service template)
            'template_service_id' => $request->template_service_id,
            'template_features' => $request->template_features ? json_decode($request->template_features, true) : null,
            'template_skills' => $request->template_skills ? json_decode($request->template_skills, true) : null,
            'estimated_duration_days' => $request->template_duration,
            'template_base_price' => $request->template_price,
            
            // Requested/customized features and skills (decode JSON from form)
            'requested_features' => $request->requested_features ? json_decode($request->requested_features, true) : null,
            'requested_skills' => $request->requested_skills ? json_decode($request->requested_skills, true) : null,
            
            // Check if user customized the template
            'has_customizations' => $this->hasCustomizations($request),
            
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        // Handle file uploads using Cloudflare R2
        if ($request->hasFile('file_upload')) {
            $r2Service = new CloudflareR2Service();

            foreach ($request->file('file_upload') as $file) {
                $uploadResult = $r2Service->uploadPublicFile($file, $userId);
                
                if ($uploadResult['success']) {
                    // Insert into documents table
                    DB::table('documents')->insert([
                        'service_request_id' => $serviceRequest->id,
                        'client_id' => $userId,
                        'uploaded_by' => $userId,
                        'fileName' => $uploadResult['original_name'],
                        'filePath' => $uploadResult['url'], // Store R2 URL instead of local path
                        'fileType' => $uploadResult['mime_type'],
                        'fileSize' => $uploadResult['size'],
                        'document_type' => 'requirement',
                        'is_public' => false,
                        'is_archived' => false,
                        'uploadedAt' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    Log::error('Failed to upload file to R2: ' . $uploadResult['error'], [
                        'file' => $file->getClientOriginalName(),
                        'service_request_id' => $serviceRequest->id,
                        'user_id' => $userId,
                    ]);
                    // Continue with other files, don't fail the entire request
                }
            }
        }

        // Create notifications for admins
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

    /**
     * Check if user customized the template features or skills
     */
    private function hasCustomizations(Request $request): bool
    {
        // If no template data exists, no customizations
        if (!$request->template_service_id) {
            return false;
        }
        
        // Compare template features with requested features
        $templateFeatures = $request->template_features;
        $requestedFeatures = $request->requested_features;
        
        // Compare template skills with requested skills
        $templateSkills = $request->template_skills;
        $requestedSkills = $request->requested_skills;
        
        // If either features or skills are different, it's customized
        return ($templateFeatures !== $requestedFeatures) || ($templateSkills !== $requestedSkills);
    }
}
