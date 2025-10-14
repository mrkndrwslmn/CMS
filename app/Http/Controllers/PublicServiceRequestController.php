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
                        'phone' => $request->contact_method === 'phone' ? $request->contact_details : null,
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

        // Insert form data into the forms table
        $formId = DB::table('forms')->insertGetId([
            'client_id' => $userId,
            'contact_method' => $request->contact_method,
            'contact_details' => $request->contact_details,
            'service_type' => $request->service_type,
            'project_name' => $request->project_name,
            'request_description' => $request->request_description,
            'projectDescription' => $request->request_description, // Also populate the old column for backward compatibility
            'deadline' => $request->deadline,
            'expectations' => $request->expectations,
            'additional_notes' => $request->additional_notes,
            'status' => 'pending',
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Handle file uploads
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

                // Insert into form_files table
                DB::table('form_files')->insert([
                    'formid' => $formId,
                    'filename' => $originalName,
                    'filepath' => '/storage/' . $relativePath,
                    'filetype' => $file->getClientMimeType(),
                    'filesize' => $file->getSize(),
                    'uploaded_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create notifications for admins
        $adminUsers = DB::table('users')->where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            DB::table('notifications')->insert([
                'user_id' => $admin->id ?? $admin->userID,
                'type' => 'new_service_request',
                'title' => 'New Service Request',
                'message' => "New service request '{$request->project_name}' submitted" . ($isNewUser ? ' by a new user' : ''),
                'data' => json_encode(['form_id' => $formId]),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // If new user was created, send credentials via email
        if ($isNewUser && $generatedPassword) {
            try {
                $userData = DB::table('users')->where('id', $userId)->orWhere('userID', $userId)->first();
                
                // Send email with credentials
                Mail::to($request->email)->send(new NewUserCredentials(
                    $request->full_name,
                    $request->email,
                    $generatedPassword
                ));
                
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
                Log::error('Failed to send credentials email: ' . $e->getMessage());
                
                return redirect()->route('get-started')->with([
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
            return redirect()->route('get-started')->with('success', 'Your service request has been submitted successfully! We will review it and get back to you soon.');
        }
    }
}
