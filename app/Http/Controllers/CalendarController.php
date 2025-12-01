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

        // Get scheduled task IDs from task_schedules table (for this adiutor)
        $scheduledTaskIds = \App\Models\TaskSchedule::whereIn('task_id', $allTasks->pluck('taskID'))
            ->where('adiutor_id', $user->id)
            ->pluck('task_id')
            ->toArray();

        // Detect deadline conflicts: tasks with same deadline for this adiutor
        $deadlineConflicts = [];
        $conflictDetails = []; // Track which task conflicts with which
        
        $tasksByDeadline = $allTasks
            ->filter(fn($t) => $t->deadline)
            ->groupBy(function($task) {
                return \Carbon\Carbon::parse($task->deadline)->format('Y-m-d');
            });
        
        foreach ($tasksByDeadline as $group) {
            if ($group->count() > 1) {
                // Multiple tasks with same deadline = conflict
                // Sort by creation date (taskID as proxy) - oldest task gets priority
                $sortedGroup = $group->sortByDesc('taskID'); // Newest first
                $oldestTask = $sortedGroup->pop(); // Remove and get oldest task
                
                // All newer tasks conflict with the oldest task
                foreach ($sortedGroup as $conflictingTask) {
                    $deadlineConflicts[] = $conflictingTask->taskID;
                    $conflictDetails[$conflictingTask->taskID] = [
                        'conflicting_task_id' => $oldestTask->taskID,
                        'conflicting_task_title' => $oldestTask->taskTitle,
                    ];
                }
            }
        }

        // Filter to get unscheduled tasks (exclude scheduled tasks, but include deadline conflicts)
        $unscheduledTasks = $allTasks->filter(function($task) use ($scheduledTaskIds, $deadlineConflicts) {
            // Exclude if manually scheduled
            if (in_array($task->taskID, $scheduledTaskIds)) {
                return false;
            }
            
            // Include if no deadline
            if (!$task->deadline) {
                return true;
            }
            
            // Include if has deadline conflict
            if (in_array($task->taskID, $deadlineConflicts)) {
                return true;
            }
            
            return false;
        })->map(function($task) use ($deadlineConflicts, $conflictDetails) {
            $hasConflict = in_array($task->taskID, $deadlineConflicts);
            
            return [
                'id' => $task->taskID,
                'title' => $task->taskTitle,
                'description' => $task->taskDescription,
                'priority' => $task->priority ?? 'medium',
                'project_id' => $task->project_id,
                'project_name' => $task->project->title ?? 'N/A',
                'estimated_hours' => $task->total_hours_tracked ?? 0,
                'deadline' => $task->deadline,
                'has_conflict' => $hasConflict,
                'conflicting_with' => $hasConflict && isset($conflictDetails[$task->taskID]) 
                    ? $conflictDetails[$task->taskID]['conflicting_task_title'] 
                    : null,
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
        file_put_contents(storage_path('logs/connect-debug.txt'), date('Y-m-d H:i:s') . ' - Connect method called by user: ' . Auth::id() . "\n", FILE_APPEND);
        
        \Log::info('CalendarController@connect called', [
            'user_id' => Auth::id(),
            'is_adiutor' => Auth::user()->isAdiutor(),
        ]);
        
        if (!Auth::user()->isAdiutor()) {
            \Log::warning('Non-adiutor tried to connect calendar');
            return redirect()->back()->with('error', 'Only adiutors can connect calendars.');
        }

        try {
            $authUrl = $this->calendarService->getAuthUrl();
            \Log::info('Generated auth URL', ['url' => $authUrl]);
            return redirect($authUrl);
        } catch (\Exception $e) {
            \Log::error('Error generating auth URL: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to connect: ' . $e->getMessage());
        }
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
            $user = Auth::user();
            
            // Handle the OAuth callback and save tokens
            $this->calendarService->handleCallback(
                $request->get('code'),
                $user
            );

            // Automatically sync all existing tasks after connection
            $this->syncExistingTasks($user);

            return redirect()->route('calendar.index')
                ->with('success', 'Google Calendar connected successfully! Your schedule is now synced.')
                ->with('showConnectedModal', true);
        } catch (\Exception $e) {
            return redirect()->route('calendar.index')
                ->with('error', 'Failed to connect calendar: ' . $e->getMessage());
        }
    }

    /**
     * Sync all existing tasks to Google Calendar (called after first connection)
     */
    protected function syncExistingTasks($user)
    {
        try {
            // Only sync tasks that are already scheduled on the timeline
            // Do NOT sync unscheduled tasks
            $scheduledTasks = \App\Models\TaskSchedule::where('adiutor_id', $user->id)
                ->whereNull('google_calendar_event_id')
                ->with('task')
                ->get();

            $syncedCount = 0;

            foreach ($scheduledTasks as $schedule) {
                if (!$schedule->task) continue;

                try {
                    $eventId = $this->calendarService->createTaskEvent($user, [
                        'title' => $schedule->task->taskTitle,
                        'description' => $schedule->task->taskDescription,
                        'project' => $schedule->task->project->title ?? 'N/A',
                        'priority' => $schedule->task->priority ?? 'medium',
                        'start' => $schedule->scheduled_start,
                        'end' => $schedule->scheduled_end,
                        'url' => route('adiutor.tasks.show', $schedule->task->taskID)
                    ]);

                    $schedule->update([
                        'google_calendar_event_id' => $eventId,
                        'calendar_synced_at' => now(),
                    ]);

                    $syncedCount++;
                    
                    \Log::info('Synced existing scheduled task', [
                        'task_id' => $schedule->task_id,
                        'event_id' => $eventId,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to sync existing scheduled task', [
                        'schedule_id' => $schedule->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            \Log::info('Finished syncing existing tasks after calendar connection', [
                'adiutor_id' => $user->id,
                'synced_count' => $syncedCount,
                'total_scheduled' => $scheduledTasks->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to sync existing tasks', [
                'adiutor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            // Don't throw - connection already succeeded
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

    /**
     * Sync all tasks with deadlines to Google Calendar
     */
    public function syncDeadlineTasks()
    {
        $user = Auth::user();
        
        if (!$user->isAdiutor()) {
            return response()->json(['error' => 'Only adiutors can sync calendar.'], 403);
        }

        $integration = $user->calendarIntegration;
        
        if (!$integration || !$integration->is_connected) {
            return response()->json(['error' => 'Google Calendar not connected.'], 400);
        }

        try {
            // Get all tasks assigned to this adiutor with deadlines
            $tasksWithDeadlines = \App\Models\Task::where('assignedTo', $user->id)
                ->whereNotNull('deadline')
                ->whereIn('status', ['pending', 'in_progress'])
                ->get();

            // Get already scheduled task IDs (manually scheduled)
            $scheduledTaskIds = \App\Models\TaskSchedule::whereIn('task_id', $tasksWithDeadlines->pluck('taskID'))
                ->where('adiutor_id', $user->id)
                ->whereNotNull('google_calendar_event_id')
                ->pluck('task_id')
                ->toArray();

            $syncedCount = 0;
            $errors = [];

            foreach ($tasksWithDeadlines as $task) {
                // Skip if already manually scheduled and synced
                if (in_array($task->taskID, $scheduledTaskIds)) {
                    continue;
                }

                try {
                    $deadline = \Carbon\Carbon::parse($task->deadline);
                    
                    // Create event from 9 AM to 5 PM on deadline day (or adjust based on estimated hours)
                    $startTime = $deadline->copy()->setTime(9, 0);
                    $endTime = $deadline->copy()->setTime(17, 0);

                    $eventId = $this->calendarService->createTaskEvent($user, [
                        'title' => $task->taskTitle,
                        'description' => $task->taskDescription,
                        'project' => $task->project->title ?? 'N/A',
                        'priority' => $task->priority ?? 'medium',
                        'start' => $startTime,
                        'end' => $endTime,
                        'url' => route('adiutor.tasks.show', $task->taskID)
                    ]);

                    // Create a task schedule record to track this sync
                    \App\Models\TaskSchedule::create([
                        'task_id' => $task->taskID,
                        'adiutor_id' => $user->id,
                        'scheduled_start' => $startTime,
                        'scheduled_end' => $endTime,
                        'estimated_duration_minutes' => 480, // 8 hours
                        'schedule_type' => 'auto_deadline',
                        'google_calendar_event_id' => $eventId,
                        'calendar_synced_at' => now(),
                    ]);

                    $syncedCount++;
                } catch (\Exception $e) {
                    $errors[] = [
                        'task_id' => $task->taskID,
                        'task_title' => $task->taskTitle,
                        'error' => $e->getMessage(),
                    ];
                    \Log::error('Failed to sync deadline task', [
                        'task_id' => $task->taskID,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Update last synced timestamp
            $integration->update(['last_synced_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => "Synced {$syncedCount} tasks to Google Calendar",
                'synced_count' => $syncedCount,
                'total_tasks' => $tasksWithDeadlines->count(),
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to sync deadline tasks', [
                'adiutor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to sync tasks: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync ALL tasks to Google Calendar (both manual schedules and deadlines)
     */
    public function syncAllTasks()
    {
        $user = Auth::user();
        
        if (!$user->isAdiutor()) {
            return response()->json(['error' => 'Only adiutors can sync calendar.'], 403);
        }

        $integration = $user->calendarIntegration;
        
        if (!$integration || !$integration->is_connected) {
            return response()->json(['error' => 'Google Calendar not connected.'], 400);
        }

        try {
            $syncedCount = 0;
            $errors = [];

            // Only sync tasks that are SCHEDULED on the timeline (in task_schedules table)
            // Do NOT sync unscheduled tasks
            $scheduledTasks = \App\Models\TaskSchedule::where('adiutor_id', $user->id)
                ->whereNull('google_calendar_event_id')
                ->with('task')
                ->get();

            foreach ($scheduledTasks as $schedule) {
                if (!$schedule->task) continue;

                try {
                    $eventId = $this->calendarService->createTaskEvent($user, [
                        'title' => $schedule->task->taskTitle,
                        'description' => $schedule->task->taskDescription,
                        'project' => $schedule->task->project->title ?? 'N/A',
                        'priority' => $schedule->task->priority ?? 'medium',
                        'start' => $schedule->scheduled_start,
                        'end' => $schedule->scheduled_end,
                        'url' => route('adiutor.tasks.show', $schedule->task->taskID)
                    ]);

                    $schedule->update([
                        'google_calendar_event_id' => $eventId,
                        'calendar_synced_at' => now(),
                    ]);

                    $syncedCount++;
                    
                    \Log::info('Synced scheduled task to Google Calendar', [
                        'task_id' => $schedule->task_id,
                        'event_id' => $eventId,
                    ]);
                } catch (\Exception $e) {
                    $errors[] = [
                        'task_id' => $schedule->task_id,
                        'task_title' => $schedule->task->taskTitle ?? 'Unknown',
                        'error' => $e->getMessage(),
                    ];
                    \Log::error('Failed to sync scheduled task', [
                        'schedule_id' => $schedule->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Update last synced timestamp
            $integration->update(['last_synced_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => "Synced {$syncedCount} timeline tasks to Google Calendar",
                'synced_count' => $syncedCount,
                'total_scheduled' => $scheduledTasks->count(),
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to sync all tasks', [
                'adiutor_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to sync tasks: ' . $e->getMessage(),
            ], 500);
        }
    }
}
