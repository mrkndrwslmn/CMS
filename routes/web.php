<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminTemplateController;
use App\Http\Controllers\Admin\AdminAuditController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Adiutor\AdiutorController;
use App\Http\Controllers\Adiutor\TimeTrackingController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\AiSearchController;
use App\Http\Controllers\PublicServiceRequestController;

// Email Template Preview Routes (for development)
if (app()->environment(['local', 'development', 'staging'])) {
    Route::get('/preview-email/{template}', function ($template) {
        // Mock data for email templates
        $mockUser = (object) [
            'fullName' => 'John Doe',
            'firstName' => 'John',
            'email' => 'john.doe@example.com'
        ];
        
        $mockServiceRequest = (object) [
            'project_name' => 'Modern Website Redesign',
            'service_type' => 'Web Development',
            'approved_budget' => 5000,
            'payment_due_date' => now()->addDays(14),
            'payment_reference' => 'REF-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'payment_confirmed_at' => now(),
            'payment_instructions' => 'Please pay via bank transfer to the account details provided.',
            'client' => $mockUser
        ];
        
        $mockProject = (object) [
            'project_name' => 'E-commerce Platform Development',
            'service_type' => 'Full Stack Development',
            'completed_at' => now(),
            'created_at' => now()->subDays(30),
            'client' => $mockUser
        ];
        
        $mockRevision = (object) [
            'revision_type' => 'Design Update',
            'approved_at' => now(),
            'rejected_at' => now(),
            'rejection_reason' => 'The color scheme needs to be adjusted to better match the brand guidelines. Please use more blue tones and reduce the intensity of the orange accents.',
            'description' => 'Update the header design with new branding elements and improve mobile responsiveness.',
            'estimated_completion' => now()->addDays(7),
            'project' => $mockProject
        ];
        
        switch ($template) {
            case 'new-user-credentials':
                return view('emails.new-user-credentials', [
                    'fullName' => $mockUser->fullName,
                    'email' => $mockUser->email,
                    'password' => 'TempPass123!'
                ]);
                
            case 'payment-confirmed':
                return view('emails.payment-confirmed', [
                    'serviceRequest' => $mockServiceRequest
                ]);
                
            case 'request-approved':
                return view('emails.request-approved', [
                    'serviceRequest' => $mockServiceRequest
                ]);
                
            case 'project-completed':
                return view('emails.project-completed', [
                    'project' => $mockProject
                ]);
                
            case 'revision-approved':
                return view('emails.revision-approved', [
                    'revision' => $mockRevision
                ]);
                
            case 'revision-rejected':
                return view('emails.revision-rejected', [
                    'revision' => $mockRevision
                ]);
                
            default:
                abort(404, 'Email template not found');
        }
    })->name('email.preview');
}

