<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\PayoutItem;
use App\Models\TimeEntry;
use App\Models\User;
use App\Mail\PayoutApprovedMail;
use App\Mail\PayoutRejectedMail;
use App\Mail\PayoutPaidMail;
use App\Notifications\PayoutApprovedNotification;
use App\Notifications\PayoutRejectedNotification;
use App\Notifications\PayoutPaidNotification;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PayoutManagementController extends Controller
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
     * Display payouts list
     */
    public function index(Request $request)
    {
        $query = Payout::with(['adiutor.adiutorProfile', 'processedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by adiutor
        if ($request->has('adiutor') && $request->adiutor) {
            $query->where('adiutor_id', $request->adiutor);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payouts = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get all adiutors for filter
        $adiutors = User::where('role', 'adiutor')
            ->orderBy('fullName')
            ->get(['id', 'fullName']);

        // Get statistics
        $stats = [
            'pending' => Payout::where('status', 'pending')->count(),
            'processing' => Payout::where('status', 'processing')->count(),
            'completed' => Payout::where('status', 'completed')->whereMonth('completed_at', Carbon::now()->month)->count(),
            'total_this_month' => Payout::where('status', 'completed')
                ->whereMonth('completed_at', Carbon::now()->month)
                ->sum('amount'),
        ];

        return view('admin.payouts.index', compact('payouts', 'adiutors', 'stats'));
    }

    /**
     * Show payout details
     */
    public function show($id)
    {
        $payout = Payout::with([
            'adiutor.adiutorProfile',
            'items.task.project',
            'items.timeEntry',
            'processedBy',
            'timeEntries'
        ])->findOrFail($id);

        return view('admin.payouts.show', compact('payout'));
    }

    /**
     * Mark payout as processing
     */
    public function markAsProcessing($id)
    {
        $payout = Payout::findOrFail($id);

        if ($payout->status !== 'pending') {
            return redirect()->back()->withErrors(['error' => 'Only pending payouts can be marked as processing.']);
        }

        $payout->markAsProcessing(auth()->id());

        return redirect()->back()->with('success', 'Payout marked as processing.');
    }

    /**
     * Complete payout
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'reference_number' => 'required|string|max:255',
            'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payout = Payout::findOrFail($id);

        if (!in_array($payout->status, ['pending', 'processing'])) {
            return redirect()->back()->withErrors(['error' => 'Only pending or processing payouts can be completed.']);
        }

        DB::beginTransaction();
        try {
            // Handle proof of payment upload
            $proofPath = null;
            if ($request->hasFile('proof_of_payment')) {
                $file = $request->file('proof_of_payment');
                $filename = 'payout_' . $payout->payout_number . '_proof.' . $file->getClientOriginalExtension();
                $proofPath = $file->storeAs('payouts/proofs', $filename, 'public');
            }

            // Update payout
            $payout->update([
                'status' => 'completed',
                'reference_number' => $request->reference_number,
                'proof_of_payment' => $proofPath,
                'notes' => $request->notes ? ($payout->notes ? $payout->notes . "\n\n" . $request->notes : $request->notes) : $payout->notes,
                'completed_at' => now(),
                'processed_by' => auth()->id(),
            ]);

            // Mark all time entries as paid
            $payout->timeEntries()->update(['is_paid' => true]);

            DB::commit();

            // Send notifications to adiutor
            $adiutor = $payout->adiutor;
            $processedBy = auth()->user();
            $firebaseService = app(FirebaseService::class);
            
            try {
                // Send email notification
                Mail::to($adiutor->email)->send(new PayoutPaidMail($payout, $adiutor, $processedBy));
                
                // Send in-app notification
                $adiutor->notify(new PayoutPaidNotification($payout, $processedBy));
                
                // Send Firebase push notification
                if ($adiutor->fcm_token) {
                    $notificationData = (new PayoutPaidNotification($payout, $processedBy))->toFirebase($adiutor);
                    $firebaseService->sendToUser($adiutor, $notificationData['data'], $notificationData['notification']);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send payout paid notification: ' . $e->getMessage());
            }

            return redirect()->route('admin.payouts.show', $payout->id)
                ->with('success', 'Payout completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to complete payout: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancel payout
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $payout = Payout::findOrFail($id);

        if ($payout->status === 'completed') {
            return redirect()->back()->withErrors(['error' => 'Completed payouts cannot be cancelled.']);
        }

        DB::beginTransaction();
        try {
            $payout->cancel($request->reason);

            DB::commit();

            // Send notifications to adiutor
            $adiutor = $payout->adiutor;
            $rejectedBy = auth()->user();
            $firebaseService = app(FirebaseService::class);
            
            try {
                // Send email notification
                Mail::to($adiutor->email)->send(new PayoutRejectedMail($payout, $adiutor, $rejectedBy));
                
                // Send in-app notification
                $adiutor->notify(new PayoutRejectedNotification($payout, $rejectedBy));
                
                // Send Firebase push notification
                if ($adiutor->fcm_token) {
                    $notificationData = (new PayoutRejectedNotification($payout, $rejectedBy))->toFirebase($adiutor);
                    $firebaseService->sendToUser($adiutor, $notificationData['data'], $notificationData['notification']);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send payout rejected notification: ' . $e->getMessage());
            }

            return redirect()->route('admin.payouts.show', $payout->id)
                ->with('success', 'Payout cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to cancel payout: ' . $e->getMessage()]);
        }
    }

    /**
     * Show adiutor earnings summary
     */
    public function adiutorEarnings($adiutorId)
    {
        $adiutor = User::where('role', 'adiutor')
            ->with('adiutorProfile')
            ->findOrFail($adiutorId);

        // Get all time entries
        $timeEntries = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->with(['task.project', 'payout'])
            ->orderBy('start_time', 'desc')
            ->paginate(50);

        // Calculate earnings
        $totalEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->sum('calculated_amount');

        $approvedUnpaid = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', true)
            ->where('is_paid', false)
            ->sum('calculated_amount');

        $paidEarnings = TimeEntry::where('adiutor_id', $adiutorId)
            ->where('is_paid', true)
            ->sum('calculated_amount');

        $pendingApproval = TimeEntry::where('adiutor_id', $adiutorId)
            ->whereNotNull('end_time')
            ->where('is_approved', false)
            ->sum('calculated_amount');

        // Get payouts
        $payouts = Payout::where('adiutor_id', $adiutorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.payouts.adiutor-earnings', compact(
            'adiutor',
            'timeEntries',
            'totalEarnings',
            'approvedUnpaid',
            'paidEarnings',
            'pendingApproval',
            'payouts'
        ));
    }

    /**
     * Approve time entries in bulk
     */
    public function approveTimeEntries(Request $request)
    {
        $request->validate([
            'time_entry_ids' => 'required|array',
            'time_entry_ids.*' => 'exists:time_entries,id',
        ]);

        $count = TimeEntry::whereIn('id', $request->time_entry_ids)
            ->where('is_approved', false)
            ->update([
                'is_approved' => true,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        // Update task earnings for affected tasks
        $taskIds = TimeEntry::whereIn('id', $request->time_entry_ids)->pluck('task_id')->unique();
        foreach ($taskIds as $taskId) {
            $task = \App\Models\Task::find($taskId);
            $task?->updateEarnings();
        }

        return redirect()->back()->with('success', "$count time entries approved successfully.");
    }

    /**
     * Export payouts report
     */
    public function export(Request $request)
    {
        $query = Payout::with(['adiutor.adiutorProfile', 'items']);

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payouts = $query->get();

        // Generate CSV
        $filename = 'payouts_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($payouts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Payout Number', 'Adiutor', 'Amount', 'Status', 'Method', 'Period Start', 'Period End', 'Requested At', 'Completed At', 'Reference']);

            foreach ($payouts as $payout) {
                fputcsv($file, [
                    $payout->payout_number,
                    $payout->adiutor->fullName,
                    $payout->amount,
                    $payout->status,
                    $payout->payout_method,
                    $payout->period_start?->format('Y-m-d'),
                    $payout->period_end?->format('Y-m-d'),
                    $payout->requested_at?->format('Y-m-d H:i'),
                    $payout->completed_at?->format('Y-m-d H:i'),
                    $payout->reference_number,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
