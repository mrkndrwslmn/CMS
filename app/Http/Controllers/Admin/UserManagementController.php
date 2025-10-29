<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserCreatedNotification;
use App\Notifications\UserStatusChangedNotification;
use App\Notifications\UserDeletedNotification;
use App\Mail\AccountDeactivatedMail;
use App\Mail\AccountReactivatedMail;
use App\Mail\WelcomeNewUserMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullName', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phoneNumber', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'clients' => User::where('role', 'client')->count(),
            'adiutors' => User::where('role', 'adiutor')->count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phoneNumber' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,client,adiutor',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        // Notify all admins about new user creation
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserCreatedNotification($user, Auth::user()->fullName, true));
        }

        // 📧 Send welcome email to new user
        try {
            Mail::to($user->email)->send(new WelcomeNewUserMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email to new user', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Load existing relationships safely
        $user->load(['tasks', 'forms', 'feedbacks']);
        
        // Get user statistics
        $stats = [
            'total_tasks' => $user->tasks ? $user->tasks->count() : 0,
            'active_tasks' => $user->tasks ? $user->tasks->where('status', 'in_progress')->count() : 0,
            'completed_tasks' => $user->tasks ? $user->tasks->where('status', 'completed')->count() : 0,
            'total_forms' => $user->forms ? $user->forms->count() : 0,
            'total_feedback' => $user->feedbacks ? $user->feedbacks->count() : 0,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phoneNumber' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:admin,client,adiutor',
            'status' => 'required|in:active,inactive',
        ]);

        // Track changes for notifications
        $changes = [];
        $oldStatus = $user->status;
        
        if ($user->fullName !== $request->fullName) {
            $changes['fullName'] = ['old' => $user->fullName, 'new' => $request->fullName];
        }
        if ($user->email !== $request->email) {
            $changes['email'] = ['old' => $user->email, 'new' => $request->email];
        }
        if ($user->role !== $request->role) {
            $changes['role'] = ['old' => $user->role, 'new' => $request->role];
        }
        if ($user->status !== $request->status) {
            $changes['status'] = ['old' => $user->status, 'new' => $request->status];
        }

        $userData = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            $changes['password'] = 'Password updated';
        }

        $user->update($userData);

        // 🔔 Notify admins about user updates if there were changes
        if (!empty($changes)) {
            $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
            foreach ($admins as $admin) {
                // Create a custom notification for user updates
                $admin->notify(new \App\Notifications\UserUpdatedNotification($user, $changes, Auth::user()->fullName));
            }
        }

        // 📧 Send email for status changes
        if (isset($changes['status'])) {
            try {
                if ($request->status === 'inactive' && $oldStatus === 'active') {
                    Mail::to($user->email)->send(new AccountDeactivatedMail($user));
                } elseif ($request->status === 'active' && $oldStatus === 'inactive') {
                    Mail::to($user->email)->send(new AccountReactivatedMail($user));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send status change email', [
                    'user_id' => $user->id,
                    'status_change' => $changes['status'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of current admin user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Check if user has active tasks or forms
        if ($user->tasks()->where('status', 'in_progress')->exists() || 
            $user->assignedTasks()->where('status', 'in_progress')->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete user with active tasks or assignments.');
        }

        // 🔔 Notify admins before deletion
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserDeletedNotification($user, Auth::user()->fullName));
        }

        // Store user data for potential email notification
        $userEmail = $user->email;
        $userName = $user->fullName;

        $user->delete();

        // 📧 Optional: Send deletion notification email to user (if they had important data)
        // This is typically not recommended for security reasons, but can be enabled if needed
        // try {
        //     Mail::to($userEmail)->send(new AccountDeletedMail($userName));
        // } catch (\Exception $e) {
        //     \Log::error('Failed to send account deletion email', [
        //         'user_email' => $userEmail,
        //         'error' => $e->getMessage()
        //     ]);
        // }

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status (active/inactive).
     */
    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        
        // Prevent deactivating current admin user
        if ($user->id === Auth::id() && $newStatus === 'inactive') {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot deactivate your own account.');
        }

        $oldStatus = $user->status;
        $user->update(['status' => $newStatus]);

        // 🔔 Notify admins about status change
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            $admin->notify(new UserStatusChangedNotification($user, $newStatus, Auth::user()->fullName));
        }

        // 📧 Send email notification to user about status change
        try {
            if ($newStatus === 'inactive') {
                Mail::to($user->email)->send(new AccountDeactivatedMail($user));
            } elseif ($newStatus === 'active') {
                Mail::to($user->email)->send(new AccountReactivatedMail($user));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send status change email', [
                'user_id' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "User {$newStatus} successfully.");
    }

    /**
     * Bulk action on selected users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $userIds = $request->user_ids;
        $currentUserId = Auth::id();

        // Remove current user from bulk actions
        $userIds = array_filter($userIds, function($id) use ($currentUserId) {
            return $id != $currentUserId;
        });

        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'No valid users selected for bulk action.');
        }

        $affectedCount = 0;

        switch ($request->action) {
            case 'activate':
                $affectedCount = User::whereIn('id', $userIds)->update(['status' => 'active']);
                $message = "{$affectedCount} users activated successfully.";
                break;

            case 'deactivate':
                $affectedCount = User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                $message = "{$affectedCount} users deactivated successfully.";
                break;

            case 'delete':
                // Check for users with active tasks
                $usersWithActiveTasks = User::whereIn('id', $userIds)
                    ->whereHas('tasks', function($query) {
                        $query->where('status', 'in_progress');
                    })
                    ->orWhereHas('assignedTasks', function($query) {
                        $query->where('status', 'in_progress');
                    })
                    ->count();

                if ($usersWithActiveTasks > 0) {
                    return redirect()->route('admin.users.index')
                        ->with('error', "Cannot delete {$usersWithActiveTasks} users with active tasks.");
                }

                $affectedCount = User::whereIn('id', $userIds)->delete();
                $message = "{$affectedCount} users deleted successfully.";
                break;
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}