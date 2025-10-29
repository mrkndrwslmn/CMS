<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\RequestAttachment;
use App\Models\Project;
use App\Mail\RequestApproved;
use App\Mail\PaymentConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RequestManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['client', 'attachments', 'project']);
        
        // Search functionality for ServiceRequests
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                  ->orWhere('request_description', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('fullName', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Service type filter
        if ($request->filled('type')) {
            $query->where('service_type', $request->type);
        }
        
        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        // Client filter
        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sort by created_at descending
        $requests = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get filter options
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        
        // Get statistics
        $stats = [
            'total_requests' => ServiceRequest::count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count(),
            'approved_requests' => ServiceRequest::where('status', 'approved')->count(),
            'rejected_requests' => ServiceRequest::where('status', 'rejected')->count(),
            'paid_requests' => ServiceRequest::where('status', 'paid')->count(),
        ];
        
        return view('admin.requests.index', compact('requests', 'clients', 'stats'));
    }
    
    public function show($id)
    {
        $serviceRequest = ServiceRequest::with(['client', 'attachments', 'project.tasks', 'payments'])
                                       ->findOrFail($id);
        
        return view('admin.requests.show', ['request' => $serviceRequest]);
    }
    
    public function approve(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $request->validate([
            'admin_notes' => 'nullable|string',
            'approved_budget' => 'required|numeric|min:0',
            'payment_due_date' => 'required|date|after:today',
            'payment_instructions' => 'nullable|string',
            // Payment type fields
            'payment_type' => 'required|in:full_payment,milestone_payment,downpayment',
            'milestone_phases' => 'nullable|required_if:payment_type,milestone_payment|array',
            'milestone_phases.*.name' => 'nullable|string|max:255',
            'milestone_phases.*.percentage' => 'nullable|numeric|min:1|max:100',
            'downpayment_percentage' => 'nullable|required_if:payment_type,downpayment|numeric|min:1|max:99',
            // Task creation fields - only validate if create_task is checked
            'create_task' => 'nullable|boolean',
            'task_title' => 'nullable|required_if:create_task,on|string|max:255',
            'task_description' => 'nullable|required_if:create_task,on|string',
            'task_priority' => 'nullable|required_if:create_task,on|in:low,medium,high,urgent',
            'task_due_date' => 'nullable|date',
            'adiutor_id' => 'nullable|exists:users,id',
        ]);

        // Validate milestone percentages total 100% if milestone payment
        if ($request->payment_type === 'milestone_payment' && $request->has('milestone_phases')) {
            $totalPercentage = collect($request->milestone_phases)->sum('percentage');
            if (abs($totalPercentage - 100) > 0.01) { // Allow for small floating point differences
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['milestone_phases' => 'Milestone percentages must total exactly 100%. Current total: ' . $totalPercentage . '%']);
            }
        }

        // Prepare service request update data
        $updateData = [
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_budget' => $request->approved_budget,
            'payment_method' => $serviceRequest->contact_method ?? 'email',
            'payment_due_date' => $request->payment_due_date,
            'payment_instructions' => $request->payment_instructions,
            'payment_type' => $request->payment_type,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'reviewed_at' => now(),
        ];

        // Handle downpayment type
        if ($request->payment_type === 'downpayment') {
            $updateData['downpayment_percentage'] = $request->downpayment_percentage;
            $updateData['downpayment_amount'] = ($request->approved_budget * $request->downpayment_percentage) / 100;
            $updateData['remaining_balance'] = $request->approved_budget - $updateData['downpayment_amount'];
        }

        // Handle milestone payment type
        if ($request->payment_type === 'milestone_payment' && $request->has('milestone_phases')) {
            $updateData['total_milestones'] = count($request->milestone_phases);
        }
        
        // Update request
        $serviceRequest->update($updateData);
        
        // Create project immediately when approved (needed for milestones)
        $project = Project::firstOrCreate(
            ['service_request_id' => $serviceRequest->id],
            [
                'client_id' => $serviceRequest->client_id,
                'title' => $serviceRequest->project_name,
                'description' => $serviceRequest->request_description,
                'budget' => $request->approved_budget,
                'deadline' => $serviceRequest->deadline,
                'status' => 'active', // Project is active once approved, will move to in_progress after payment
                'priority' => $serviceRequest->priority ?? 'medium',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Create milestone phases if milestone payment type
        if ($request->payment_type === 'milestone_payment' && $request->has('milestone_phases')) {
            // Delete existing milestones if any
            $project->milestones()->delete();
            
            $phaseOrder = 1;
            foreach ($request->milestone_phases as $phase) {
                if (!empty($phase['name']) && !empty($phase['percentage'])) {
                    $phaseAmount = ($request->approved_budget * $phase['percentage']) / 100;
                    
                    \App\Models\ProjectMilestone::create([
                        'project_id' => $project->id,
                        'service_request_id' => $serviceRequest->id,
                        'phase_name' => $phase['name'],
                        'phase_order' => $phaseOrder,
                        'percentage' => $phase['percentage'],
                        'amount' => $phaseAmount,
                        'is_paid' => false,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    $phaseOrder++;
                }
            }
            
            Log::info('Created ' . ($phaseOrder - 1) . ' milestone phases for project ' . $project->id);
        }
        
        // Create task if requested
        if ($request->has('create_task') && $request->create_task && $request->filled('task_title')) {
            $taskData = [
                'service_request_id' => $serviceRequest->id,
                'client_id' => $serviceRequest->client_id,
                'title' => $request->task_title,
                'description' => $request->task_description,
                'priority' => $request->task_priority ?? 'medium',
                'status' => 'pending',
                'created_by' => Auth::id(),
            ];
            
            if ($request->filled('task_due_date')) {
                $taskData['due_date'] = $request->task_due_date;
            }
            
            if ($request->filled('adiutor_id')) {
                $taskData['adiutor_id'] = $request->adiutor_id;
                $taskData['status'] = 'assigned';
            }
            
            \App\Models\Task::create($taskData);
        }
        
        // Send email notification
        try {
            // Refresh the service request to get the latest data
            $serviceRequest->refresh();
            $serviceRequest->load('client');
            
            Mail::to($serviceRequest->client->email)
                ->send(new RequestApproved($serviceRequest));
                
            Log::info('Approval email sent to: ' . $serviceRequest->client->email);
        } catch (\Exception $e) {
            Log::error('Failed to send approval email: ' . $e->getMessage());
        }
        
        return redirect()->route('admin.requests.show', $serviceRequest->id)
                        ->with('success', 'Request approved successfully with ' . ucfirst(str_replace('_', ' ', $request->payment_type)) . '. Client has been notified via email.');
    }
    
    public function requestPayment($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        if ($serviceRequest->status !== 'approved') {
            return redirect()->back()->with('error', 'Request must be approved first.');
        }
        
        // Update status to pending payment
        $serviceRequest->update([
            'status' => 'pending_payment'
        ]);
        
        return redirect()->route('admin.requests.show', $serviceRequest->id)
                        ->with('success', 'Payment request sent to client.');
    }
    
    public function confirmPayment(Request $request, $id)
    {
        // THIS METHOD IS NO LONGER USED
        // Maya payment gateway handles payment confirmation automatically
        // When payment is successful, MayaPaymentController automatically:
        // 1. Confirms payment
        // 2. Updates service request status to 'paid'
        // 3. Creates project
        // 4. Sends confirmation emails
        
        return redirect()->route('admin.requests.show', $id)
                        ->with('info', 'Payment is handled automatically by Maya payment gateway. Manual confirmation is no longer needed.');
    }
    
    /**
     * View payment proof - DEPRECATED
     * Maya payment gateway handles payment verification automatically.
     * This method is kept for backward compatibility but no longer functional.
     */
    public function viewPaymentProof($id)
    {
        return redirect()->back()->with('info', 'Payment proof viewing is no longer available. Maya payment gateway handles verification automatically.');
    }
    
    public function reject(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $request->validate([
            'admin_notes' => 'required|string',
            'rejection_reason' => 'required|string',
        ]);
        
        $serviceRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);
        
        return redirect()->route('admin.requests.show', $serviceRequest->id)
                        ->with('success', 'Request rejected successfully.');
    }
    
    public function reopen($id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $serviceRequest->update([
            'status' => 'pending',
            'admin_notes' => null,
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
        
        return redirect()->route('admin.requests.show', $serviceRequest->id)
                        ->with('success', 'Request reopened successfully.');
    }
    
    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);
        
        $serviceRequest = ServiceRequest::findOrFail($id);
        $serviceRequest->update(['priority' => $request->priority]);
        
        return redirect()->back()->with('success', 'Priority updated successfully.');
    }
    
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string',
        ]);
        
        $serviceRequest = ServiceRequest::findOrFail($id);
        $currentNotes = $serviceRequest->admin_notes ?? '';
        $newNote = "[" . now()->format('Y-m-d H:i:s') . " - " . Auth::user()->fullName . "]\n" . $request->note . "\n\n";
        
        $serviceRequest->update([
            'admin_notes' => $newNote . $currentNotes
        ]);
        
        return redirect()->back()->with('success', 'Note added successfully.');
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete,update_priority',
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:service_requests,id',
            'priority' => 'required_if:action,update_priority|in:low,medium,high,urgent',
            'bulk_notes' => 'nullable|string',
        ]);
        
        $requests = ServiceRequest::whereIn('id', $request->request_ids);
        
        switch ($request->action) {
            case 'approve':
                $requests->update([
                    'status' => 'approved',
                    'admin_notes' => $request->bulk_notes,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);
                $message = 'Requests approved successfully.';
                break;
                
            case 'reject':
                $requests->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->bulk_notes,
                    'rejection_reason' => $request->bulk_notes ?? 'Bulk rejection',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);
                $message = 'Requests rejected successfully.';
                break;
                
            case 'update_priority':
                $requests->update(['priority' => $request->priority]);
                $message = 'Request priorities updated successfully.';
                break;
                
            case 'delete':
                $requests->delete();
                $message = 'Requests deleted successfully.';
                break;
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function downloadFile($requestId, $fileId)
    {
        // Get attachment for ServiceRequest
        $attachment = RequestAttachment::where('service_request_id', $requestId)->findOrFail($fileId);
        
        // Use the model's method to get the proper download URL
        return redirect($attachment->getDownloadUrl());
    }
    
    public function export(Request $request)
    {
        // This would implement CSV/Excel export functionality
        $query = ServiceRequest::with(['client']);
        
        // Apply same filters as index method
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('service_type', $request->type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $requests = $query->get();
        
        // For now, just return a simple CSV
        $filename = 'service_requests_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($requests) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Project Name', 'Client', 'Service Type', 'Status', 'Priority', 'Created At']);
            
            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->project_name,
                    $request->client->fullName ?? 'N/A',
                    $request->service_type,
                    $request->status,
                    $request->priority ?? 'N/A',
                    $request->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}