<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetChangeRequest;
use App\Models\Task;
use App\Models\User;
use App\Notifications\BudgetChangeReviewedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetChangeRequestController extends Controller
{
    /**
     * Display a listing of budget change requests
     */
    public function index(Request $request)
    {
        $query = BudgetChangeRequest::with(['task.project', 'adiutor', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by adiutor
        if ($request->filled('adiutor_id')) {
            $query->where('adiutor_id', $request->adiutor_id);
        }

        // Search by task title or adiutor name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('task', function ($q) use ($search) {
                $q->where('taskTitle', 'like', "%{$search}%");
            })->orWhereHas('adiutor', function ($q) use ($search) {
                $q->where('fullName', 'like', "%{$search}%");
            });
        }

        $budgetRequests = $query->paginate(15);

        // Get statistics
        $stats = [
            'pending' => BudgetChangeRequest::where('status', 'pending')->count(),
            'approved' => BudgetChangeRequest::where('status', 'approved')->count(),
            'rejected' => BudgetChangeRequest::where('status', 'rejected')->count(),
            'total' => BudgetChangeRequest::count(),
        ];

        return view('admin.budget-requests.index', compact('budgetRequests', 'stats'));
    }

    /**
     * Display the specified budget change request
     */
    public function show($id)
    {
        $budgetRequest = BudgetChangeRequest::with(['task.project', 'adiutor', 'reviewer'])
            ->findOrFail($id);

        return view('admin.budget-requests.show', compact('budgetRequest'));
    }

    /**
     * Approve the budget change request
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'review_notes' => 'nullable|string|max:500',
        ]);

        $budgetRequest = BudgetChangeRequest::findOrFail($id);

        // Check if already reviewed
        if ($budgetRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This budget change request has already been reviewed.');
        }

        DB::beginTransaction();
        try {
            // Update the budget change request
            $budgetRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes,
            ]);

            // Update the task budget
            $task = Task::find($budgetRequest->task_id);
            if ($task) {
                $task->allocated_budget = $budgetRequest->requested_budget;
                $task->save();
            }

            // Create notification for adiutor
            $adiutor = User::find($budgetRequest->adiutor_id);
            $adiutor->notify(new BudgetChangeReviewedNotification(
                $task,
                'approved',
                $budgetRequest->requested_budget
            ));

            DB::commit();

            return redirect()->route('admin.budget-requests.index')
                ->with('success', 'Budget change request approved successfully. Task budget has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to approve budget change request: ' . $e->getMessage());
        }
    }

    /**
     * Reject the budget change request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'review_notes' => 'required|string|max:500',
        ]);

        $budgetRequest = BudgetChangeRequest::findOrFail($id);

        // Check if already reviewed
        if ($budgetRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This budget change request has already been reviewed.');
        }

        DB::beginTransaction();
        try {
            // Update the budget change request
            $budgetRequest->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes,
            ]);

            // Create notification for adiutor
            $task = Task::find($budgetRequest->task_id);
            $adiutor = User::find($budgetRequest->adiutor_id);
            $adiutor->notify(new BudgetChangeReviewedNotification(
                $task,
                'rejected',
                null,
                $request->review_notes
            ));

            DB::commit();

            return redirect()->route('admin.budget-requests.index')
                ->with('success', 'Budget change request rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reject budget change request: ' . $e->getMessage());
        }
    }

    /**
     * Delete the budget change request (admin only)
     */
    public function destroy($id)
    {
        $budgetRequest = BudgetChangeRequest::findOrFail($id);

        // Only allow deleting old approved/rejected requests
        if ($budgetRequest->status === 'pending') {
            return redirect()->back()
                ->with('error', 'Cannot delete a pending budget change request. Please approve or reject it first.');
        }

        $budgetRequest->delete();

        return redirect()->route('admin.budget-requests.index')
            ->with('success', 'Budget change request deleted successfully.');
    }
}
