<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Notifications\FeedbackResponseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with(['client', 'adiutor', 'task', 'project']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('client', function($clientQuery) use ($search) {
                      $clientQuery->where('fullName', 'like', "%{$search}%");
                  });
            });
        }
        
        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Client filter
        if ($request->filled('client')) {
            $query->where('client_id', $request->client);
        }
        
        // Adiutor filter
        if ($request->filled('adiutor')) {
            $query->where('adiutor_id', $request->adiutor);
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);
        
        $feedbacks = $query->paginate(15)->withQueryString();
        
        // Get filter options
        $clients = User::where('role', 'client')->orderBy('fullName')->get();
        $adiutors = User::where('role', 'adiutor')->orderBy('fullName')->get();
        
        // Get statistics
        $stats = [
            'total_feedback' => Feedback::count(),
            'average_rating' => round(Feedback::avg('rating'), 1),
            'pending_feedback' => Feedback::where('status', 'pending')->count(),
            'resolved_feedback' => Feedback::where('status', 'resolved')->count(),
            'positive_feedback' => Feedback::where('rating', '>=', 4)->count(),
            'negative_feedback' => Feedback::where('rating', '<=', 2)->count(),
        ];
        
        return view('admin.feedback.index', compact('feedbacks', 'clients', 'adiutors', 'stats'));
    }
    
    public function show($id)
    {
        $feedback = Feedback::with(['client', 'project.assignments.adiutor', 'task'])->findOrFail($id);
        
        return view('admin.feedback.show', compact('feedback'));
    }
    
    public function respond(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        
        $request->validate([
            'response' => 'required|string',
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'internal_notes' => 'nullable|string',
        ]);
        
        // Update feedback
        $feedback->update([
            'admin_response' => $request->response,
            'status' => $request->status,
            'internal_notes' => $request->internal_notes,
            'responded_by' => Auth::id(),
            'responded_at' => now(),
        ]);

        // Notify the client about the response
        if ($feedback->client) {
            $feedback->client->notify(new FeedbackResponseNotification($feedback, $request->response));
        }
        
        return redirect()->route('admin.feedback.show', $feedback->id)
                        ->with('success', 'Response added successfully.');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'notes' => 'nullable|string',
        ]);
        
        $feedback = Feedback::findOrFail($id);
        $oldStatus = $feedback->status;
        
        $updateData = [
            'status' => $request->status,
        ];
        
        if ($request->filled('notes')) {
            $currentNotes = $feedback->internal_notes ?? '';
            $newNote = "[" . now()->format('Y-m-d H:i:s') . " - Status changed from {$oldStatus} to {$request->status}]\n" . $request->notes . "\n\n";
            $updateData['internal_notes'] = $newNote . $currentNotes;
        }
        
        if ($request->status === 'resolved' && $oldStatus !== 'resolved') {
            $updateData['resolved_at'] = now();
            $updateData['resolved_by'] = Auth::id();
        }
        
        $feedback->update($updateData);
        
        return redirect()->back()->with('success', 'Status updated successfully.');
    }
    
    public function assignTo(Request $request, $id)
    {
        $request->validate([
            'adiutor_id' => 'required|exists:users,id',
        ]);
        
        $feedback = Feedback::findOrFail($id);
        $feedback->update([
            'adiutor_id' => $request->adiutor_id,
            'status' => 'in_progress',
        ]);
        
        return redirect()->back()->with('success', 'Feedback assigned successfully.');
    }
    
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string',
        ]);
        
        $feedback = Feedback::findOrFail($id);
        $currentNotes = $feedback->internal_notes ?? '';
        $newNote = "[" . now()->format('Y-m-d H:i:s') . " - " . Auth::user()->fullName . "]\n" . $request->note . "\n\n";
        
        $feedback->update([
            'internal_notes' => $newNote . $currentNotes
        ]);
        
        return redirect()->back()->with('success', 'Note added successfully.');
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:update_status,assign,delete,mark_priority',
            'feedback_ids' => 'required|array',
            'feedback_ids.*' => 'exists:feedbacks,id',
            'status' => 'required_if:action,update_status|in:pending,in_progress,resolved,closed',
            'adiutor_id' => 'required_if:action,assign|exists:users,id',
            'priority' => 'required_if:action,mark_priority|in:low,medium,high,urgent',
        ]);
        
        $feedbacks = Feedback::whereIn('id', $request->feedback_ids);
        
        switch ($request->action) {
            case 'update_status':
                $updateData = ['status' => $request->status];
                if ($request->status === 'resolved') {
                    $updateData['resolved_at'] = now();
                    $updateData['resolved_by'] = Auth::id();
                }
                $feedbacks->update($updateData);
                $message = 'Feedback statuses updated successfully.';
                break;
                
            case 'assign':
                $feedbacks->update([
                    'adiutor_id' => $request->adiutor_id,
                    'status' => 'in_progress',
                ]);
                $message = 'Feedbacks assigned successfully.';
                break;
                
            case 'mark_priority':
                $feedbacks->update(['priority' => $request->priority]);
                $message = 'Feedback priorities updated successfully.';
                break;
                
            case 'delete':
                $feedbacks->delete();
                $message = 'Feedbacks deleted successfully.';
                break;
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);
        
        // Feedback Analytics
        $analytics = [
            'total_feedback' => Feedback::count(),
            'period_feedback' => Feedback::where('created_at', '>=', $startDate)->count(),
            'average_rating' => round(Feedback::avg('rating'), 2),
            'period_average_rating' => round(Feedback::where('created_at', '>=', $startDate)->avg('rating'), 2),
            'response_rate' => round(Feedback::whereNotNull('admin_response')->count() / max(Feedback::count(), 1) * 100, 1),
            'resolution_rate' => round(Feedback::where('status', 'resolved')->count() / max(Feedback::count(), 1) * 100, 1),
        ];
        
        // Rating Distribution
        $ratingDistribution = Feedback::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();
            
        // Feedback Trend
        $feedbackTrend = Feedback::selectRaw('DATE(created_at) as date, AVG(rating) as avg_rating, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Top Issues (by type)
        $topIssues = Feedback::selectRaw('type, COUNT(*) as count, AVG(rating) as avg_rating')
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->get();
            
        // Adiutor Performance (derived from project feedback)
        $adiutorPerformance = DB::table('users')
            ->join('project_assignments', 'users.id', '=', 'project_assignments.adiutor_id')
            ->join('feedbacks', 'project_assignments.project_id', '=', 'feedbacks.project_id')
            ->where('users.role', 'adiutor')
            ->whereNotNull('feedbacks.rating')
            ->select(
                'users.id',
                'users.fullName',
                'users.email',
                DB::raw('COUNT(DISTINCT feedbacks.id) as received_feedback_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN feedbacks.rating >= 4 THEN feedbacks.id END) as positive_feedback_count'),
                DB::raw('AVG(feedbacks.rating) as received_feedback_avg_rating')
            )
            ->groupBy('users.id', 'users.fullName', 'users.email')
            ->having('received_feedback_count', '>', 0)
            ->orderBy('received_feedback_avg_rating', 'desc')
            ->get();
            
        // Client Satisfaction by Segment
        $clientSatisfaction = User::where('role', 'client')
            ->withCount('feedbacks')
            ->withAvg('feedbacks', 'rating')
            ->having('feedbacks_count', '>', 0)
            ->orderBy('feedbacks_avg_rating', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.feedback.analytics', compact(
            'analytics', 'ratingDistribution', 'feedbackTrend', 'topIssues', 
            'adiutorPerformance', 'clientSatisfaction', 'period'
        ));
    }
    
    public function export(Request $request)
    {
        $query = Feedback::with(['client', 'adiutor']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $feedbacks = $query->get();
        
        $filename = 'feedback_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($feedbacks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Title', 'Client', 'Adiutor', 'Rating', 'Type', 'Status', 
                'Message', 'Response', 'Created At', 'Resolved At'
            ]);
            
            foreach ($feedbacks as $feedback) {
                fputcsv($file, [
                    $feedback->id,
                    $feedback->title,
                    $feedback->client->fullName ?? 'N/A',
                    $feedback->adiutor->fullName ?? 'N/A',
                    $feedback->rating,
                    $feedback->type,
                    $feedback->status,
                    $feedback->message,
                    $feedback->admin_response ?? 'No response',
                    $feedback->created_at->format('Y-m-d H:i:s'),
                    $feedback->resolved_at ? $feedback->resolved_at->format('Y-m-d H:i:s') : 'Not resolved',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function summary()
    {
        // Quick summary for dashboard widgets
        $summary = [
            'total_feedback' => Feedback::count(),
            'pending_feedback' => Feedback::where('status', 'pending')->count(),
            'average_rating' => round(Feedback::avg('rating'), 1),
            'recent_feedback' => Feedback::with(['client'])
                ->latest()
                ->take(5)
                ->get(),
        ];
        
        return response()->json($summary);
    }
}