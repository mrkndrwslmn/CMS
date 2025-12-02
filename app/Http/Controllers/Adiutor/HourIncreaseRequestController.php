<?php

namespace App\Http\Controllers\Adiutor;

use App\Http\Controllers\Controller;
use App\Models\HourIncreaseRequest;
use App\Models\ProjectAssignment;
use App\Models\User;
use App\Notifications\HourIncreaseRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HourIncreaseRequestController extends Controller
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
     * Display list of hour increase requests for the adiutor
     */
    public function index()
    {
        $adiutor = Auth::user();

        $requests = HourIncreaseRequest::forAdiutor($adiutor->id)
            ->with(['project', 'projectAssignment', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get assignments approaching max hours (80%+ utilized)
        $warningAssignments = ProjectAssignment::where('adiutor_id', $adiutor->id)
            ->whereNotNull('max_hours')
            ->where('max_hours', '>', 0)
            ->whereRaw('total_hours_logged >= (max_hours * 0.8)')
            ->with('project')
            ->get();

        return view('adiutor.hour-requests.index', compact('requests', 'warningAssignments'));
    }

    /**
     * Show the form for creating a new request
     */
    public function create(Request $request)
    {
        $adiutor = Auth::user();
        $assignmentId = $request->get('assignment_id');

        $assignment = ProjectAssignment::where('id', $assignmentId)
            ->where('adiutor_id', $adiutor->id)
            ->with('project')
            ->firstOrFail();

        // Check if there's already a pending request
        $existingRequest = HourIncreaseRequest::where('project_assignment_id', $assignment->id)
            ->where('adiutor_id', $adiutor->id)
            ->pending()
            ->first();

        if ($existingRequest) {
            return redirect()->route('adiutor.hour-requests.index')
                ->with('error', 'You already have a pending request for this assignment.');
        }

        return view('adiutor.hour-requests.create', compact('assignment'));
    }

    /**
     * Store a newly created request
     */
    public function store(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:project_assignments,id',
            'requested_hours' => 'required|numeric|min:1',
            'reason' => 'required|string|min:20|max:1000',
        ]);

        $adiutor = Auth::user();

        $assignment = ProjectAssignment::where('id', $request->assignment_id)
            ->where('adiutor_id', $adiutor->id)
            ->firstOrFail();

        // Validate requested hours is greater than current
        if ($request->requested_hours <= ($assignment->max_hours ?? 0)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['requested_hours' => 'Requested hours must be greater than current max hours.']);
        }

        // Check for existing pending request
        $existingRequest = HourIncreaseRequest::where('project_assignment_id', $assignment->id)
            ->where('adiutor_id', $adiutor->id)
            ->pending()
            ->first();

        if ($existingRequest) {
            return redirect()->route('adiutor.hour-requests.index')
                ->with('error', 'You already have a pending request for this assignment.');
        }

        DB::beginTransaction();
        try {
            $hourRequest = HourIncreaseRequest::createForAssignment(
                $assignment,
                $request->requested_hours,
                $request->reason
            );

            DB::commit();

            // Notify admins
            $this->notifyAdmins($hourRequest);

            return redirect()->route('adiutor.hour-requests.index')
                ->with('success', 'Hour increase request submitted successfully. You will be notified when it is reviewed.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create hour increase request', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit request. Please try again.']);
        }
    }

    /**
     * Display a specific request
     */
    public function show($id)
    {
        $adiutor = Auth::user();

        $hourRequest = HourIncreaseRequest::where('id', $id)
            ->where('adiutor_id', $adiutor->id)
            ->with(['project', 'projectAssignment', 'reviewer'])
            ->firstOrFail();

        return view('adiutor.hour-requests.show', compact('hourRequest'));
    }

    /**
     * Cancel a pending request
     */
    public function cancel($id)
    {
        $adiutor = Auth::user();

        $hourRequest = HourIncreaseRequest::where('id', $id)
            ->where('adiutor_id', $adiutor->id)
            ->pending()
            ->firstOrFail();

        $hourRequest->delete();

        return redirect()->route('adiutor.hour-requests.index')
            ->with('success', 'Request cancelled successfully.');
    }

    /**
     * Notify admins about the new request
     */
    protected function notifyAdmins(HourIncreaseRequest $request)
    {
        $admins = User::where('role', 'admin')->where('status', 'active')->get();

        foreach ($admins as $admin) {
            try {
                // You can create a notification class for this
                // $admin->notify(new HourIncreaseRequestNotification($request));
                
                // For now, just log it
                \Log::info('Hour increase request notification would be sent to admin', [
                    'admin_id' => $admin->id,
                    'request_id' => $request->id,
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to notify admin about hour increase request', [
                    'admin_id' => $admin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
