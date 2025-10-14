<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\User;
use App\Models\Task;
use App\Models\FormFile;
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

class RequestManagementController extends Controller
{
    public function index(Request $request)
    {
        // Handle both ServiceRequests (new) and Forms (legacy) in one unified view
        $serviceRequests = ServiceRequest::with(['client', 'attachments']);
        $forms = Form::with(['client', 'files']);
        
        // Search functionality for ServiceRequests
        if ($request->filled('search')) {
            $search = $request->search;
            $serviceRequests->where(function($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                  ->orWhere('request_description', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('fullName', 'like', "%{$search}%");
                  });
            });
            
            // Search functionality for Forms
            $forms->where(function($q) use ($search) {
                $q->where('projectDescription', 'like', "%{$search}%")
                  ->orWhere('companyName', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('fullName', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $serviceRequests->where('status', $request->status);
            $forms->where('status', $request->status);
        }
        
        // Type filter - handle different field names
        if ($request->filled('type')) {
            $serviceRequests->where('service_type', $request->type);
            $forms->where('businessType', $request->type);
        }
        
        // Priority filter
        if ($request->filled('priority')) {
            $serviceRequests->where('priority', $request->priority);
            $forms->where('priority', $request->priority);
        }
        
        // Client filter
        if ($request->filled('client')) {
            $serviceRequests->where('client_id', $request->client);
            $forms->where('client_id', $request->client);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $serviceRequests->whereDate('created_at', '>=', $request->date_from);
            $forms->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $serviceRequests->whereDate('created_at', '<=', $request->date_to);
            $forms->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Get collections and merge them
        $serviceRequestCollection = $serviceRequests->get()->map(function($request) {
            $request->type = 'service_request';
            $request->display_title = $request->project_name ?? 'Request #' . $request->id;
            $request->display_description = $request->request_description;
            $request->display_type = $request->service_type;
            $request->display_id = $request->id;
            return $request;
        });
        
        $formCollection = $forms->get()->map(function($form) {
            $form->type = 'form';
            $form->display_title = $form->companyName ?? 'Form #' . $form->formID;
            $form->display_description = $form->projectDescription;
            $form->display_type = $form->businessType;
            $form->display_id = $form->formID;
            return $form;
        });
        
        // Merge and sort collections
        $allRequests = $serviceRequestCollection->concat($formCollection);
        
        // Sort by created_at descending
        $allRequests = $allRequests->sortByDesc('created_at');
        
        // Manual pagination
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $allRequests->count();
        $offset = ($page - 1) * $perPage;
        $items = $allRequests->slice($offset, $perPage)->values();
        
        $requests = new \Illuminate\Pagination\LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Get filter options
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        
        // Get combined statistics
        $stats = [
            'total_requests' => ServiceRequest::count() + Form::count(),
            'pending_requests' => ServiceRequest::where('status', 'pending')->count() + Form::where('status', 'pending')->count(),
            'approved_requests' => ServiceRequest::where('status', 'approved')->count() + Form::where('status', 'approved')->count(),
            'rejected_requests' => ServiceRequest::where('status', 'rejected')->count() + Form::where('status', 'rejected')->count(),
        ];
        
        return view('admin.requests.index', compact('requests', 'clients', 'stats'));
    }
    
    public function show($id)
    {
        // Try to find in ServiceRequests first (new system)
        $serviceRequest = ServiceRequest::with(['client', 'attachments', 'tasks', 'payments'])->find($id);
        
        if ($serviceRequest) {
            $serviceRequest->type = 'service_request';
            return view('admin.requests.show', ['request' => $serviceRequest]);
        }
        
        // If not found, try Forms table (legacy system)
        $form = Form::with(['client', 'files', 'tasks'])->where('formID', $id)->first();
        
        if ($form) {
            $form->type = 'form';
            return view('admin.requests.show', ['request' => $form]);
        }
        
        // If neither found, throw 404
        abort(404, 'Request not found');
    }
    
    public function approve(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $request->validate([
            'admin_notes' => 'nullable|string',
            'approved_budget' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_due_date' => 'required|date|after:today',
            'payment_instructions' => 'nullable|string',
        ]);
        
        // Update request status to approved and set budget
        $serviceRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_budget' => $request->approved_budget,
            'payment_method' => $request->payment_method,
            'payment_due_date' => $request->payment_due_date,
            'payment_instructions' => $request->payment_instructions,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'reviewed_at' => now(),
        ]);
        
        // Send email notification
        Mail::to($serviceRequest->client->email)
            ->send(new RequestApproved($serviceRequest));
        
        return redirect()->route('admin.requests.show', $serviceRequest->id)
                        ->with('success', 'Request approved successfully. Client will be notified to proceed with payment.');
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
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $request->validate([
            'payment_reference' => 'required|string',
            'payment_notes' => 'nullable|string',
        ]);
        
        // Create payment record
        $payment = Payment::create([
            'service_request_id' => $serviceRequest->id,
            'amount' => $serviceRequest->approved_budget,
            'payment_method' => $serviceRequest->payment_method,
            'payment_reference' => $request->payment_reference,
            'status' => 'confirmed',
            'notes' => $request->payment_notes,
            'confirmed_at' => now(),
            'confirmed_by' => Auth::id(),
        ]);
        
        // Update service request status
        $serviceRequest->update([
            'status' => 'paid',
            'payment_confirmed_at' => now(),
            'payment_reference' => $request->payment_reference,
        ]);
        
        // IMPORTANT: Create a PROJECT from the paid service request
        $project = Project::create([
            'service_request_id' => $serviceRequest->id,
            'client_id' => $serviceRequest->client_id,
            'title' => $serviceRequest->project_name,
            'description' => $serviceRequest->request_description,
            'status' => 'active',
            'budget' => $serviceRequest->approved_budget,
            'deadline' => $serviceRequest->deadline,
            'priority' => $serviceRequest->priority,
            'started_at' => now(),
        ]);
        
        // Send payment confirmation email
        Mail::to($serviceRequest->client->email)
            ->send(new PaymentConfirmed($serviceRequest));
        
        return redirect()->route('admin.requests.show', $serviceRequest->id)
                        ->with('success', 'Payment confirmed successfully. Project #' . $project->id . ' has been created and can now have tasks assigned.');
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
        // Check if this is an attachment for ServiceRequest
        $attachment = RequestAttachment::where('service_request_id', $requestId)->findOrFail($fileId);
        
        if (Storage::exists($attachment->file_path)) {
            return Storage::download($attachment->file_path, $attachment->original_name);
        }
        
        return redirect()->back()->with('error', 'File not found.');
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