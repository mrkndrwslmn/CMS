<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display the admin calendar view
     */
    public function index(Request $request)
    {
        // Get all active projects with assigned adiutors
        $projects = Project::whereIn('status', ['active', 'in_progress'])
            ->with(['client', 'assignments' => function($query) {
                $query->whereIn('status', ['active', 'in_progress'])
                      ->with(['adiutor.adiutorProfile', 'adiutor.calendarIntegration']);
            }, 'tasks.assignedUser.calendarIntegration'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($project) {
                // Get adiutors from project assignments
                $adiutorsFromAssignments = $project->assignments->map(function($assignment) {
                    return [
                        'id' => $assignment->adiutor->id,
                        'name' => $assignment->adiutor->fullName,
                        'profilePic' => $assignment->adiutor->profilePic,
                        'avatar' => $assignment->adiutor->profilePic ? $assignment->adiutor->getProfilePictureUrl() : null,
                        'calendar_connected' => $assignment->adiutor->calendarIntegration && $assignment->adiutor->calendarIntegration->is_connected,
                    ];
                });

                // Get adiutors from task assignments
                $adiutorsFromTasks = collect($project->tasks ?? [])
                    ->filter(function($task) {
                        return $task->assignedUser && $task->assignedUser->role === 'adiutor';
                    })
                    ->map(function($task) {
                        return [
                            'id' => $task->assignedUser->id,
                            'name' => $task->assignedUser->fullName,
                            'profilePic' => $task->assignedUser->profilePic,
                            'avatar' => $task->assignedUser->profilePic ? $task->assignedUser->getProfilePictureUrl() : null,
                            'calendar_connected' => $task->assignedUser->calendarIntegration && $task->assignedUser->calendarIntegration->is_connected,
                        ];
                    });

                // Merge and get unique adiutors by id
                $allAdiutors = $adiutorsFromAssignments->concat($adiutorsFromTasks)
                    ->unique(function($adiutor) {
                        return $adiutor['id'];
                    })
                    ->values();

                return [
                    'id' => $project->id,
                    'project_name' => $project->title,
                    'client_name' => $project->client ? $project->client->fullName : 'N/A',
                    'status' => $project->status,
                    'start_date' => $project->started_at,
                    'deadline' => $project->deadline,
                    'adiutors' => $allAdiutors,
                ];
            });

        return view('admin.calendar.index', compact('projects'));
    }
}
