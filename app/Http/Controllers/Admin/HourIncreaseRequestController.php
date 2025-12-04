<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HourIncreaseRequest;
use App\Models\User;
use App\Notifications\HourIncreaseReviewedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HourIncreaseRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized access. Admins only.');
            }
            return $next($request);
        });
    }

    /**
     * Display list of all hour increase requests
     */
    public function index(Request $request)
    {
        $query = HourIncreaseRequest::with(['adiutor', 'project', 'projectAssignment', 'reviewer']);

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by adiutor
        if ($request->has('adiutor_id') && $request->adiutor_id) {
            $query->where('adiutor_id', $request->adiutor_id);
        }

        // Filter by project
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get stats
        $stats = [
            'pending' => HourIncreaseRequest::pending()->count(),
            'approved_this_month' => HourIncreaseRequest::where('status', 'approved')
                ->whereMonth('reviewed_at', now()->month)
                ->count(),
            'rejected_this_month' => HourIncreaseRequest::where('status', 'rejected')
                ->whereMonth('reviewed_at', now()->month)
                ->count(),
        ];

        // Get adiutors for filter
        $adiutors = User::where('role', 'adiutor')
            ->orderBy('fullName')
            ->get(['id', 'fullName']);

        return view('admin.hour-requests.index', compact('requests', 'stats', 'status', 'adiutors'));
    }

    /**
     * Display a specific request for review
     */
    public function show($id)
    {
        $hourRequest = HourIncreaseRequest::with([
            'adiutor.adiutorProfile',
            'project',
            'projectAssignment',
            'reviewer'
        ])->findOrFail($id);

        // Get adiutor's recent time entries for this project
        $recentTimeEntries = \App\Models\TimeEntry::where('adiutor_id', $hourRequest->adiutor_id)
            ->where('project_id', $hourRequest->project_id)
            ->whereNotNull('end_time')
            ->orderBy('start_time', 'desc')
            ->limit(10)
            ->get();

        return view('admin.hour-requests.show', compact('hourRequest', 'recentTimeEntries'));
    }

    /**
     * Approve the request
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approved_hours' => 'required|numeric|min:1',
            'review_notes' => 'nullable|string|max:500',
        ]);

        $hourRequest = HourIncreaseRequest::pending()->findOrFail($id);

        DB::beginTransaction();
        try {
            $hourRequest->approve(
                Auth::id(),
                $request->approved_hours,
                $request->review_notes
            );

            DB::commit();

            // Notify adiutor about approval
            $this->notifyAdiutor($hourRequest, 'approved');

            return redirect()->route('admin.hour-requests.index')
                ->with('success', "Hour increase request approved. New max hours: {$request->approved_hours}");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to approve hour increase request', [
                'request_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to approve request. Please try again.']);
        }
    }

    /**
     * Reject the request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'review_notes' => 'required|string|min:10|max:500',
        ]);

        $hourRequest = HourIncreaseRequest::pending()->findOrFail($id);

        DB::beginTransaction();
        try {
            $hourRequest->reject(
                Auth::id(),
                $request->review_notes
            );

            DB::commit();

            // Notify adiutor about rejection
            $this->notifyAdiutor($hourRequest, 'rejected');

            return redirect()->route('admin.hour-requests.index')
                ->with('success', 'Hour increase request rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to reject hour increase request', [
                'request_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to reject request. Please try again.']);
        }
    }

    /**
     * Quick approve from list view (AJAX)
     */
    public function quickApprove(Request $request, $id)
    {
        $hourRequest = HourIncreaseRequest::pending()->findOrFail($id);

        DB::beginTransaction();
        try {
            // Approve with requested hours
            $hourRequest->approve(
                Auth::id(),
                $hourRequest->requested_max_hours,
                'Quick approved'
            );

            DB::commit();

            // Notify adiutor about approval
            $this->notifyAdiutor($hourRequest, 'approved');

            return response()->json([
                'success' => true,
                'message' => 'Request approved successfully.',
                'approved_hours' => $hourRequest->requested_max_hours,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve request.',
            ], 500);
        }
    }

    /**
     * Notify the adiutor about the review decision
     *
     * @param HourIncreaseRequest $hourRequest
     * @param string $status 'approved' or 'rejected'
     */
    protected function notifyAdiutor(HourIncreaseRequest $hourRequest, string $status): void
    {
        try {
            // Reload the model to ensure we have fresh data with relationships
            $hourRequest->load('adiutor', 'project');

            if ($hourRequest->adiutor) {
                $hourRequest->adiutor->notify(new HourIncreaseReviewedNotification($hourRequest, $status));
                
                \Log::info('Hour increase review notification sent to adiutor', [
                    'adiutor_id' => $hourRequest->adiutor_id,
                    'request_id' => $hourRequest->id,
                    'status' => $status,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to notify adiutor about hour increase review', [
                'adiutor_id' => $hourRequest->adiutor_id,
                'request_id' => $hourRequest->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
