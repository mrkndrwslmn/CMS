<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkflowController extends Controller
{
    /**
     * Approve a service request and set payment details
     */
    public function approveRequest(Request $request, $requestId)
    {
        $request->validate([
            'approved_budget' => 'required|numeric|min:0',
            'payment_method' => 'required|in:bank_transfer,credit_card,paypal,cash',
            'payment_due_days' => 'required|integer|min:1|max:30',
            'payment_instructions' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $serviceRequest = DB::table('service_requests')
            ->where('id', $requestId)
            ->where('status', 'pending')
            ->first();

        if (!$serviceRequest) {
            return redirect()->back()->with('error', 'Service request not found or already processed.');
        }

        // Update service request to approved with payment details
        DB::table('service_requests')
            ->where('id', $requestId)
            ->update([
                'status' => 'pending_payment',
                'approved_budget' => $request->approved_budget,
                'payment_method' => $request->payment_method,
                'payment_due_date' => Carbon::now()->addDays($request->payment_due_days),
                'payment_instructions' => $request->payment_instructions,
                'admin_notes' => $request->admin_notes,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'reviewed_at' => now(),
                'updated_at' => now()
            ]);

        // Send email notification to client about approval and payment
        // TODO: Implement email sending

        return redirect()->back()->with('success', 'Service request approved. Client will be notified about payment details.');
    }

    /**
     * Reject a service request
     */
    public function rejectRequest(Request $request, $requestId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $serviceRequest = DB::table('service_requests')
            ->where('id', $requestId)
            ->where('status', 'pending')
            ->first();

        if (!$serviceRequest) {
            return redirect()->back()->with('error', 'Service request not found or already processed.');
        }

        // Update service request to rejected
        DB::table('service_requests')
            ->where('id', $requestId)
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'reviewed_at' => now(),
                'updated_at' => now()
            ]);

        // Send email notification to client about rejection
        // TODO: Implement email sending

        return redirect()->back()->with('success', 'Service request rejected. Client will be notified.');
    }

    /**
     * Confirm payment and convert service request to project
     */
    public function confirmPayment(Request $request, $requestId)
    {
        $request->validate([
            'payment_reference' => 'required|string|max:255',
            'payment_confirmed_notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();

        try {
            $serviceRequest = DB::table('service_requests')
                ->where('id', $requestId)
                ->where('status', 'pending_payment')
                ->first();

            if (!$serviceRequest) {
                return redirect()->back()->with('error', 'Service request not found or not pending payment.');
            }

            // 1. Update service request to paid status
            DB::table('service_requests')
                ->where('id', $requestId)
                ->update([
                    'status' => 'paid',
                    'payment_reference' => $request->payment_reference,
                    'payment_confirmed_at' => now(),
                    'updated_at' => now()
                ]);

            // 2. Create corresponding project
            $projectId = DB::table('projects')->insertGetId([
                'service_request_id' => $requestId,
                'client_id' => $serviceRequest->client_id,
                'title' => $serviceRequest->project_name,
                'description' => $serviceRequest->request_description,
                'budget' => $serviceRequest->estimated_budget,
                'approved_budget' => $serviceRequest->approved_budget,
                'deadline' => $serviceRequest->deadline,
                'priority' => $serviceRequest->priority,
                'status' => 'open', // Ready for task assignment
                'project_started_at' => now(),
                'project_notes' => $request->payment_confirmed_notes,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 3. Update service request with completed status (project created)
            DB::table('service_requests')
                ->where('id', $requestId)
                ->update([
                    'status' => 'completed', // Request is complete, now it's a project
                    'updated_at' => now()
                ]);

            DB::commit();

            // Send email notification to client about payment confirmation and project start
            // TODO: Implement email sending

            return redirect()->back()->with('success', "Payment confirmed! Project #{$projectId} has been created and is ready for task assignment.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to confirm payment: ' . $e->getMessage());
        }
    }

    /**
     * Create a task for a project
     */
    public function createTask(Request $request, $projectId)
    {
        $request->validate([
            'task_title' => 'required|string|max:255',
            'task_description' => 'required|string|max:2000',
            'assigned_to' => 'required|exists:users,id',
            'allocated_budget' => 'required|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
            'priority' => 'required|in:low,medium,high'
        ]);

        $project = DB::table('projects')->where('id', $projectId)->first();
        
        if (!$project) {
            return redirect()->back()->with('error', 'Project not found.');
        }

        // Check if adding this task budget would exceed project budget
        $currentAllocatedBudget = DB::table('tasks')
            ->where('project_id', $projectId)
            ->sum('allocated_budget') ?? 0;

        $totalAfterNewTask = $currentAllocatedBudget + $request->allocated_budget;

        if ($totalAfterNewTask > $project->approved_budget) {
            $remaining = $project->approved_budget - $currentAllocatedBudget;
            return redirect()->back()->with('error', "Budget exceeded! Remaining budget: $" . number_format($remaining, 2));
        }

        // Create the task
        $taskId = DB::table('tasks')->insertGetId([
            'project_id' => $projectId,
            'assignedTo' => $request->assigned_to,
            'createdBy' => Auth::id(),
            'taskTitle' => $request->task_title,
            'taskDescription' => $request->task_description,
            'allocated_budget' => $request->allocated_budget,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'status' => 'pending',
            'progress_percentage' => 0,
            'dateAssigned' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Send email notification to assigned adiutor
        // TODO: Implement email sending

        return redirect()->back()->with('success', "Task #{$taskId} created and assigned successfully.");
    }

    /**
     * Get project budget overview
     */
    public function getProjectBudgetOverview($projectId)
    {
        $project = DB::table('projects')->where('id', $projectId)->first();
        
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $tasks = DB::table('tasks')
            ->leftJoin('users', 'tasks.assignedTo', '=', 'users.id')
            ->where('tasks.project_id', $projectId)
            ->select(
                'tasks.*',
                'users.fullName as assigned_to_name'
            )
            ->get();

        $totalAllocated = $tasks->sum('allocated_budget') ?? 0;
        $totalSpent = $tasks->sum('actual_cost') ?? 0;
        $remainingBudget = $project->approved_budget - $totalAllocated;

        return response()->json([
            'project' => $project,
            'tasks' => $tasks,
            'budget_summary' => [
                'approved_budget' => $project->approved_budget,
                'total_allocated' => $totalAllocated,
                'total_spent' => $totalSpent,
                'remaining_budget' => $remainingBudget,
                'is_over_budget' => $remainingBudget < 0,
                'budget_utilization_percentage' => $project->approved_budget > 0 ? round(($totalAllocated / $project->approved_budget) * 100, 2) : 0
            ]
        ]);
    }
}