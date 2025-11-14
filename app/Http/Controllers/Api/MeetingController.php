<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MeetingApproved;
use App\Mail\MeetingRejected;
use App\Mail\MeetingRequested;
use App\Mail\MeetingRescheduled;
use App\Mail\MeetingRescheduleRejected;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        // Send notification to admin
        try {
            $admins = User::where('role', 'admin')->where('isActive', true)->get();
            
            foreach ($admins as $admin) {
                Mail::to($admin->email)->queue(new MeetingRequested($meeting->load(['client', 'project'])));
            }
            
            Log::info('Meeting requested notifications sent to admins', [
                'meeting_id' => $meeting->id,
                'admin_count' => $admins->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send meeting requested notification', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the request if notification fails
        }

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

        // Send notification to client
        try {
            $client = $meeting->client;
            
            Mail::to($client->email)->queue(new MeetingApproved($meeting->load(['client', 'admin', 'project'])));
            
            Log::info('Meeting approved notification sent to client', [
                'meeting_id' => $meeting->id,
                'client_id' => $client->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send meeting approved notification', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the request if notification fails
        }

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

        // Send notification to client
        try {
            $client = $meeting->client;
            
            Mail::to($client->email)->queue(new MeetingRescheduled($meeting->load(['client', 'admin', 'project'])));
            
            Log::info('Meeting rescheduled notification sent to client', [
                'meeting_id' => $meeting->id,
                'client_id' => $client->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send meeting rescheduled notification', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the request if notification fails
        }

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

        // Send notification to admin (confirming client approved the reschedule)
        try {
            $admin = $meeting->admin;
            
            if ($admin) {
                Mail::to($admin->email)->queue(new MeetingApproved($meeting->load(['client', 'admin', 'project'])));
                
                Log::info('Meeting reschedule approval notification sent to admin', [
                    'meeting_id' => $meeting->id,
                    'admin_id' => $admin->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send meeting reschedule approval notification to admin', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the request if notification fails
        }

        return response()->json([
            'success' => true,
            'message' => 'Meeting approved and Zoom link generated',
            'meeting' => $meeting->load(['client', 'admin', 'project']),
        ]);
    }

    /**
     * Client rejects rescheduled meeting
     */
    public function rejectReschedule(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        // Ensure user is the client
        if ($meeting->client_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$meeting->isRescheduled()) {
            return response()->json(['error' => 'Meeting is not rescheduled'], 400);
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        // Reset to pending status and clear rescheduled dates
        $meeting->status = Meeting::STATUS_PENDING;
        $meeting->rescheduled_date = null;
        $meeting->rescheduled_time = null;
        
        // Add client's rejection reason to admin notes
        $rejectionNote = 'Client rejected rescheduled time.';
        if (!empty($validated['reason'])) {
            $rejectionNote .= ' Reason: ' . $validated['reason'];
        }
        
        if ($meeting->admin_notes) {
            $meeting->admin_notes .= "\n\n" . $rejectionNote;
        } else {
            $meeting->admin_notes = $rejectionNote;
        }

        $meeting->save();

        // Send notification to admin
        try {
            $admin = $meeting->admin;
            
            if ($admin) {
                Mail::to($admin->email)->queue(new MeetingRescheduleRejected(
                    $meeting->load(['client', 'admin', 'project']),
                    $validated['reason'] ?? 'No reason provided'
                ));
                
                Log::info('Meeting reschedule rejection notification sent to admin', [
                    'meeting_id' => $meeting->id,
                    'admin_id' => $admin->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send meeting reschedule rejection notification', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the request if notification fails
        }

        return response()->json([
            'success' => true,
            'message' => 'Meeting time declined. Admin will propose a new time.',
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

        // Send notification to client
        try {
            $client = $meeting->client;
            
            Mail::to($client->email)->queue(new MeetingRejected($meeting->load(['client', 'admin', 'project'])));
            
            Log::info('Meeting rejected notification sent to client', [
                'meeting_id' => $meeting->id,
                'client_id' => $client->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send meeting rejected notification', [
                'meeting_id' => $meeting->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the request if notification fails
        }

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
