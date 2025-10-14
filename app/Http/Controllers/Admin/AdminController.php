<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Form;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->role === 'admin' && $user->status === 'active') {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            } else {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Unauthorized access or inactive account.',
                ]);
            }
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_adiutors' => User::where('role', 'adiutor')->count(),
            'pending_requests' => Form::where('status', 'pending')->count(),
            'active_tasks' => Task::whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'recent_feedback' => Feedback::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_users' => User::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_requests' => Form::with('user')->orderBy('submissionDate', 'desc')->limit(5)->get(),
        ];

        // Get monthly user registrations for chart
        $monthlyUsers = User::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
            ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get task completion stats for chart
        $taskStats = Task::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact('stats', 'monthlyUsers', 'taskStats'));
    }

    /**
     * Show user management
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Show service requests management
     */
    public function requests()
    {
        $requests = Form::with('user')->orderBy('submissionDate', 'desc')->paginate(15);
        
        return view('admin.requests', compact('requests'));
    }

    /**
     * Show task assignment
     */
    public function tasks()
    {
        $tasks = Task::with(['form', 'assignedUser'])->orderBy('dateAssigned', 'desc')->paginate(15);
        
        return view('admin.tasks', compact('tasks'));
    }

    /**
     * Show reports and analytics
     */
    public function reports()
    {
        // Generate comprehensive analytics data
        $analytics = [
            'user_growth' => User::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
                ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'task_completion' => Task::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'user_roles' => User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->get(),
            'monthly_requests' => Form::selectRaw('strftime("%m", submissionDate) as month, COUNT(*) as count')
                ->whereRaw('strftime("%Y", submissionDate) = ?', [date('Y')])
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
            'feedback_ratings' => Feedback::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating')
                ->get(),
        ];
        
        return view('admin.reports', compact('analytics'));
    }

    /**
     * Show admin profile.
     */
    public function profile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phoneNumber' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Check current password if new password is provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Current password is incorrect.',
                ]);
            }
        }

        // Update user data
        $userData = $request->only(['fullName', 'email', 'phoneNumber']);
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        User::where('id', $user->id)->update($userData);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }
}