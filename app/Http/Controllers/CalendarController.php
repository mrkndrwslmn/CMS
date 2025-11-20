<?php

namespace App\Http\Controllers;

use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    protected GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->middleware('auth');
        $this->calendarService = $calendarService;
    }

    /**
     * Show calendar schedule page with task scheduling timeline
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isAdiutor()) {
            return redirect()->route('adiutor.dashboard');
        }

        // Get active projects where this adiutor is assigned (from project_assignments)
        $assignedProjects = \App\Models\ProjectAssignment::where('adiutor_id', $user->id)
            ->whereIn('status', ['active', 'in_progress'])
            ->with('project.client')
            ->get()
            ->pluck('project')
            ->filter();

        // Also get projects where this adiutor has tasks assigned (from tasks table)
        $projectsWithTasks = \App\Models\Task::where('assignedTo', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('project')
            ->get()
            ->pluck('project')
            ->filter()
            ->filter(function($project) {
                return in_array($project->status, ['active', 'in_progress']);
            });

        // Merge and deduplicate projects
        $activeProjects = $assignedProjects->concat($projectsWithTasks)
            ->unique('id')
            ->values();

        // Get all unscheduled tasks from these projects
        $projectIds = $activeProjects->pluck('id')->toArray();
        
        $allTasks = \App\Models\Task::whereIn('project_id', $projectIds)
            ->where('assignedTo', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->get();

        // Get scheduled task IDs from task_schedules table
        $scheduledTaskIds = \App\Models\TaskSchedule::whereIn('task_id', $allTasks->pluck('taskID'))
            ->pluck('task_id')
            ->toArray();

        // Filter to get unscheduled tasks
        $unscheduledTasks = $allTasks->filter(function($task) use ($scheduledTaskIds) {
            return !in_array($task->taskID, $scheduledTaskIds);
        })->map(function($task) {
            return [
                'id' => $task->taskID,
                'title' => $task->taskTitle,
                'description' => $task->taskDescription,
                'priority' => $task->priority ?? 'medium',
                'project_id' => $task->project_id,
                'project_name' => $task->project->title ?? 'N/A',
                'estimated_hours' => $task->total_hours_tracked ?? 0,
                'deadline' => $task->deadline,
            ];
        });

        $integration = $user->calendarIntegration;

        return view('adiutor.calendar.schedule', compact('activeProjects', 'unscheduledTasks', 'user', 'integration'));
    }

    /**
     * Show calendar connection settings page
     */
    public function connection()
    {
        $user = Auth::user();
        $integration = $user->calendarIntegration;

        return view('adiutor.calendar.index', compact('user', 'integration'));
    }

    /**
     * Redirect to Google OAuth consent screen
     */
    public function connect()
    {
        if (!Auth::user()->isAdiutor()) {
            return redirect()->back()->with('error', 'Only adiutors can connect calendars.');
        }

        $authUrl = $this->calendarService->getAuthUrl();
        return redirect($authUrl);
    }

    /**
     * Handle OAuth callback from Google
     */
    public function callback(Request $request)
    {
        if (!Auth::user()->isAdiutor()) {
            return redirect()->route('adiutor.dashboard')->with('error', 'Only adiutors can connect calendars.');
        }

        if ($request->has('error')) {
            return redirect()->route('calendar.index')
                ->with('error', 'Authorization failed: ' . $request->get('error'));
        }

        if (!$request->has('code')) {
            return redirect()->route('calendar.index')
                ->with('error', 'Authorization code not received.');
        }

        try {
            $this->calendarService->handleCallback(
                $request->get('code'),
                Auth::user()
            );

            return redirect()->route('calendar.index')
                ->with('success', 'Google Calendar connected successfully! Your schedule is now synced.')
                ->with('showConnectedModal', true);
        } catch (\Exception $e) {
            return redirect()->route('calendar.index')
                ->with('error', 'Failed to connect calendar: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect Google Calendar integration
     */
    public function disconnect()
    {
        if (!Auth::user()->isAdiutor()) {
            return redirect()->back()->with('error', 'Only adiutors can disconnect calendars.');
        }

        try {
            $this->calendarService->disconnect(Auth::user());

            return redirect()->route('calendar.index')
                ->with('success', 'Google Calendar disconnected successfully.');
        } catch (\Exception $e) {
            return redirect()->route('calendar.index')
                ->with('error', 'Failed to disconnect calendar: ' . $e->getMessage());
        }
    }

    /**
     * Test calendar connection by fetching events
     */
    public function testConnection()
    {
        if (!Auth::user()->isAdiutor()) {
            return response()->json(['error' => 'Only adiutors can test calendar connection.'], 403);
        }

        try {
            $events = $this->calendarService->getEvents(
                Auth::user(),
                now()->startOfWeek(),
                now()->endOfWeek()
            );

            return response()->json([
                'success' => true,
                'message' => 'Calendar connection is working!',
                'events_count' => count($events),
                'sample_events' => array_slice($events, 0, 3),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Preview the connected modal UI (for testing without actual connection)
     */
    public function previewConnected()
    {
        $user = Auth::user();
        
        if (!$user->isAdiutor()) {
            return redirect()->route('adiutor.dashboard');
        }

        // Create fake integration data for preview
        $fakeIntegration = (object) [
            'is_connected' => true,
            'calendar_id' => 'preview@gmail.com',
            'last_synced_at' => now()->subMinutes(5),
        ];

        return view('adiutor.calendar.schedule', [
            'user' => $user,
            'activeProjects' => collect([]),
            'unscheduledTasks' => collect([]),
            'integration' => $fakeIntegration,
            'showConnectedModal' => true, // Flag to auto-open the modal
        ]);
    }
}
