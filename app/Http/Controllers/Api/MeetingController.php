<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Project;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MeetingController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    /**
     * Get all meetings for a project
     */
    public function index(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        
        // Ensure user has access to this project
        if (Auth::user()->isClient() && $project->client_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $meetings = Meeting::forProject($projectId)
            ->with(['client', 'admin'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'meetings' => $meetings,
        ]);
    }

    /**
     * Create a new meeting request (Client)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'requested_date' => 'required|date|after:today',
            'requested_time' => 'required|date_format:H:i',
        ]);

        // Ensure user is the project client
        $project = Project::findOrFail($validated['project_id']);
        
        if (Auth::user()->isClient() && $project->client_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $meeting = Meeting::create([
            'project_id' => $validated['project_id'],
            'client_id' => $project->client_id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'requested_date' => $validated['requested_date'],
            'requested_time' => $validated['requested_time'],
            'status' => Meeting::STATUS_PENDING,
        ]);

        // TODO: Send notification to admin

        return response()->json([
            'success' => true,
            'message' => 'Meeting request submitted successfully',
            'meeting' => $meeting->load(['client', 'project']),
        ], 201);
    }

    /**
     * Approve a meeting (Admin)
     */
    public function approve(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $meeting = Meeting::findOrFail($id);

        if (!$meeting->isPending()) {
            return response()->json(['error' => 'Meeting is not pending'], 400);
        }

        // Set scheduled date/time to the requested date/time
        $meeting->scheduled_date = $meeting->requested_date;
        $meeting->scheduled_time = $meeting->requested_time;
        $meeting->admin_id = Auth::id();
        $meeting->status = Meeting::STATUS_APPROVED;

        // Create Zoom meeting
        $scheduledDateTime = Carbon::parse($meeting->scheduled_date->format('Y-m-d') . ' ' . $meeting->scheduled_time);
        
        $zoomMeeting = $this->zoomService->createMeeting(
            $meeting->title,
            $scheduledDateTime,
            60, // 1 hour duration
            $meeting->description
        );

        if ($zoomMeeting) {
            $meeting->zoom_meeting_id = $zoomMeeting['meeting_id'];
            $meeting->zoom_join_url = $zoomMeeting['join_url'];
            $meeting->zoom_start_url = $zoomMeeting['start_url'];
            $meeting->zoom_password = $zoomMeeting['password'];
        } else {
            Log::error('Failed to create Zoom meeting for meeting ID: ' . $meeting->id);
            return response()->json([
                'error' => 'Failed to create Zoom meeting. Please try again.',
            ], 500);
        }

        $meeting->save();

        // TODO: Send notification to client

        return response()->json([
            'success' => true,
            'message' => 'Meeting approved and Zoom link generated',
            'meeting' => $meeting->load(['client', 'admin', 'project']),
        ]);
    }

    /**
     * Reschedule a meeting (Admin)
     */
    public function reschedule(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'rescheduled_date' => 'required|date|after:today',
            'rescheduled_time' => 'required|date_format:H:i',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $meeting = Meeting::findOrFail($id);

        if (!$meeting->isPending() && !$meeting->isRescheduled()) {
            return response()->json(['error' => 'Cannot reschedule this meeting'], 400);
        }

        $meeting->update([
            'rescheduled_date' => $validated['rescheduled_date'],
            'rescheduled_time' => $validated['rescheduled_time'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'admin_id' => Auth::id(),
            'status' => Meeting::STATUS_RESCHEDULED,
        ]);

        // TODO: Send notification to client

        return response()->json([
            'success' => true,
            'message' => 'Meeting rescheduled. Waiting for client approval.',
            'meeting' => $meeting->load(['client', 'admin', 'project']),
        ]);
    }

    /**
     * Client approves rescheduled meeting
     */
    public function approveReschedule(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        // Ensure user is the client
        if ($meeting->client_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$meeting->isRescheduled()) {
            return response()->json(['error' => 'Meeting is not rescheduled'], 400);
        }

        // Set scheduled date/time to the rescheduled date/time
        $meeting->scheduled_date = $meeting->rescheduled_date;
        $meeting->scheduled_time = $meeting->rescheduled_time;
        $meeting->status = Meeting::STATUS_APPROVED;

        // Create Zoom meeting
        $scheduledDateTime = Carbon::parse($meeting->scheduled_date->format('Y-m-d') . ' ' . $meeting->scheduled_time);
        
        $zoomMeeting = $this->zoomService->createMeeting(
            $meeting->title,
            $scheduledDateTime,
            60,
            $meeting->description
        );

        if ($zoomMeeting) {
            $meeting->zoom_meeting_id = $zoomMeeting['meeting_id'];
            $meeting->zoom_join_url = $zoomMeeting['join_url'];
            $meeting->zoom_start_url = $zoomMeeting['start_url'];
            $meeting->zoom_password = $zoomMeeting['password'];
        } else {
            return response()->json([
                'error' => 'Failed to create Zoom meeting. Please try again.',
            ], 500);
        }

        $meeting->save();

        // TODO: Send notification to admin

        return response()->json([
            'success' => true,
            'message' => 'Meeting approved and Zoom link generated',
            'meeting' => $meeting->load(['client', 'admin', 'project']),
        ]);
    }

    /**
     * Reject a meeting (Admin)
     */
    public function reject(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $meeting = Meeting::findOrFail($id);

        if (!$meeting->isPending()) {
            return response()->json(['error' => 'Meeting is not pending'], 400);
        }

        $meeting->update([
            'admin_notes' => $validated['admin_notes'],
            'admin_id' => Auth::id(),
            'status' => Meeting::STATUS_REJECTED,
        ]);

        // TODO: Send notification to client

        return response()->json([
            'success' => true,
            'message' => 'Meeting rejected',
            'meeting' => $meeting->load(['client', 'admin', 'project']),
        ]);
    }

    /**
     * Cancel a meeting
     */
    public function destroy($id)
    {
        $meeting = Meeting::findOrFail($id);

        // Check authorization
        if (Auth::user()->isClient() && $meeting->client_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!Auth::user()->isAdmin() && !Auth::user()->isClient()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete Zoom meeting if it exists
        if ($meeting->zoom_meeting_id) {
            $this->zoomService->deleteMeeting($meeting->zoom_meeting_id);
        }

        $meeting->update(['status' => Meeting::STATUS_CANCELLED]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting cancelled successfully',
        ]);
    }
}
