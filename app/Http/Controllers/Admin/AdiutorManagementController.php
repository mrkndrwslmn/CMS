<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdiutorAccountCreatedMail;
use App\Models\User;
use App\Models\AdiutorProfile;
use App\Models\Skill;
use App\Models\WalletTransaction;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for managing adiutor (freelancer/team member) users in the admin panel.
 * 
 * Handles CRUD operations for adiutors (users with role='adiutor'),
 * including profile management, earnings tracking, and project assignments.
 */
class AdiutorManagementController extends Controller
{
    /**
     * Display a paginated list of all adiutors with statistics.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'adiutor')
            ->with(['adiutorProfile', 'projectAssignments'])
            ->withCount([
                'projectAssignments',
                'projectAssignments as active_projects_count' => function($q) {
                    $q->whereIn('status', ['assigned', 'in_progress']);
                },
                'projectAssignments as completed_projects_count' => function($q) {
                    $q->where('status', 'completed');
                },
                'timeEntries'
            ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullName', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phoneNumber', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        // Exclude archived (status = 'archived') from the main list
        $query->where('status', '!=', 'archived');

        $adiutors = $query->paginate(15)->withQueryString();

        // Get statistics
        $totalEarnings = User::where('role', 'adiutor')
            ->where('status', '!=', 'archived')
            ->sum('work_earnings_balance');

        $totalProjects = DB::table('project_assignments')
            ->join('users', 'project_assignments.adiutor_id', '=', 'users.id')
            ->where('users.role', 'adiutor')
            ->where('users.status', '!=', 'archived')
            ->count();

        $stats = [
            'total_adiutors' => User::where('role', 'adiutor')->where('status', '!=', 'archived')->count(),
            'active_adiutors' => User::where('role', 'adiutor')->where('status', 'active')->count(),
            'total_projects' => $totalProjects,
            'total_earnings' => $totalEarnings,
            'archived_adiutors' => User::where('role', 'adiutor')->where('status', 'archived')->count(),
        ];

        return view('admin.adiutors.index', compact('adiutors', 'stats'));
    }

    /**
     * Display detailed information for a specific adiutor.
     */
    public function show($id): View
    {
        $adiutor = User::where('role', 'adiutor')
            ->with([
                'adiutorProfile.skills',
                'projectAssignments.project',
                'timeEntries' => fn($q) => $q->with('project')->latest()->take(10),
                'walletTransactions' => fn($q) => $q->latest()->take(20),
            ])
            ->withCount([
                'projectAssignments as total_projects_count',
                'projectAssignments as active_projects_count' => function($q) {
                    $q->whereIn('status', ['assigned', 'in_progress']);
                },
                'projectAssignments as completed_projects_count' => function($q) {
                    $q->where('status', 'completed');
                },
                'timeEntries as total_time_entries_count',
            ])
            ->findOrFail($id);

        // Calculate total hours worked (convert minutes to hours)
        $totalMinutes = TimeEntry::where('adiutor_id', $id)
            ->where('is_approved', 1)
            ->sum('duration_minutes');
        $totalHours = round($totalMinutes / 60, 2);

        // Calculate total earnings
        $totalEarnings = WalletTransaction::where('user_id', $id)
            ->where('transaction_type', 'work_earned')
            ->sum('amount');

        // Get average rating from feedback (if available)
        $averageRating = DB::table('feedbacks')
            ->where('adiutor_id', $id)
            ->avg('rating') ?? 0;

        // Statistics for the view
        $stats = [
            'total_projects' => $adiutor->total_projects_count,
            'completed_projects' => $adiutor->completed_projects_count,
            'total_hours' => $totalHours,
            'total_earnings' => $totalEarnings,
            'average_rating' => $averageRating,
        ];

        // Recent project assignments
        $recentProjects = $adiutor->projectAssignments()
            ->with('project.client')
            ->latest()
            ->take(5)
            ->get();

        // Recent time entries
        $recentTimeEntries = $adiutor->timeEntries()
            ->with('project')
            ->latest()
            ->take(10)
            ->get();

        // Wallet transactions
        $walletTransactions = $adiutor->walletTransactions()
            ->latest()
            ->take(15)
            ->get();

        // Client feedback
        $feedback = DB::table('feedbacks')
            ->where('adiutor_id', $id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.adiutors.show', compact(
            'adiutor', 
            'stats', 
            'recentProjects', 
            'recentTimeEntries', 
            'walletTransactions',
            'feedback'
        ));
    }

    /**
     * Show the form for creating a new adiutor.
     */
    public function create(): View
    {
        $skills = Skill::orderBy('name')->get();
        return view('admin.adiutors.create', compact('skills'));
    }

    /**
     * Store a newly created adiutor in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phoneNumber' => ['nullable', 'string', 'max:25', new \App\Rules\PhoneNumber(true)],
            'status' => 'required|in:active,inactive',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'standard_hourly_rate' => 'nullable|numeric|min:0',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ]);

        try {
            // Generate a secure random password
            $temporaryPassword = Str::random(12);

            $adiutor = DB::transaction(function () use ($validated, $temporaryPassword) {
                // Create user
                $user = User::create([
                    'fullName' => $validated['fullName'],
                    'email' => $validated['email'],
                    'phoneNumber' => $validated['phoneNumber'] ?? null,
                    'password' => bcrypt($temporaryPassword),
                    'role' => 'adiutor',
                    'status' => $validated['status'],
                ]);

                // Create adiutor profile
                $profile = AdiutorProfile::create([
                    'user_id' => $user->id,
                    'title' => $validated['title'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'standard_hourly_rate' => $validated['standard_hourly_rate'] ?? 0,
                    'status' => $validated['status'],
                    'is_verified' => false,
                ]);

                // Attach skills if provided
                if (!empty($validated['skills'])) {
                    $profile->skills()->attach($validated['skills']);
                }

                return $user;
            });

            // Send welcome email with credentials
            Mail::to($adiutor->email)->send(new AdiutorAccountCreatedMail($adiutor, $temporaryPassword));

            return redirect()->route('admin.adiutors.index')
                ->with('success', 'Adiutor created successfully. A welcome email with login credentials has been sent to ' . $adiutor->email);
        } catch (\Exception $e) {
            \Log::error('Failed to create adiutor', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to create adiutor. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing an existing adiutor.
     */
    public function edit($id): View
    {
        $adiutor = User::where('role', 'adiutor')
            ->with('adiutorProfile.skills')
            ->findOrFail($id);
        
        $skills = Skill::orderBy('name')->get();
        
        return view('admin.adiutors.edit', compact('adiutor', 'skills'));
    }

    /**
     * Update the specified adiutor in the database.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $adiutor = User::where('role', 'adiutor')->findOrFail($id);

        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phoneNumber' => ['nullable', 'string', 'max:25', new \App\Rules\PhoneNumber(true)],
            'status' => 'required|in:active,inactive',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'standard_hourly_rate' => 'nullable|numeric|min:0',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
            'is_verified' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($adiutor, $validated) {
                // Update user
                $adiutor->update([
                    'fullName' => $validated['fullName'],
                    'email' => $validated['email'],
                    'phoneNumber' => $validated['phoneNumber'] ?? null,
                    'status' => $validated['status'],
                ]);

                // Update or create adiutor profile
                $profile = $adiutor->adiutorProfile ?? new AdiutorProfile(['user_id' => $adiutor->id]);
                $profile->fill([
                    'title' => $validated['title'] ?? null,
                    'bio' => $validated['bio'] ?? null,
                    'standard_hourly_rate' => $validated['standard_hourly_rate'] ?? 0,
                    'status' => $validated['status'],
                    'is_verified' => $validated['is_verified'] ?? false,
                ]);
                $profile->save();

                // Sync skills
                if (isset($validated['skills'])) {
                    $profile->skills()->sync($validated['skills']);
                } else {
                    $profile->skills()->detach();
                }
            });

            return redirect()->route('admin.adiutors.show', $adiutor->id)
                ->with('success', 'Adiutor updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to update adiutor', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to update adiutor. Please try again.')
                ->withInput();
        }
    }

    /**
     * Deactivate the specified adiutor.
     */
    public function destroy($id): RedirectResponse
    {
        $adiutor = User::where('role', 'adiutor')->findOrFail($id);

        // Check if adiutor has active projects
        $activeProjects = $adiutor->projectAssignments()
            ->whereIn('status', ['assigned', 'in_progress'])
            ->count();

        if ($activeProjects > 0) {
            return redirect()->route('admin.adiutors.index')
                ->with('error', "Cannot deactivate adiutor with {$activeProjects} active project(s). Please reassign or complete their projects first.");
        }

        // Check for pending earnings
        if (($adiutor->work_earnings_balance ?? 0) > 0) {
            return redirect()->route('admin.adiutors.index')
                ->with('error', 'Cannot deactivate adiutor with pending earnings. Please process their withdrawal first.');
        }

        // Soft delete by setting status to 'inactive'
        $adiutor->update(['status' => 'inactive']);

        return redirect()->route('admin.adiutors.index')
            ->with('success', 'Adiutor has been deactivated successfully.');
    }

    /**
     * Export adiutors to CSV format.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = User::where('role', 'adiutor')
            ->with(['adiutorProfile'])
            ->withCount([
                'projectAssignments as total_projects',
                'projectAssignments as completed_projects' => fn($q) => $q->where('status', 'completed'),
            ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $adiutors = $query->get();

        $filename = 'adiutors_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($adiutors) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Header
            fputcsv($file, [
                'ID',
                'Full Name',
                'Email',
                'Phone Number',
                'Title',
                'Hourly Rate',
                'Status',
                'Verified',
                'Total Projects',
                'Completed Projects',
                'Current Balance',
                'Joined Date',
            ]);

            // CSV Data
            foreach ($adiutors as $adiutor) {
                fputcsv($file, [
                    $adiutor->id,
                    $adiutor->fullName,
                    $adiutor->email,
                    $adiutor->phoneNumber ?? 'N/A',
                    $adiutor->adiutorProfile?->title ?? 'N/A',
                    $adiutor->adiutorProfile?->standard_hourly_rate ?? 0,
                    ucfirst($adiutor->status),
                    $adiutor->adiutorProfile?->is_verified ? 'Yes' : 'No',
                    $adiutor->total_projects,
                    $adiutor->completed_projects,
                    $adiutor->work_earnings_balance ?? 0,
                    $adiutor->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Resend login credentials to adiutor.
     */
    public function resendCredentials($id): RedirectResponse
    {
        $adiutor = User::where('role', 'adiutor')->findOrFail($id);

        // Generate new password
        $newPassword = Str::random(12);
        $adiutor->update(['password' => bcrypt($newPassword)]);

        // Send email
        Mail::to($adiutor->email)->send(new AdiutorAccountCreatedMail($adiutor, $newPassword));

        return redirect()->route('admin.adiutors.show', $adiutor->id)
            ->with('success', 'New login credentials have been sent to ' . $adiutor->email);
    }

    /**
     * Toggle adiutor verification status.
     */
    public function toggleVerification($id): RedirectResponse
    {
        $adiutor = User::where('role', 'adiutor')->with('adiutorProfile')->findOrFail($id);

        if (!$adiutor->adiutorProfile) {
            AdiutorProfile::create([
                'user_id' => $adiutor->id,
                'is_verified' => true,
            ]);
        } else {
            $adiutor->adiutorProfile->update([
                'is_verified' => !$adiutor->adiutorProfile->is_verified
            ]);
        }

        $status = $adiutor->adiutorProfile?->is_verified ? 'verified' : 'unverified';
        return redirect()->route('admin.adiutors.show', $adiutor->id)
            ->with('success', "Adiutor has been {$status}.");
    }

    /**
     * Display a list of archived adiutors.
     */
    public function archived(): View
    {
        $adiutors = User::where('role', 'adiutor')
            ->where('status', 'archived')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.adiutors.archived', compact('adiutors'));
    }

    /**
     * Restore an archived adiutor.
     */
    public function restore($id): RedirectResponse
    {
        $adiutor = User::where('role', 'adiutor')
            ->where('status', 'archived')
            ->findOrFail($id);

        $adiutor->update(['status' => 'active']);

        return redirect()->route('admin.adiutors.archived')
            ->with('success', "{$adiutor->fullName} has been restored successfully.");
    }

    /**
     * Handle bulk actions on multiple adiutors.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'adiutor_ids' => 'required|array',
            'adiutor_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate,ban,archive',
        ]);

        $statusMap = [
            'activate' => 'active',
            'deactivate' => 'inactive',
            'ban' => 'banned',
            'archive' => 'archived',
        ];

        $count = User::where('role', 'adiutor')
            ->whereIn('id', $request->adiutor_ids)
            ->update(['status' => $statusMap[$request->action]]);

        $actionLabel = match($request->action) {
            'activate' => 'activated',
            'deactivate' => 'deactivated',
            'ban' => 'banned',
            'archive' => 'archived',
        };

        return redirect()->route('admin.adiutors.index')
            ->with('success', "{$count} adiutor(s) have been {$actionLabel}.");
    }
}
