<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TimeTrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'adiutor') {
                abort(403, 'Unauthorized access. Adiutors only.');
            }
            return $next($request);
        });
    }

    /**
     * Display time tracking dashboard
     */
    public function index(Request $request)
    {
        $adiutorId = Auth::id();
        
        // Get current week's time entries with optimized query and correct column names
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $currentWeekEntries = TimeEntry::select(['id', 'adiutor_id', 'task_id', 'start_time', 'end_time', 'duration_minutes', 'description', 'is_approved'])
            ->where('adiutor_id', $adiutorId)
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->with(['task:taskID,taskTitle,project_id', 'task.project:id,title'])
            ->orderBy('start_time', 'desc')
            ->get();

        // Get active timer if any with optimized query and correct column names
        $activeTimer = TimeEntry::select(['id', 'adiutor_id', 'task_id', 'start_time', 'description'])
            ->where('adiutor_id', $adiutorId)
            ->whereNull('end_time')
            ->with(['task:taskID,taskTitle,project_id', 'task.project:id,title'])
            ->first();

        // Get available tasks for this adiutor with correct column names
        $availableTasks = Task::select(['taskID', 'taskTitle', 'project_id', 'status', 'priority', 'created_at'])
            ->whereHas('project', function($query) use ($adiutorId) {
                $query->whereHas('adiutors', function($subQuery) use ($adiutorId) {
                    $subQuery->where('adiutor_id', $adiutorId);
                });
            })
            ->where('status', '!=', 'completed')
            ->with(['project:id,title'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit to prevent large result sets
            ->get();

        // Optimized statistics with single queries and caching - using correct column names
        $todayHours = Cache::remember("today_hours_{$adiutorId}", 300, function() use ($adiutorId) {
            return TimeEntry::where('adiutor_id', $adiutorId)
                ->whereDate('start_time', Carbon::today())
                ->whereNotNull('end_time')
                ->sum('duration_minutes') / 60;
        });

        $weekHours = $currentWeekEntries->where('end_time', '!=', null)->sum('duration_minutes') / 60;

        $monthHours = Cache::remember("month_hours_{$adiutorId}_" . Carbon::now()->format('Y_m'), 600, function() use ($adiutorId) {
            return TimeEntry::where('adiutor_id', $adiutorId)
                ->whereMonth('start_time', Carbon::now()->month)
                ->whereYear('start_time', Carbon::now()->year)
                ->whereNotNull('end_time')
                ->sum('duration_minutes') / 60;
        });

        // Debug: Log available tasks count
        \Log::info('Time tracking - Available tasks count: ' . $availableTasks->count());
        \Log::info('Time tracking - Adiutor ID: ' . $adiutorId);
        
        return view('adiutor.time-tracking.index', compact(
            'currentWeekEntries', 
            'activeTimer', 
            'availableTasks',
            'todayHours',
            'weekHours',
            'monthHours'
        ));
    }

    /**
     * Start a new time entry
     */
    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,taskID',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $adiutorId = Auth::id();

        // Check if there's already an active timer
        $activeTimer = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNull('end_time')
            ->first();

        if ($activeTimer) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active timer running. Please stop it first.'
            ], 400);
        }

        // Verify the task belongs to adiutor's projects
        $task = Task::whereHas('project.adiutors', function($query) use ($adiutorId) {
                $query->where('adiutor_id', $adiutorId);
            })
            ->where('taskID', $request->task_id)
            ->first();

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or you do not have access to this task.'
            ], 404);
        }

        $timeEntry = TimeEntry::create([
            'adiutor_id' => $adiutorId,
            'task_id' => $request->task_id,
            'project_id' => $task->project_id,
            'start_time' => Carbon::now(),
            'description' => $request->description,
            'is_approved' => false
        ]);

        $timeEntry->load(['task.project']);

        return response()->json([
            'success' => true,
            'message' => 'Timer started successfully',
            'timer' => [
                'id' => $timeEntry->id,
                'task_name' => $timeEntry->task->taskTitle,
                'project_name' => $timeEntry->task->project->title,
                'started_at' => $timeEntry->start_time->toISOString(),
                'description' => $timeEntry->description
            ]
        ]);
    }

    /**
     * Stop the active time entry
     */
    public function stop(Request $request)
    {
        $adiutorId = Auth::id();
        
        $activeTimer = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNull('ended_at')
            ->first();

        if (!$activeTimer) {
            return response()->json([
                'success' => false,
                'message' => 'No active timer found.'
            ], 404);
        }

        $now = Carbon::now();
        $durationMinutes = $activeTimer->start_time->diffInMinutes($now);

        $activeTimer->update([
            'end_time' => $now,
            'duration_minutes' => $durationMinutes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Timer stopped successfully',
            'duration' => [
                'minutes' => $durationMinutes,
                'formatted' => $this->formatDuration($durationMinutes)
            ]
        ]);
    }

    /**
     * Get current timer status
     */
    public function status()
    {
        $adiutorId = Auth::id();
        
        $activeTimer = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNull('end_time')
            ->with(['task.project'])
            ->first();

        if (!$activeTimer) {
            return response()->json([
                'success' => true,
                'active' => false,
                'timer' => null
            ]);
        }

        $elapsed = $activeTimer->start_time->diffInMinutes(Carbon::now());

        return response()->json([
            'success' => true,
            'active' => true,
            'timer' => [
                'id' => $activeTimer->id,
                'task_name' => $activeTimer->task->taskTitle,
                'project_name' => $activeTimer->task->project->title,
                'started_at' => $activeTimer->start_time->toISOString(),
                'elapsed_minutes' => $elapsed,
                'elapsed_formatted' => $this->formatDuration($elapsed),
                'description' => $activeTimer->description
            ]
        ]);
    }

    /**
     * Get time entries with filtering - optimized for performance
     */
    public function entries(Request $request)
    {
        $adiutorId = Auth::id();
        
        // Use select to only get necessary columns with correct column names
        $query = TimeEntry::select(['id', 'adiutor_id', 'task_id', 'start_time', 'end_time', 'duration_minutes', 'description', 'is_approved'])
            ->where('adiutor_id', $adiutorId)
            ->with(['task:taskID,taskTitle,project_id', 'task.project:id,title']);

        // Date filtering with optimized between using correct column name
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('start_time', [$request->date_from, $request->date_to]);
        } elseif ($request->filled('date_from')) {
            $query->where('start_time', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('start_time', '<=', $request->date_to);
        }

        // Project filtering with join instead of whereHas for better performance
        if ($request->filled('project_id')) {
            $query->whereHas('task', function($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }

        // Status filtering with direct column check
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Cache key based on filters
        $cacheKey = "time_entries_{$adiutorId}_" . md5(serialize($request->only(['date_from', 'date_to', 'project_id', 'status', 'page'])));
        
        $entries = Cache::remember($cacheKey, 180, function() use ($query) {
            return $query->orderBy('start_time', 'desc')->paginate(20);
        });

        return response()->json([
            'success' => true,
            'entries' => $entries->items(),
            'pagination' => [
                'current_page' => $entries->currentPage(),
                'last_page' => $entries->lastPage(),
                'per_page' => $entries->perPage(),
                'total' => $entries->total()
            ]
        ]);
    }

    /**
     * Update a time entry
     */
    public function update(Request $request, TimeEntry $timeEntry)
    {
        // Verify ownership
        if ($timeEntry->adiutor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Can't edit approved entries
        if ($timeEntry->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit approved time entries.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string|max:500',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time',
            'duration_minutes' => 'sometimes|integer|min:1|max:1440' // Max 24 hours
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['description', 'start_time', 'end_time', 'duration_minutes']);

        // If both start_time and end_time are provided, calculate duration
        if (isset($data['start_time']) && isset($data['end_time'])) {
            $start = Carbon::parse($data['start_time']);
            $end = Carbon::parse($data['end_time']);
            $data['duration_minutes'] = $start->diffInMinutes($end);
        }

        $timeEntry->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Time entry updated successfully',
            'time_entry' => $timeEntry->fresh(['task.project'])
        ]);
    }

    /**
     * Delete a time entry
     */
    public function delete(TimeEntry $timeEntry)
    {
        // Verify ownership
        if ($timeEntry->adiutor_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Can't delete approved entries
        if ($timeEntry->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved time entries.'
            ], 400);
        }

        $timeEntry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Time entry deleted successfully'
        ]);
    }

    /**
     * Format duration in minutes to readable format
     */
    private function formatDuration($minutes)
    {
        $hours = intval($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $mins);
        }
        
        return sprintf('%dm', $mins);
    }
}