// API routes
Route::prefix('api')->group(function () {
    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/aiSearch', [AiSearchController::class, 'search']);
    
    // Showcase routes
    Route::get('/showcases', [\App\Http\Controllers\Api\ShowcaseController::class, 'index']);
    Route::get('/showcases/{slug}', [\App\Http\Controllers\Api\ShowcaseController::class, 'show']);
    Route::get('/tech_stack', [\App\Http\Controllers\Api\ShowcaseController::class, 'techStack']);
    
    // Chatbot routes
    Route::post('/chatbot/chat', [\App\Http\Controllers\ChatbotController::class, 'chat']);
    Route::get('/chatbot/greeting', [\App\Http\Controllers\ChatbotController::class, 'greeting']);
    
    // Messaging API routes (requires authentication)
    Route::middleware('auth')->group(function () {
        // Adiutor rate endpoint for auto-fill
        Route::get('/adiutor/{id}/rate', function($id) {
            $user = \App\Models\User::with('adiutorProfile')->findOrFail($id);
            return response()->json([
                'rate' => $user->adiutorProfile->standard_hourly_rate ?? 0
            ]);
        })->name('api.adiutor.rate');
        
        // Adiutor schedule timeline endpoint
        Route::get('/schedule/adiutor/{id}/timeline', function($id, \Illuminate\Http\Request $request) {
            try {
                $user = \App\Models\User::findOrFail($id);
                $startDate = $request->query('start_date', now()->startOfWeek()->format('Y-m-d'));
                $projectId = $request->query('project_id'); // Get project filter
                
                // Parse start date
                $weekStart = \Carbon\Carbon::parse($startDate);
                $weekEnd = $weekStart->copy()->addDays(6)->endOfDay(); // Monday to Sunday
                
                // Build slots array
                $slots = [];
                
                // 1. Get scheduled tasks from task_schedules table (manually scheduled by admin)
                // Check if scheduled_end (deadline) falls within the week
                $scheduledTasksQuery = \App\Models\TaskSchedule::where('adiutor_id', $id)
                    ->whereBetween('scheduled_end', [$weekStart, $weekEnd])
                    ->with('task');
                
                // Apply project filter if provided
                if ($projectId) {
                    $scheduledTasksQuery->whereHas('task', function($query) use ($projectId) {
                        $query->where('project_id', $projectId);
                    });
                }
                
                $scheduledTasks = $scheduledTasksQuery->get();
                
                foreach ($scheduledTasks as $schedule) {
                    // Use scheduled_end (deadline) for display
                    $deadline = \Carbon\Carbon::parse($schedule->scheduled_end);
                    
                    // Calculate days until deadline (from today)
                    $now = \Carbon\Carbon::now();
                    $daysUntil = (int) $now->diffInDays($deadline, false);
                    
                    $slots[] = [
                        'date' => $deadline->format('Y-m-d'), // Show on deadline date
                        'hour' => $deadline->hour,
                        'type' => 'task',
                        'title' => $schedule->task->taskTitle ?? 'Task',
                        'description' => $schedule->task->taskDescription ?? '',
                        'duration' => $daysUntil,
                        'is_synced' => $schedule->isSynced()
                    ];
                }
                
                // 2. Get assigned tasks with deadlines (show on calendar even if not manually scheduled)
                $assignedTasksQuery = \App\Models\Task::where('assignedTo', $id)
                    ->whereNotNull('deadline')
                    ->whereBetween('deadline', [$weekStart, $weekEnd])
                    ->where('status', '!=', 'completed'); // Only exclude completed tasks
                
                // Apply project filter if provided
                if ($projectId) {
                    $assignedTasksQuery->where('project_id', $projectId);
                }
                
                $assignedTasks = $assignedTasksQuery->get();
                
                // Detect deadline conflicts for this adiutor
                $deadlineConflicts = [];
                $tasksByDeadline = $assignedTasks->groupBy(function($task) {
                    return \Carbon\Carbon::parse($task->deadline)->format('Y-m-d');
                });
                
                foreach ($tasksByDeadline as $group) {
                    if ($group->count() > 1) {
                        // Multiple tasks with same deadline = conflict
                        // Sort by taskID and skip the first one (earliest assigned)
                        $sortedGroup = $group->sortBy('taskID');
                        $sortedGroup->shift(); // Remove first task
                        
                        foreach ($sortedGroup as $conflictingTask) {
                            $deadlineConflicts[] = $conflictingTask->taskID;
                        }
                    }
                }
                
                // Get task IDs that are already in scheduled tasks to avoid duplicates
                $scheduledTaskIds = $scheduledTasks->pluck('task_id')->toArray();
                
                foreach ($assignedTasks as $task) {
                    // Skip if already scheduled
                    if (in_array($task->taskID, $scheduledTaskIds)) {
                        continue;
                    }
                    
                    // Skip if has deadline conflict (should only appear in Unscheduled Tasks)
                    if (in_array($task->taskID, $deadlineConflicts)) {
                        continue;
                    }
                    
                    $deadline = \Carbon\Carbon::parse($task->deadline);
                    
                    // Calculate days until deadline
                    $now = \Carbon\Carbon::now();
                    $daysUntil = (int) $now->diffInDays($deadline, false);
                    
                    // Show task at 5 PM on deadline date (default time if not scheduled)
                    $slots[] = [
                        'date' => $deadline->format('Y-m-d'),
                        'hour' => 17, // 5 PM
                        'type' => 'task',
                        'title' => $task->taskTitle ?? 'Task',
                        'description' => $task->taskDescription ?? '',
                        'duration' => $daysUntil,
                        'is_synced' => false,
                        'is_deadline' => true
                    ];
                }
                
                // 3. Fetch Google Calendar events if connected
                $calendarIntegration = \App\Models\AdiutorCalendarIntegration::where('adiutor_id', $id)
                    ->where('is_connected', true)
                    ->first();
                
                if ($calendarIntegration) {
                    try {
                        $calendarService = app(\App\Services\GoogleCalendarService::class);
                        $calendarEvents = $calendarService->getEvents($user, $weekStart, $weekEnd);
                        
                        // Add calendar events to slots (exclude CMS-synced tasks to avoid duplicates)
                        foreach ($calendarEvents as $event) {
                            // Skip events created by CMS (they're already shown from task_schedules)
                            $title = $event['title'] ?? '';
                            if (str_starts_with($title, '[CMS]')) {
                                continue;
                            }
                            
                            $start = \Carbon\Carbon::parse($event['start']);
                            $end = \Carbon\Carbon::parse($event['end']);
                            
                            // Calculate days until event (from today)
                            $now = \Carbon\Carbon::now();
                            $daysUntil = (int) $now->diffInDays($end, false); // Use end date for countdown
                            
                            $slots[] = [
                                'date' => $end->format('Y-m-d'), // Show on end/deadline date
                                'hour' => $end->hour,
                                'type' => 'calendar',
                                'title' => $title,
                                'duration' => $daysUntil,
                                'is_synced' => true
                            ];
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to fetch calendar events: ' . $e->getMessage());
                        // Continue without calendar events if there's an error
                    }
                }
                
                return response()->json([
                    'slots' => $slots,
                    'calendar_connected' => (bool) $calendarIntegration
                ]);
            } catch (\Exception $e) {
                \Log::error('Calendar timeline error: ' . $e->getMessage());
                return response()->json([
                    'error' => $e->getMessage(),
                    'slots' => []
                ], 500);
            }
        })->name('api.schedule.timeline');
        
        // Project schedule timeline endpoint (all adiutors)
        Route::get('/schedule/project/{id}/timeline', function($id, \Illuminate\Http\Request $request) {
            try {
                $project = \App\Models\Project::findOrFail($id);
                $startDate = $request->query('start_date', now()->startOfWeek()->format('Y-m-d'));
                
                // Parse start date
                $weekStart = \Carbon\Carbon::parse($startDate);
                $weekEnd = $weekStart->copy()->addDays(6)->endOfDay(); // Monday to Sunday
                
                // Build slots array
                $slots = [];
                
                // Get all adiutors assigned to this project
                $projectAdiutorIds = \App\Models\ProjectAssignment::where('project_id', $id)
                    ->pluck('adiutor_id')
                    ->toArray();
                
                // 1. Get scheduled tasks from task_schedules table (all tasks for project adiutors)
                // Check if scheduled_end (deadline) falls within the week
                $scheduledTasks = \App\Models\TaskSchedule::whereBetween('scheduled_end', [$weekStart, $weekEnd])
                    ->with(['task', 'adiutor'])
                    ->whereIn('adiutor_id', $projectAdiutorIds)
                    ->get();
                
                foreach ($scheduledTasks as $schedule) {
                    // Use scheduled_end (deadline) for display, not scheduled_start
                    $deadline = \Carbon\Carbon::parse($schedule->scheduled_end);
                    
                    // Calculate days until deadline (from today)
                    $now = \Carbon\Carbon::now();
                    $daysUntil = (int) $now->diffInDays($deadline, false); // false = signed difference
                    
                    $slots[] = [
                        'date' => $deadline->format('Y-m-d'), // Show on deadline date
                        'hour' => $deadline->hour,
                        'type' => 'task',
                        'title' => ($schedule->task->taskTitle ?? 'Task') . ' - ' . ($schedule->adiutor->fullName ?? 'Unknown'),
                        'description' => $schedule->task->taskDescription ?? '',
                        'duration' => $daysUntil,
                        'is_synced' => $schedule->isSynced(),
                        'adiutor' => $schedule->adiutor->fullName ?? 'Unknown'
                    ];
                }
                
                // 2. Get assigned tasks with deadlines for all project adiutors (from any project)
                $assignedTasks = \App\Models\Task::whereIn('assignedTo', $projectAdiutorIds)
                    ->whereNotNull('deadline')
                    ->whereBetween('deadline', [$weekStart, $weekEnd])
                    ->where('status', '!=', 'completed') // Only exclude completed tasks
                    ->with('assignedUser')
                    ->get();
                
                \Log::info('Project timeline - assigned tasks with deadlines', [
                    'project_id' => $id,
                    'week_start' => $weekStart->format('Y-m-d'),
                    'week_end' => $weekEnd->format('Y-m-d'),
                    'tasks_count' => $assignedTasks->count(),
                    'tasks' => $assignedTasks->map(function($t) {
                        return [
                            'id' => $t->taskID,
                            'title' => $t->taskTitle,
                            'deadline' => $t->deadline,
                            'assigned_to' => $t->assignedTo,
                            'status' => $t->status
                        ];
                    })
                ]);
                
                // Detect deadline conflicts for tasks in this project
                $deadlineConflicts = [];
                $tasksByAdiutorAndDeadline = $assignedTasks->groupBy(function($task) {
                    return $task->assignedTo . '_' . \Carbon\Carbon::parse($task->deadline)->format('Y-m-d');
                });
                
                foreach ($tasksByAdiutorAndDeadline as $group) {
                    if ($group->count() > 1) {
                        // Multiple tasks with same deadline for same adiutor = conflict
                        // Sort by taskID and skip the first one (earliest assigned)
                        $sortedGroup = $group->sortBy('taskID');
                        $sortedGroup->shift(); // Remove first task
                        
                        foreach ($sortedGroup as $conflictingTask) {
                            $deadlineConflicts[] = $conflictingTask->taskID;
                        }
                    }
                }
                
                // Get task IDs that are already scheduled to avoid duplicates
                $scheduledTaskIds = $scheduledTasks->pluck('task_id')->toArray();
                
                foreach ($assignedTasks as $task) {
                    // Skip if already scheduled
                    if (in_array($task->taskID, $scheduledTaskIds)) {
                        continue;
                    }
                    
                    // Skip if has deadline conflict (should only appear in Unscheduled Tasks)
                    if (in_array($task->taskID, $deadlineConflicts)) {
                        continue;
                    }
                    
                    $deadline = \Carbon\Carbon::parse($task->deadline);
                    
                    // Calculate days until deadline
                    $now = \Carbon\Carbon::now();
                    $daysUntil = (int) $now->diffInDays($deadline, false);
                    
                    // Get assigned adiutor name
                    $adiutorName = $task->assignedUser ? $task->assignedUser->fullName : 'Unassigned';
                    
                    // Show task at 5 PM on deadline date (default time if not scheduled)
                    $slots[] = [
                        'date' => $deadline->format('Y-m-d'),
                        'hour' => 17, // 5 PM
                        'type' => 'task',
                        'title' => ($task->taskTitle ?? 'Task') . ' - ' . $adiutorName,
                        'description' => $task->taskDescription ?? '',
                        'duration' => $daysUntil,
                        'is_synced' => false,
                        'adiutor' => $adiutorName,
                        'is_deadline' => true
                    ];
                }
                
                return response()->json([
                    'slots' => $slots
                ]);
            } catch (\Exception $e) {
                \Log::error('Project timeline error: ' . $e->getMessage());
                return response()->json([
                    'error' => $e->getMessage(),
                    'slots' => []
                ], 500);
            }
        })->name('api.schedule.project.timeline');
        
        // Test Google Calendar fetch
        Route::get('/test-calendar/{id}', function($id) {
            $user = \App\Models\User::findOrFail($id);
            $integration = $user->calendarIntegration;
            
            if (!$integration) {
                return response()->json(['error' => 'No calendar integration found']);
            }
            
            if (!$integration->is_connected) {
                return response()->json(['error' => 'Calendar not connected']);
            }
            
            try {
                $service = app(\App\Services\GoogleCalendarService::class);
                $start = \Carbon\Carbon::now()->startOfWeek();
                $end = $start->copy()->addDays(6);
                
                $events = $service->getEvents($user, $start, $end);
                
                return response()->json([
                    'success' => true,
                    'integration' => [
                        'id' => $integration->id,
                        'provider' => $integration->provider,
                        'calendar_id' => $integration->calendar_id,
                        'is_connected' => $integration->is_connected,
                        'token_expires_at' => $integration->token_expires_at,
                    ],
                    'date_range' => [
                        'start' => $start->toDateTimeString(),
                        'end' => $end->toDateTimeString(),
                    ],
                    'event_count' => count($events),
                    'events' => $events
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
        
        // Test workload count
        Route::get('/test-workload/{name}', function($name) {
            $user = \App\Models\User::where('fullName', 'LIKE', '%' . $name . '%')
                ->where('role', 'adiutor')
                ->with(['assignedProjects' => function($query) {
                    $query->whereIn('project_assignments.status', ['active', 'in_progress']);
                }])
                ->first();
            
            if (!$user) {
                return response()->json(['error' => 'User not found']);
            }
            
            $allAssignments = \DB::table('project_assignments')
                ->where('adiutor_id', $user->id)
                ->get();
            
            return response()->json([
                'user_id' => $user->id,
                'full_name' => $user->fullName,
                'filtered_count' => $user->assignedProjects->count(),
                'filtered_projects' => $user->assignedProjects->pluck('id'),
                'all_assignments_count' => $allAssignments->count(),
                'all_assignments' => $allAssignments
            ]);
        });
        
        // Get adiutor standard rate
        Route::get('/adiutors/{id}/standard-rate', function($id) {
            $user = \App\Models\User::with('adiutorProfile')->findOrFail($id);
            
            if ($user->role !== 'adiutor') {
                return response()->json(['error' => 'User is not an adiutor'], 400);
            }
            
            $standardRate = $user->adiutorProfile->standard_hourly_rate ?? null;
            
            return response()->json([
                'standard_rate' => $standardRate,
                'adiutor_id' => $user->id,
                'adiutor_name' => $user->fullName
            ]);
        });
        
        // Schedule Task API
        Route::post('/schedule/task', function(\Illuminate\Http\Request $request) {
            try {
                $validated = $request->validate([
                    'task_id' => 'required|exists:tasks,taskID',
                    'adiutor_id' => 'required|exists:users,id',
                    'scheduled_start' => 'required|date',
                    'scheduled_end' => 'required|date|after:scheduled_start',
                ]);
                
                $task = \App\Models\Task::find($validated['task_id']);
                $adiutor = \App\Models\User::find($validated['adiutor_id']);
                
                // Calculate duration in minutes
                $start = \Carbon\Carbon::parse($validated['scheduled_start']);
                $end = \Carbon\Carbon::parse($validated['scheduled_end']);
                $durationMinutes = $start->diffInMinutes($end);
                
                // Check for deadline conflicts: same adiutor, same deadline date, different task
                if ($task->deadline) {
                    $deadlineDate = \Carbon\Carbon::parse($task->deadline)->format('Y-m-d');
                    
                    $conflictingTask = \App\Models\Task::where('assignedTo', $validated['adiutor_id'])
                        ->whereNotNull('deadline')
                        ->whereRaw('DATE(deadline) = ?', [$deadlineDate])
                        ->where('taskID', '!=', $validated['task_id'])
                        ->whereHas('schedule') // Only check tasks that are already scheduled
                        ->with('schedule')
                        ->first();
                    
                    if ($conflictingTask && $conflictingTask->taskID < $task->taskID) {
                        // There's an older task with the same deadline already scheduled
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot schedule this task. There is already an older task (Task #{$conflictingTask->taskID}: {$conflictingTask->taskTitle}) with the same deadline ({$deadlineDate}) scheduled for this adiutor.",
                            'conflict' => [
                                'task_id' => $conflictingTask->taskID,
                                'task_title' => $conflictingTask->taskTitle,
                                'deadline' => $conflictingTask->deadline,
                            ]
                        ], 409); // 409 Conflict
                    }
                }
                
                // Check if already scheduled and update, or create new
                $schedule = \App\Models\TaskSchedule::updateOrCreate(
                    [
                        'task_id' => $validated['task_id'],
                        'adiutor_id' => $validated['adiutor_id'],
                    ],
                    [
                        'scheduled_start' => $validated['scheduled_start'],
                        'scheduled_end' => $validated['scheduled_end'],
                        'estimated_duration_minutes' => $durationMinutes,
                        'schedule_type' => 'manual',
                    ]
                );
                
                \Log::info('Task schedule created/updated', [
                    'schedule_id' => $schedule->id,
                    'task_id' => $validated['task_id'],
                    'adiutor_id' => $validated['adiutor_id'],
                    'was_recently_created' => $schedule->wasRecentlyCreated,
                ]);
                
                // If adiutor has Google Calendar connected, create event
                \Log::info('Checking calendar integration', [
                    'adiutor_id' => $adiutor->id,
                    'has_integration' => $adiutor->calendarIntegration ? 'yes' : 'no',
                    'is_connected' => $adiutor->calendarIntegration ? $adiutor->calendarIntegration->is_connected : 'N/A'
                ]);
                
                if ($adiutor->calendarIntegration && $adiutor->calendarIntegration->is_connected) {
                    try {
                        \Log::info('Creating Google Calendar event', ['task_id' => $task->taskID]);
                        
                        $calendarService = app(\App\Services\GoogleCalendarService::class);
                        $eventId = $calendarService->createTaskEvent($adiutor, [
                            'title' => $task->taskTitle,
                            'description' => $task->taskDescription,
                            'project' => $task->project->title ?? 'N/A',
                            'priority' => $task->priority ?? 'medium',
                            'start' => \Carbon\Carbon::parse($validated['scheduled_start']),
                            'end' => \Carbon\Carbon::parse($validated['scheduled_end']),
                            'url' => route('adiutor.tasks.show', $task->taskID)
                        ]);
                        
                        \Log::info('Google Calendar event created', [
                            'task_id' => $task->taskID,
                            'event_id' => $eventId
                        ]);
                        
                        $schedule->update(['google_calendar_event_id' => $eventId]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to create Google Calendar event', [
                            'task_id' => $validated['task_id'],
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                } else {
                    \Log::warning('Adiutor does not have Google Calendar connected', [
                        'adiutor_id' => $adiutor->id
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Task scheduled successfully',
                    'schedule' => $schedule
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('Validation failed for task scheduling', [
                    'errors' => $e->errors(),
                    'request' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                \Log::error('Failed to schedule task', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'request' => $request->all()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
        })->name('api.schedule.task');
        
        // Google Calendar Integration routes (API - with different names to avoid conflicts)
        Route::prefix('calendar')->name('api.calendar.')->middleware('auth')->group(function () {
            Route::get('/', [\App\Http\Controllers\CalendarController::class, 'index'])->name('index');
            Route::get('/connection', [\App\Http\Controllers\CalendarController::class, 'connection'])->name('connection');
            Route::get('/connect', [\App\Http\Controllers\CalendarController::class, 'connect'])->name('connect');
            Route::get('/callback', [\App\Http\Controllers\CalendarController::class, 'callback'])->name('callback');
            Route::post('/disconnect', [\App\Http\Controllers\CalendarController::class, 'disconnect'])->name('disconnect');
            Route::get('/test-connection', [\App\Http\Controllers\CalendarController::class, 'testConnection'])->name('test');
        });
        
        Route::prefix('messages')->name('api.messages.')->middleware('throttle:60,1')->group(function () {
            Route::get('/conversations', [\App\Http\Controllers\Api\MessageController::class, 'index'])->name('conversations');
            Route::get('/unread-count', [\App\Http\Controllers\Api\MessageController::class, 'unreadCount'])->name('unread-count');
            Route::get('/search', [\App\Http\Controllers\Api\MessageController::class, 'search'])->name('search');
            Route::post('/fcm-token', [\App\Http\Controllers\Api\MessageController::class, 'updateFcmToken'])->name('update-fcm-token');
            Route::get('/projects/{project}', [\App\Http\Controllers\Api\MessageController::class, 'show'])->name('show');
            Route::post('/projects/{project}', [\App\Http\Controllers\Api\MessageController::class, 'store'])->middleware('throttle:30,1')->name('store');
            Route::post('/projects/{project}/mark-read', [\App\Http\Controllers\Api\MessageController::class, 'markAsRead'])->name('mark-read');
            Route::delete('/{message}', [\App\Http\Controllers\Api\MessageController::class, 'destroy'])->name('destroy');
        });

        // Group Chat API routes (admins and adiutors only, NOT clients)
        Route::prefix('group-chats')->name('api.group-chats.')->middleware('throttle:60,1')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\GroupChatController::class, 'index'])->name('index');
            Route::get('/unread-count', [\App\Http\Controllers\Api\GroupChatController::class, 'unreadCount'])->name('unread-count');
            Route::get('/{groupChat}', [\App\Http\Controllers\Api\GroupChatController::class, 'show'])->name('show');
            Route::post('/{groupChat}', [\App\Http\Controllers\Api\GroupChatController::class, 'store'])->middleware('throttle:30,1')->name('store');
            Route::post('/{groupChat}/mark-read', [\App\Http\Controllers\Api\GroupChatController::class, 'markAsRead'])->name('mark-read');
            Route::post('/{groupChat}/archive', [\App\Http\Controllers\Api\GroupChatController::class, 'archive'])->name('archive');
            Route::post('/{groupChat}/reopen', [\App\Http\Controllers\Api\GroupChatController::class, 'reopen'])->name('reopen');
        });
        
        // Meeting API routes
        Route::prefix('meetings')->name('api.meetings.')->group(function () {
            Route::get('/projects/{project}', [\App\Http\Controllers\Api\MeetingController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Api\MeetingController::class, 'store'])->name('store');
            Route::put('/{meeting}/approve', [\App\Http\Controllers\Api\MeetingController::class, 'approve'])->name('approve');
            Route::put('/{meeting}/reschedule', [\App\Http\Controllers\Api\MeetingController::class, 'reschedule'])->name('reschedule');
            Route::put('/{meeting}/reject', [\App\Http\Controllers\Api\MeetingController::class, 'reject'])->name('reject');
            Route::put('/{meeting}/approve-reschedule', [\App\Http\Controllers\Api\MeetingController::class, 'approveReschedule'])->name('approve-reschedule');
            Route::put('/{meeting}/reject-reschedule', [\App\Http\Controllers\Api\MeetingController::class, 'rejectReschedule'])->name('reject-reschedule');
            Route::delete('/{meeting}', [\App\Http\Controllers\Api\MeetingController::class, 'destroy'])->name('destroy');
        });
    });
});

// Public routes
Route::get('/', function () {
    // Get active announcements for public
    $announcements = DB::table('announcements')
        ->join('users', 'announcements.created_by', '=', 'users.id')
        ->where('announcements.status', 'active')
        ->where(function ($query) {
            $query->where('announcements.target_audience', 'LIKE', '%public%')
                  ->orWhere('announcements.target_audience', 'LIKE', '%all%');
        })
        ->where(function ($query) {
            $query->whereNull('announcements.expires_at')
                  ->orWhere('announcements.expires_at', '>', now());
        })
        ->where(function ($query) {
            $query->whereNull('announcements.starts_at')
                  ->orWhere('announcements.starts_at', '<=', now());
        })
        ->select(
            'announcements.id',
            'announcements.title',
            'announcements.content',
            'announcements.priority',
            'announcements.status',
            'announcements.starts_at',
            'announcements.expires_at',
            'announcements.created_at',
            'users.fullName as creator_name'
        )
        ->orderByRaw("FIELD(announcements.priority, 'high', 'medium', 'low')")
        ->orderBy('announcements.created_at', 'desc')
        ->get();
    
    return view('public.welcome', compact('announcements'));
})->name('home');

Route::get('/services', function () {
    return view('public.services');
})->name('services');

Route::get('/about', function () {
    return view('public.about');
})->name('about');

Route::get('/about-us', function () {
    return view('public.about');
})->name('about-us');

Route::get('/contact', function () {
    $user = Auth::user();
    $isLoggedIn = $user !== null;
    return view('public.get-started', compact('user', 'isLoggedIn'));
})->name('contact');

Route::get('/featured-projects', function () {
    return view('public.featured-projects');
})->name('featured-projects');

Route::get('/project-details', function () {
    return view('public.project-details');
})->name('public.project-details');

Route::get('/client-testimonials', function () {
    return view('public.client-testimonials');
})->name('client-testimonials');

Route::get('/faq', function () {
    return view('public.faq');
})->name('faq');

Route::get('/referral-program', function () {
    return view('public.referral-program');
})->name('referral-program');

Route::get('/privacy-policy', function () {
    return view('public.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-and-conditions', function () {
    return view('public.terms-and-conditions');
})->name('terms-and-conditions');

// Public service request form
Route::get('/get-started', [PublicServiceRequestController::class, 'create'])->name('get-started');
Route::post('/get-started', [PublicServiceRequestController::class, 'store'])->name('get-started.submit');

// Public service request routes (accessible without authentication)
Route::get('/client/requests/create', [\App\Http\Controllers\Client\ServiceRequestController::class, 'create'])->name('client.requests.create');
Route::post('/client/requests/store', [\App\Http\Controllers\Client\ServiceRequestController::class, 'store'])->name('client.requests.store');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1'); // 3 attempts per minute
    
    // Password Reset routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1');
    
    // Firebase Authentication Routes
    Route::prefix('auth/firebase')->name('firebase.')->group(function () {
        Route::post('/callback', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'handleCallback'])->name('callback')->middleware('throttle:10,1');
        Route::get('/config', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'getConfig'])->name('config');
    });
});

// Firebase account linking (requires authentication)
Route::middleware('auth')->prefix('auth/firebase')->name('firebase.')->group(function () {
    Route::post('/link/initiate', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'initiateLink'])->name('link.initiate');
    Route::post('/unlink', [\App\Http\Controllers\Auth\FirebaseAuthController::class, 'unlinkAccount'])->name('unlink');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Notification routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/notifications/fetch', [\App\Http\Controllers\NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Admin authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });
    
    Route::post('/logout', [AdminController::class, 'logout'])->middleware('auth')->name('logout');
});

// Admin routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/refresh', [AdminController::class, 'refreshDashboard'])->name('dashboard.refresh');
    Route::get('/dashboard/download-report', [AdminController::class, 'downloadReport'])->name('dashboard.download');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::get('/api/announcements/active', [\App\Http\Controllers\Admin\AnnouncementController::class, 'getActive'])->name('announcements.active');
    
    // Budget Change Requests
    Route::prefix('budget-requests')->name('budget-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\BudgetChangeRequestController::class, 'destroy'])->name('destroy');
    });
    
    // Payout Management
    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'export'])->name('export');
        Route::get('/adiutor/{adiutorId}', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'adiutorEarnings'])->name('adiutor-earnings');
        Route::post('/approve-time-entries', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'approveTimeEntries'])->name('approve-entries');
        
        // Time Entry Approval with Adjustment (Phase 4)
        Route::get('/time-entries/{id}', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'getTimeEntryDetails'])->name('time-entry-details');
        Route::post('/time-entries/{id}/approve', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'approveTimeEntry'])->name('approve-time-entry');
        Route::post('/time-entries/{id}/reject', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'rejectTimeEntry'])->name('reject-time-entry');
        
        Route::get('/{id}', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'show'])->name('show');
        Route::post('/{id}/process', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'markAsProcessing'])->name('process');
        Route::post('/{id}/complete', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'complete'])->name('complete');
        Route::post('/{id}/cancel', [\App\Http\Controllers\Admin\PayoutManagementController::class, 'cancel'])->name('cancel');
    });
    
    // Hour Increase Requests Management
    Route::prefix('hour-requests')->name('hour-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\HourIncreaseRequestController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\HourIncreaseRequestController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [\App\Http\Controllers\Admin\HourIncreaseRequestController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\Admin\HourIncreaseRequestController::class, 'reject'])->name('reject');
        Route::post('/{id}/quick-approve', [\App\Http\Controllers\Admin\HourIncreaseRequestController::class, 'quickApprove'])->name('quick-approve');
    });
    
    // Payment Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'export'])->name('export');
        Route::get('/{id}', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [\App\Http\Controllers\Admin\PaymentManagementController::class, 'updateStatus'])->name('update-status');
    });
    
    // Project Template Management
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', [AdminTemplateController::class, 'index'])->name('index');
        Route::get('/create', [AdminTemplateController::class, 'create'])->name('create');
        Route::post('/', [AdminTemplateController::class, 'store'])->name('store');
        Route::get('/{template}', [AdminTemplateController::class, 'show'])->name('show');
        Route::get('/{template}/edit', [AdminTemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [AdminTemplateController::class, 'update'])->name('update');
        Route::delete('/{template}', [AdminTemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{template}/toggle', [AdminTemplateController::class, 'toggle'])->name('toggle');
        Route::post('/{template}/duplicate', [AdminTemplateController::class, 'duplicate'])->name('duplicate');
    });
    
    // Audit Log Management
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [AdminAuditController::class, 'index'])->name('index');
        Route::get('/{auditLog}', [AdminAuditController::class, 'show'])->name('show');
        Route::get('/api/logs', [AdminAuditController::class, 'logs'])->name('logs');
        Route::get('/api/statistics', [AdminAuditController::class, 'statistics'])->name('statistics');
        Route::get('/export/csv', [AdminAuditController::class, 'export'])->name('export');
        Route::post('/cleanup', [AdminAuditController::class, 'cleanup'])->name('cleanup');
    });
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
    Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/bulk-action', [\App\Http\Controllers\Admin\UserManagementController::class, 'bulkAction'])->name('users.bulk-action');
    
    // Client Management
    Route::get('/clients/archived', [\App\Http\Controllers\Admin\ClientManagementController::class, 'archived'])->name('clients.archived');
    Route::get('/clients/export', [\App\Http\Controllers\Admin\ClientManagementController::class, 'export'])->name('clients.export');
    Route::post('/clients/bulk-action', [\App\Http\Controllers\Admin\ClientManagementController::class, 'bulkAction'])->name('clients.bulk-action');
    Route::resource('clients', \App\Http\Controllers\Admin\ClientManagementController::class);
    Route::post('/clients/{client}/notes', [\App\Http\Controllers\Admin\ClientManagementController::class, 'addNote'])->name('clients.notes.store');
    Route::put('/clients/{client}/notes/{note}', [\App\Http\Controllers\Admin\ClientManagementController::class, 'updateNote'])->name('clients.notes.update');
    Route::delete('/clients/{client}/notes/{note}', [\App\Http\Controllers\Admin\ClientManagementController::class, 'deleteNote'])->name('clients.notes.destroy');
    Route::post('/clients/{client}/restore', [\App\Http\Controllers\Admin\ClientManagementController::class, 'restore'])->name('clients.restore');
    
    // Task Management
    Route::resource('tasks', \App\Http\Controllers\Admin\TaskManagementController::class);
    Route::post('/tasks/{task}/assign', [\App\Http\Controllers\Admin\TaskManagementController::class, 'assign'])->name('tasks.assign');
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\Admin\TaskManagementController::class, 'updateStatus'])->name('tasks.update-status');
    Route::patch('/tasks/{task}/update-notes', [\App\Http\Controllers\Admin\TaskManagementController::class, 'updateNotes'])->name('tasks.update-notes');
    Route::patch('/tasks/{task}/update-budget', [\App\Http\Controllers\Admin\TaskManagementController::class, 'updateBudget'])->name('tasks.update-budget');
    Route::get('/service-requests/{serviceRequest}/budget-overview', [\App\Http\Controllers\Admin\TaskManagementController::class, 'budgetOverview'])->name('service-requests.budget-overview');
    Route::post('/tasks/bulk-action', [\App\Http\Controllers\Admin\TaskManagementController::class, 'bulkAction'])->name('tasks.bulk-action');
    Route::post('/tasks/reorder', [\App\Http\Controllers\Admin\TaskManagementController::class, 'reorder'])->name('tasks.reorder');
    
    // Subtask Management
    Route::prefix('tasks/{task}')->name('tasks.')->group(function () {
        // Subtasks
        Route::get('/subtasks', [\App\Http\Controllers\Admin\SubtaskController::class, 'index'])->name('subtasks.index');
        Route::post('/subtasks', [\App\Http\Controllers\Admin\SubtaskController::class, 'store'])->name('subtasks.store');
        Route::post('/subtasks/bulk', [\App\Http\Controllers\Admin\SubtaskController::class, 'bulkStore'])->name('subtasks.bulk-store');
        Route::post('/subtasks/reorder', [\App\Http\Controllers\Admin\SubtaskController::class, 'reorder'])->name('subtasks.reorder');
        
        // Deliverables
        Route::get('/deliverables', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'index'])->name('deliverables.index');
        Route::post('/deliverables', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'store'])->name('deliverables.store');
        Route::post('/deliverables/reorder', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'reorder'])->name('deliverables.reorder');
        Route::get('/deliverables/check', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'checkDeliverables'])->name('deliverables.check');
    });
    
    // Individual subtask routes
    Route::prefix('subtasks/{subtask}')->name('subtasks.')->group(function () {
        Route::put('/', [\App\Http\Controllers\Admin\SubtaskController::class, 'update'])->name('update');
        Route::delete('/', [\App\Http\Controllers\Admin\SubtaskController::class, 'destroy'])->name('destroy');
        Route::post('/toggle', [\App\Http\Controllers\Admin\SubtaskController::class, 'toggle'])->name('toggle');
        Route::post('/complete', [\App\Http\Controllers\Admin\SubtaskController::class, 'complete'])->name('complete');
        Route::post('/incomplete', [\App\Http\Controllers\Admin\SubtaskController::class, 'incomplete'])->name('incomplete');
    });
    
    // Individual deliverable routes (using Document model with is_deliverable flag)
    Route::prefix('deliverables/{document}')->name('deliverables.')->group(function () {
        Route::put('/', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'update'])->name('update');
        Route::delete('/', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'destroy'])->name('destroy');
        Route::post('/approve', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'approve'])->name('approve');
        Route::post('/reject', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'reject'])->name('reject');
        Route::post('/revoke-approval', [\App\Http\Controllers\Admin\TaskDeliverableController::class, 'revokeApproval'])->name('revoke-approval');
    });
    
    // Request Management
    Route::resource('requests', \App\Http\Controllers\Admin\RequestManagementController::class, ['only' => ['index', 'show']]);
    Route::post('/requests/{request}/approve', [\App\Http\Controllers\Admin\RequestManagementController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{request}/reject', [\App\Http\Controllers\Admin\RequestManagementController::class, 'reject'])->name('requests.reject');
    Route::post('/requests/{request}/reopen', [\App\Http\Controllers\Admin\RequestManagementController::class, 'reopen'])->name('requests.reopen');
    Route::post('/requests/{request}/request-payment', [\App\Http\Controllers\Admin\RequestManagementController::class, 'requestPayment'])->name('requests.request-payment');
    // Note: confirmPayment is deprecated - Maya payment gateway handles confirmation automatically
    Route::post('/requests/{request}/confirm-payment', [\App\Http\Controllers\Admin\RequestManagementController::class, 'confirmPayment'])->name('requests.confirm-payment');
    Route::patch('/requests/{request}/priority', [\App\Http\Controllers\Admin\RequestManagementController::class, 'updatePriority'])->name('requests.update-priority');
    Route::post('/requests/{request}/notes', [\App\Http\Controllers\Admin\RequestManagementController::class, 'addNote'])->name('requests.add-note');
    Route::post('/requests/bulk-action', [\App\Http\Controllers\Admin\RequestManagementController::class, 'bulkAction'])->name('requests.bulk-action');
    Route::get('/requests/{request}/files/{file}/download', [\App\Http\Controllers\Admin\RequestManagementController::class, 'downloadFile'])->name('requests.download-file');
    Route::get('/requests/export', [\App\Http\Controllers\Admin\RequestManagementController::class, 'export'])->name('requests.export');
    
    // Coupon Management
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('store');
        Route::get('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'show'])->name('show');
        Route::get('/{coupon}/edit', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('edit');
        Route::put('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('destroy');
        Route::post('/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('toggle');
        Route::get('/{coupon}/usage', [\App\Http\Controllers\Admin\CouponController::class, 'usageHistory'])->name('usage');
        Route::post('/bulk-generate', [\App\Http\Controllers\Admin\CouponController::class, 'bulkGenerate'])->name('bulk');
        Route::post('/check-code', [\App\Http\Controllers\Admin\CouponController::class, 'checkCode'])->name('check-code');
    });
    
    // Loyalty Program Management
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LoyaltyController::class, 'index'])->name('index');
        Route::get('/leaderboard', [\App\Http\Controllers\Admin\LoyaltyController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/transactions', [\App\Http\Controllers\Admin\LoyaltyController::class, 'transactions'])->name('transactions');
        Route::get('/settings/tiers', [\App\Http\Controllers\Admin\LoyaltyController::class, 'tierSettings'])->name('settings');
        Route::put('/settings/tiers', [\App\Http\Controllers\Admin\LoyaltyController::class, 'updateTierSettings'])->name('settings.update');
        Route::get('/export/report', [\App\Http\Controllers\Admin\LoyaltyController::class, 'exportLoyaltyReport'])->name('export');
        Route::post('/expiry-warnings', [\App\Http\Controllers\Admin\LoyaltyController::class, 'sendExpiryWarnings'])->name('expiry-warnings');
        Route::get('/dashboard-widget', [\App\Http\Controllers\Admin\LoyaltyController::class, 'dashboardWidget'])->name('widget');
        Route::get('/{user}', [\App\Http\Controllers\Admin\LoyaltyController::class, 'show'])->name('show');
        Route::get('/{user}/transactions', [\App\Http\Controllers\Admin\LoyaltyController::class, 'userTransactions'])->name('user-transactions');
        Route::get('/{user}/export', [\App\Http\Controllers\Admin\LoyaltyController::class, 'exportUserReport'])->name('export-user');
        Route::post('/{user}/adjust', [\App\Http\Controllers\Admin\LoyaltyController::class, 'adjustPoints'])->name('adjust-points');
    });
    
    // Referral Program Management
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReferralController::class, 'index'])->name('index');
        Route::get('/list', [\App\Http\Controllers\Admin\ReferralController::class, 'list'])->name('list');
        Route::get('/codes', [\App\Http\Controllers\Admin\ReferralController::class, 'codes'])->name('codes');
        Route::get('/analytics', [\App\Http\Controllers\Admin\ReferralController::class, 'analytics'])->name('analytics');
        Route::get('/export', [\App\Http\Controllers\Admin\ReferralController::class, 'export'])->name('export');
        Route::get('/{referral}', [\App\Http\Controllers\Admin\ReferralController::class, 'show'])->name('show');
        Route::post('/{referral}/process', [\App\Http\Controllers\Admin\ReferralController::class, 'processPending'])->name('process');
        Route::patch('/codes/{code}/toggle', [\App\Http\Controllers\Admin\ReferralController::class, 'toggleCodeStatus'])->name('codes.toggle');
        
        // Referral Credit Withdrawals Management
        Route::get('/withdrawals/pending', [\App\Http\Controllers\Admin\ReferralController::class, 'withdrawalsPending'])->name('withdrawals.pending');
        Route::get('/withdrawals/{withdrawal}', [\App\Http\Controllers\Admin\ReferralController::class, 'showWithdrawal'])->name('withdrawals.show');
        Route::post('/withdrawals/{withdrawal}/process', [\App\Http\Controllers\Admin\ReferralController::class, 'processWithdrawal'])->name('withdrawals.process');
        Route::post('/withdrawals/{withdrawal}/complete', [\App\Http\Controllers\Admin\ReferralController::class, 'completeWithdrawal'])->name('withdrawals.complete');
        Route::post('/withdrawals/{withdrawal}/reject', [\App\Http\Controllers\Admin\ReferralController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
    });
    
    // Document Management
    Route::resource('documents', \App\Http\Controllers\Admin\DocumentManagementController::class);
    Route::get('/documents/{document}/download', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}/preview', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'preview'])->name('documents.preview');
    Route::post('/documents/bulk-action', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'bulkAction'])->name('documents.bulk-action');
    Route::get('/documents/search', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'search'])->name('documents.search');
    Route::post('/projects/{project}/documents', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'uploadToProject'])->name('projects.documents.upload');
    
    // Bulk Upload
    Route::get('/documents-bulk-upload', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'bulkCreate'])->name('documents.bulk-create');
    Route::post('/documents-bulk-upload', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'bulkStore'])->name('documents.bulk-store');
    
    // Document Trash Management
    Route::get('/documents-trash', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'trash'])->name('documents.trash');
    Route::post('/documents/{document}/restore', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'restore'])->name('documents.restore');
    Route::delete('/documents/{document}/force-delete', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'forceDelete'])->name('documents.force-delete');
    Route::post('/documents-trash/bulk-restore', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'bulkRestore'])->name('documents.bulk-restore');
    Route::delete('/documents-trash/empty', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'emptyTrash'])->name('documents.empty-trash');
    
    // Deliverable Approval Management
    Route::get('/deliverables/pending', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'pendingDeliverables'])->name('deliverables.pending');
    Route::post('/deliverables/{document}/approve', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'approveDeliverable'])->name('deliverables.approve');
    Route::post('/deliverables/{document}/reject', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'rejectDeliverable'])->name('deliverables.reject');
    Route::post('/deliverables/{document}/revoke', [\App\Http\Controllers\Admin\DocumentManagementController::class, 'revokeApproval'])->name('deliverables.revoke');
    
    // Revision Management
    Route::prefix('revisions')->name('revisions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RevisionController::class, 'index'])->name('index');
        Route::get('/{revision}', [\App\Http\Controllers\Admin\RevisionController::class, 'show'])->name('show');
        Route::post('/{revision}/approve', [\App\Http\Controllers\Admin\RevisionController::class, 'approve'])->name('approve');
        Route::post('/{revision}/reject', [\App\Http\Controllers\Admin\RevisionController::class, 'reject'])->name('reject');
        Route::post('/{revision}/reassign', [\App\Http\Controllers\Admin\RevisionController::class, 'reassign'])->name('reassign');
    });
    
    // Calendar Management
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('index');
    });
    
    // Feedback Management
    // Note: Static routes must be defined BEFORE the resource route to avoid {feedback} param conflict
    Route::get('/feedback/analytics', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'analytics'])->name('feedback.analytics');
    Route::get('/feedback/export', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'export'])->name('feedback.export');
    Route::get('/feedback/summary', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'summary'])->name('feedback.summary');
    Route::post('/feedback/bulk-action', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'bulkAction'])->name('feedback.bulk-action');
    Route::resource('feedback', \App\Http\Controllers\Admin\FeedbackManagementController::class, ['only' => ['index', 'show']]);
    Route::post('/feedback/{feedback}/respond', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'respond'])->name('feedback.respond');
    Route::patch('/feedback/{feedback}/status', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'updateStatus'])->name('feedback.update-status');
    Route::post('/feedback/{feedback}/assign', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'assignTo'])->name('feedback.assign');
    Route::post('/feedback/{feedback}/notes', [\App\Http\Controllers\Admin\FeedbackManagementController::class, 'addNote'])->name('feedback.add-note');
    
    // Workflow Management (Service Request → Project → Task)
    Route::prefix('workflow')->name('workflow.')->group(function () {
        Route::post('/requests/{request}/approve', [\App\Http\Controllers\Admin\WorkflowController::class, 'approveRequest'])->name('requests.approve');
        Route::post('/requests/{request}/reject', [\App\Http\Controllers\Admin\WorkflowController::class, 'rejectRequest'])->name('requests.reject');
        Route::post('/requests/{request}/confirm-payment', [\App\Http\Controllers\Admin\WorkflowController::class, 'confirmPayment'])->name('requests.confirm-payment');
        Route::post('/projects/{project}/tasks', [\App\Http\Controllers\Admin\WorkflowController::class, 'createTask'])->name('projects.tasks.create');
        Route::get('/projects/{project}/budget-overview', [\App\Http\Controllers\Admin\WorkflowController::class, 'getProjectBudgetOverview'])->name('projects.budget-overview');
    });

    // Project Management (NEW)
    Route::resource('projects', \App\Http\Controllers\Admin\ProjectManagementController::class);
    Route::get('/projects/{project}/schedule', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'schedule'])->name('projects.schedule');
    Route::post('/projects/{project}/notes', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'addNote'])->name('projects.notes.store');
    Route::patch('/projects/{project}/status', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'updateStatus'])->name('projects.update-status');
    Route::patch('/projects/{project}/complete', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'complete'])->name('projects.complete');
    Route::post('/projects/bulk-action', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'bulkAction'])->name('projects.bulk-action');
    
    // Team Management routes
    Route::post('/projects/{project}/assign-adiutor', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'assignAdiutor'])->name('projects.assign-adiutor');
    Route::delete('/projects/{project}/adiutors/{adiutor}', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'removeAdiutor'])->name('projects.remove-adiutor');
    Route::get('/projects/{project}/team-members', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'getTeamMembers'])->name('projects.team-members');
    Route::get('/projects/{project}/phases', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'getProjectPhases'])->name('projects.phases');
    
    // Assignment Payment Management (Phase 1: Fixed Rate Approval)
    Route::post('/projects/{project}/assignments/{assignment}/approve-fixed-rate', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'approveFixedRate'])->name('projects.assignments.approve-fixed-rate');
    Route::post('/projects/{project}/assignments/{assignment}/revoke-fixed-rate', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'revokeFixedRateApproval'])->name('projects.assignments.revoke-fixed-rate');
    Route::put('/projects/{project}/assignments/{assignment}/payment', [\App\Http\Controllers\Admin\ProjectManagementController::class, 'updateAssignmentPayment'])->name('projects.assignments.update-payment');
    
    // Reporting and Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReportingController::class, 'index'])->name('index');
        Route::get('/dashboard', [\App\Http\Controllers\Admin\ReportingController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\Admin\ReportingController::class, 'users'])->name('users');
        Route::get('/tasks', [\App\Http\Controllers\Admin\ReportingController::class, 'tasks'])->name('tasks');
        Route::get('/requests', [\App\Http\Controllers\Admin\ReportingController::class, 'requests'])->name('requests');
        Route::get('/projects', [\App\Http\Controllers\Admin\ReportingController::class, 'projects'])->name('projects');
        Route::get('/documents', [\App\Http\Controllers\Admin\ReportingController::class, 'documents'])->name('documents');
        Route::get('/export', [\App\Http\Controllers\Admin\ReportingController::class, 'export'])->name('export');
        Route::get('/custom', [\App\Http\Controllers\Admin\ReportingController::class, 'customReport'])->name('custom');
        
        // Custom Reports API endpoints
        Route::post('/custom/generate', [\App\Http\Controllers\Admin\ReportingController::class, 'generateCustomReport'])->name('custom.generate');
        Route::post('/custom/preview', [\App\Http\Controllers\Admin\ReportingController::class, 'previewCustomReport'])->name('custom.preview');
        Route::post('/custom/templates', [\App\Http\Controllers\Admin\ReportingController::class, 'saveCustomTemplate'])->name('custom.template.save');
        Route::get('/custom/templates', [\App\Http\Controllers\Admin\ReportingController::class, 'getCustomTemplates'])->name('custom.templates');
        Route::get('/custom/filters/{type}', [\App\Http\Controllers\Admin\ReportingController::class, 'getFilterOptions'])->name('custom.filters');
    });
    
    // Earnings Analytics (Phase 7)
    Route::prefix('earnings-analytics')->name('earnings-analytics.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EarningsAnalyticsController::class, 'index'])->name('index');
        Route::get('/leaderboard', [\App\Http\Controllers\Admin\EarningsAnalyticsController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/project-costs', [\App\Http\Controllers\Admin\EarningsAnalyticsController::class, 'projectCosts'])->name('project-costs');
        Route::get('/audit-log', [\App\Http\Controllers\Admin\EarningsAnalyticsController::class, 'auditLog'])->name('audit-log');
        Route::get('/payout-history', [\App\Http\Controllers\Admin\EarningsAnalyticsController::class, 'payoutHistory'])->name('payout-history');
        Route::get('/export', [\App\Http\Controllers\Admin\EarningsAnalyticsController::class, 'export'])->name('export');
    });
    
    // Messaging routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [AdminController::class, 'messages'])->name('index');
        Route::get('/projects/{project}', [AdminController::class, 'showMessages'])->name('show');
    });
    
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
});

// Client routes
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects', [ClientController::class, 'index'])->name('projects.index');
    Route::get('/tasks', [ClientController::class, 'tasks'])->name('tasks');
    Route::get('/requests', [ClientController::class, 'requests'])->name('requests');
    Route::get('/feedback', [ClientController::class, 'feedback'])->name('feedback');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Service Request routes (authenticated only)
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'show'])->name('show');
        Route::get('/{id}/payment', [\App\Http\Controllers\Client\ServiceRequestController::class, 'showPayment'])->name('show-payment');
        Route::get('/{id}/edit', [\App\Http\Controllers\Client\ServiceRequestController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Client\ServiceRequestController::class, 'destroy'])->name('destroy');
        Route::get('/{requestId}/attachments/{attachmentId}/download', [\App\Http\Controllers\Client\ServiceRequestController::class, 'downloadAttachment'])->name('attachment.download');
    });
    
    // Maya Payment routes
    Route::prefix('maya')->name('maya.')->group(function () {
        Route::get('/checkout/{serviceRequestId}', [\App\Http\Controllers\Client\MayaPaymentController::class, 'checkout'])->name('checkout');
        Route::get('/success', [\App\Http\Controllers\Client\MayaPaymentController::class, 'success'])->name('success');
        Route::get('/failure', [\App\Http\Controllers\Client\MayaPaymentController::class, 'failure'])->name('failure');
        Route::get('/cancel', [\App\Http\Controllers\Client\MayaPaymentController::class, 'cancel'])->name('cancel');
    });
    
    // Payment History routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\PaymentHistoryController::class, 'index'])->name('history');
        Route::get('/{id}', [\App\Http\Controllers\Client\PaymentHistoryController::class, 'show'])->name('show');
        Route::get('/{id}/receipt', [\App\Http\Controllers\Client\PaymentHistoryController::class, 'receipt'])->name('receipt');
    });
    
    // Project routes
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/{id}', [ClientController::class, 'showProject'])->name('show');
        Route::get('/{projectId}/documents/{documentId}/download', [ClientController::class, 'downloadDocument'])->name('documents.download');
    });
    
    // Documents route
    Route::get('/documents', [ClientController::class, 'documents'])->name('documents');
    
    // Feedback routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/{projectId}/create', [\App\Http\Controllers\Client\FeedbackController::class, 'create'])->name('create');
        Route::post('/{projectId}', [\App\Http\Controllers\Client\FeedbackController::class, 'store'])->name('store');
    });
    
    // Coupon routes
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\CouponController::class, 'index'])->name('index');
        Route::get('/{coupon}', [\App\Http\Controllers\Client\CouponController::class, 'show'])->name('show');
        Route::post('/validate', [\App\Http\Controllers\Client\CouponController::class, 'validateCode'])->name('validate');
        Route::post('/requests/{serviceRequest}/apply', [\App\Http\Controllers\Client\CouponController::class, 'applyCoupon'])->name('apply');
        Route::delete('/requests/{serviceRequest}/remove', [\App\Http\Controllers\Client\CouponController::class, 'removeCoupon'])->name('remove');
        Route::post('/{coupon}/copy', [\App\Http\Controllers\Client\CouponController::class, 'copyCode'])->name('copy');
    });
    
    // Loyalty Program routes
    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\LoyaltyController::class, 'dashboard'])->name('dashboard');
        Route::get('/transactions', [\App\Http\Controllers\Client\LoyaltyController::class, 'transactions'])->name('transactions');
        Route::post('/requests/{serviceRequest}/redeem', [\App\Http\Controllers\Client\LoyaltyController::class, 'redeemPoints'])->name('redeem');
        Route::delete('/requests/{serviceRequest}/remove-redemption', [\App\Http\Controllers\Client\LoyaltyController::class, 'removeRedemption'])->name('remove-redemption');
        Route::get('/widget-data', [\App\Http\Controllers\Client\LoyaltyController::class, 'widgetData'])->name('widget-data');
        Route::post('/calculate-earning', [\App\Http\Controllers\Client\LoyaltyController::class, 'calculateEarning'])->name('calculate-earning');
        Route::post('/calculate-discount', [\App\Http\Controllers\Client\LoyaltyController::class, 'calculateDiscount'])->name('calculate-discount');
    });
    
    // Referral Program routes
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\ReferralController::class, 'dashboard'])->name('dashboard');
        Route::get('/share', [\App\Http\Controllers\Client\ReferralController::class, 'share'])->name('share');
        Route::get('/history', [\App\Http\Controllers\Client\ReferralController::class, 'history'])->name('history');
        Route::get('/code', [\App\Http\Controllers\Client\ReferralController::class, 'getCode'])->name('code');
        Route::get('/stats', [\App\Http\Controllers\Client\ReferralController::class, 'getStats'])->name('stats');
        Route::post('/validate', [\App\Http\Controllers\Client\ReferralController::class, 'validateCode'])->name('validate');
        Route::post('/invite', [\App\Http\Controllers\Client\ReferralController::class, 'sendInvitation'])->name('invite');
        Route::post('/generate-link', [\App\Http\Controllers\Client\ReferralController::class, 'generateLink'])->name('generate-link');
        
        // Referral Credits & Withdrawals
        Route::get('/credits', [\App\Http\Controllers\Client\ReferralController::class, 'credits'])->name('credits');
        Route::post('/withdrawals', [\App\Http\Controllers\Client\ReferralController::class, 'requestWithdrawal'])->name('withdrawals.request');
        Route::get('/withdrawals/{id}', [\App\Http\Controllers\Client\ReferralController::class, 'showWithdrawal'])->name('withdrawals.show');
        Route::post('/withdrawals/{id}/cancel', [\App\Http\Controllers\Client\ReferralController::class, 'cancelWithdrawal'])->name('withdrawals.cancel');
    });
    
    // Revision Request routes
    Route::prefix('revisions')->name('revisions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Client\RevisionRequestController::class, 'index'])->name('index');
        Route::get('/{revision}', [\App\Http\Controllers\Client\RevisionRequestController::class, 'show'])->name('show');
        
        // Project-level revisions
        Route::get('/projects/{project}/create', [\App\Http\Controllers\Client\RevisionRequestController::class, 'createForProject'])->name('project.create');
        Route::post('/projects/{project}', [\App\Http\Controllers\Client\RevisionRequestController::class, 'storeForProject'])->name('project.store');
        
        // Task-level revisions (NEW)
        Route::post('/tasks/{task}', [\App\Http\Controllers\Client\RevisionRequestController::class, 'storeForTask'])->name('task.store');
        
        // Document-level revisions
        Route::get('/documents/{document}/create', [\App\Http\Controllers\Client\RevisionRequestController::class, 'create'])->name('create');
        Route::post('/documents/{document}', [\App\Http\Controllers\Client\RevisionRequestController::class, 'store'])->name('store');
        
        Route::post('/{revision}/cancel', [\App\Http\Controllers\Client\RevisionRequestController::class, 'cancel'])->name('cancel');
    });
    
    // Messaging routes
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [ClientController::class, 'messages'])->name('index');
        Route::get('/projects/{project}', [ClientController::class, 'showMessages'])->name('show');
    });
});

// Adiutor routes
Route::middleware(['auth', 'role:adiutor'])->prefix('adiutor')->name('adiutor.')->group(function () {
    Route::get('/dashboard', [AdiutorController::class, 'dashboard'])->name('dashboard');
    Route::get('/clients', [AdiutorController::class, 'clients'])->name('clients');
    Route::get('/documents', [AdiutorController::class, 'documents'])->name('documents');
    Route::get('/feedback', [AdiutorController::class, 'feedback'])->name('feedback');
    
    // Group Chat Routes
    Route::prefix('group-chats')->name('group-chats.')->group(function () {
        Route::get('/', [AdiutorController::class, 'groupChats'])->name('index');
        Route::get('/{project}', [AdiutorController::class, 'showGroupChat'])->name('show');
    });
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Projects Routes (Separate from Tasks)
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\ProjectController::class, 'index'])->name('index');
        Route::get('/{project}', [\App\Http\Controllers\Adiutor\ProjectController::class, 'show'])->name('show');
        Route::post('/{assignment}/accept', [\App\Http\Controllers\Adiutor\ProjectController::class, 'accept'])->name('accept');
        Route::post('/{assignment}/decline', [\App\Http\Controllers\Adiutor\ProjectController::class, 'decline'])->name('decline');
        Route::post('/{assignment}/update-progress', [\App\Http\Controllers\Adiutor\ProjectController::class, 'updateProgress'])->name('update-progress');
        Route::post('/{project}/create-task', [\App\Http\Controllers\Adiutor\ProjectController::class, 'createTask'])->name('create-task');
    });
    
    // Tasks Routes (Individual tasks within projects)
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\TaskController::class, 'index'])->name('index');
        Route::get('/{task}', [\App\Http\Controllers\Adiutor\TaskController::class, 'show'])->name('show');
        Route::post('/{task}/update-status', [\App\Http\Controllers\Adiutor\TaskController::class, 'updateStatus'])->name('update-status');
        Route::post('/{task}/complete', [\App\Http\Controllers\Adiutor\TaskController::class, 'markCompleted'])->name('complete');
        Route::post('/{task}/add-note', [\App\Http\Controllers\Adiutor\TaskController::class, 'addNote'])->name('add-note');
        Route::post('/{task}/upload-file', [\App\Http\Controllers\Adiutor\TaskController::class, 'uploadFile'])->name('upload-file');
        Route::post('/{task}/add-link-deliverable', [\App\Http\Controllers\Adiutor\TaskController::class, 'addLinkDeliverable'])->name('add-link-deliverable');
        Route::get('/download-file/{fileId}', [\App\Http\Controllers\Adiutor\TaskController::class, 'downloadFile'])->name('download-file');
        Route::delete('/delete-file/{fileId}', [\App\Http\Controllers\Adiutor\TaskController::class, 'deleteFile'])->name('delete-file');
        Route::post('/{task}/request-budget-change', [\App\Http\Controllers\Adiutor\TaskController::class, 'requestBudgetChange'])->name('request-budget-change');
        Route::post('/{task}/update-progress', [\App\Http\Controllers\Adiutor\TaskController::class, 'updateTaskProgress'])->name('update-progress');
        
        // Subtasks
        Route::get('/{task}/subtasks', [\App\Http\Controllers\Adiutor\SubtaskController::class, 'index'])->name('subtasks.index');
        Route::post('/{task}/subtasks', [\App\Http\Controllers\Adiutor\SubtaskController::class, 'store'])->name('subtasks.store');
        Route::post('/{task}/subtasks/reorder', [\App\Http\Controllers\Adiutor\SubtaskController::class, 'reorder'])->name('subtasks.reorder');
        
        // Deliverables (using documents with is_deliverable flag)
        Route::get('/{task}/deliverables', [\App\Http\Controllers\Adiutor\TaskController::class, 'getDeliverables'])->name('deliverables.index');
    });
    
    // Adiutor Subtask routes (individual operations)
    Route::prefix('subtasks/{subtask}')->name('subtasks.')->group(function () {
        Route::put('/', [\App\Http\Controllers\Adiutor\SubtaskController::class, 'update'])->name('update');
        Route::delete('/', [\App\Http\Controllers\Adiutor\SubtaskController::class, 'destroy'])->name('destroy');
        Route::post('/toggle', [\App\Http\Controllers\Adiutor\SubtaskController::class, 'toggle'])->name('toggle');
    });
    
    // Adiutor Deliverable routes (individual operations)
    Route::prefix('deliverables/{deliverable}')->name('deliverables.')->group(function () {
        Route::put('/', [\App\Http\Controllers\Adiutor\TaskDeliverableController::class, 'update'])->name('update');
        Route::delete('/', [\App\Http\Controllers\Adiutor\TaskDeliverableController::class, 'destroy'])->name('destroy');
    });
    
    // Time Tracking Routes
    Route::prefix('time-tracking')->name('time-tracking.')->group(function () {
        Route::get('/', [TimeTrackingController::class, 'index'])->name('index');
        Route::get('/status', [TimeTrackingController::class, 'status'])->name('status');
        Route::get('/entries', [TimeTrackingController::class, 'entries'])->name('entries');
        Route::post('/start', [TimeTrackingController::class, 'start'])->name('start');
        Route::post('/stop', [TimeTrackingController::class, 'stop'])->name('stop');
        Route::put('/entries/{timeEntry}', [TimeTrackingController::class, 'update'])->name('entries.update');
        Route::delete('/entries/{timeEntry}', [TimeTrackingController::class, 'delete'])->name('entries.delete');
    });
    
    // Earnings & Payout Routes
    Route::prefix('earnings')->name('earnings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\EarningsController::class, 'index'])->name('index');
        Route::get('/wallet', [\App\Http\Controllers\Adiutor\EarningsController::class, 'wallet'])->name('wallet');
        Route::get('/request-payout', [\App\Http\Controllers\Adiutor\EarningsController::class, 'showRequestForm'])->name('request-form');
        Route::post('/request-payout', [\App\Http\Controllers\Adiutor\EarningsController::class, 'requestPayout'])->name('request');
        Route::get('/payouts', [\App\Http\Controllers\Adiutor\EarningsController::class, 'payouts'])->name('payouts');
        Route::get('/payouts/{id}', [\App\Http\Controllers\Adiutor\EarningsController::class, 'showPayout'])->name('payout.show');
    });
    
    // Hour Increase Requests Routes
    Route::prefix('hour-requests')->name('hour-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\HourIncreaseRequestController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Adiutor\HourIncreaseRequestController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Adiutor\HourIncreaseRequestController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Adiutor\HourIncreaseRequestController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\Adiutor\HourIncreaseRequestController::class, 'cancel'])->name('cancel');
    });
    
    // Budget Change Requests Routes
    Route::prefix('budget-requests')->name('budget-requests.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\BudgetChangeRequestController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Adiutor\BudgetChangeRequestController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Adiutor\BudgetChangeRequestController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Adiutor\BudgetChangeRequestController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [\App\Http\Controllers\Adiutor\BudgetChangeRequestController::class, 'cancel'])->name('cancel');
    });
    
    // Profile management routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [\App\Http\Controllers\Adiutor\ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\Adiutor\ProfileController::class, 'update'])->name('update');
        Route::put('/skills', [\App\Http\Controllers\Adiutor\ProfileController::class, 'updateSkills'])->name('skills.update');
        Route::get('/earnings', [\App\Http\Controllers\Adiutor\ProfileController::class, 'earningsSettings'])->name('earnings');
        Route::put('/earnings', [\App\Http\Controllers\Adiutor\ProfileController::class, 'updateEarningsSettings'])->name('earnings.update');
    });
    
    // Revision Management routes
    Route::prefix('revisions')->name('revisions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Adiutor\RevisionController::class, 'index'])->name('index');
        Route::get('/{revision}', [\App\Http\Controllers\Adiutor\RevisionController::class, 'show'])->name('show');
        Route::get('/{revision}/upload', [\App\Http\Controllers\Adiutor\RevisionController::class, 'uploadForm'])->name('upload');
        Route::post('/{revision}/complete', [\App\Http\Controllers\Adiutor\RevisionController::class, 'complete'])->name('complete');
    });
});

// Calendar Integration Routes (Adiutors only)
Route::middleware(['auth', 'role:adiutor'])->prefix('calendar')->name('calendar.')->group(function () {
    Route::get('/', [\App\Http\Controllers\CalendarController::class, 'index'])->name('index');
    Route::get('/connection', [\App\Http\Controllers\CalendarController::class, 'connection'])->name('connection');
    Route::get('/connect', [\App\Http\Controllers\CalendarController::class, 'connect'])->name('connect');
    Route::get('/callback', [\App\Http\Controllers\CalendarController::class, 'callback'])->name('callback');
    Route::post('/disconnect', [\App\Http\Controllers\CalendarController::class, 'disconnect'])->name('disconnect');
    Route::get('/test', [\App\Http\Controllers\CalendarController::class, 'testConnection'])->name('test');
    Route::post('/sync-deadlines', [\App\Http\Controllers\CalendarController::class, 'syncDeadlineTasks'])->name('sync.deadlines');
    Route::post('/sync-all', [\App\Http\Controllers\CalendarController::class, 'syncAllTasks'])->name('sync.all');
    Route::get('/preview-connected', [\App\Http\Controllers\CalendarController::class, 'previewConnected'])->name('preview');
});