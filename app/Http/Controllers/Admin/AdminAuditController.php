<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminAuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return $next($request);
        });
    }

    /**
     * Display audit logs dashboard
     */
    public function index(Request $request)
    {
        $query = AuditLog::with(['user', 'auditable'])->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by IP address
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Search in old/new values
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('old_values', 'like', "%{$search}%")
                  ->orWhere('new_values', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('fullName', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $auditLogs = $query->paginate(25);

        // Get filter options
        $users = User::select('id', 'fullName', 'email')
            ->whereHas('auditLogs')
            ->orderBy('fullName')
            ->get();

        $actions = AuditLog::distinct()
            ->pluck('action')
            ->sort()
            ->values();

        $modelTypes = AuditLog::distinct()
            ->pluck('auditable_type')
            ->map(function ($type) {
                return [
                    'value' => $type,
                    'label' => class_basename($type)
                ];
            })
            ->sortBy('label')
            ->values();

        // Statistics
        $stats = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', Carbon::today())->count(),
            'week_logs' => AuditLog::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'active_users' => AuditLog::distinct('user_id')
                ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
                ->count('user_id'),
        ];

        return view('admin.audit.index', compact(
            'auditLogs', 
            'users', 
            'actions', 
            'modelTypes', 
            'stats'
        ));
    }

    /**
     * Show detailed audit log entry
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load(['user', 'auditable']);
        
        // Get related audit logs for the same record
        $relatedLogs = AuditLog::where('auditable_type', $auditLog->auditable_type)
            ->where('auditable_id', $auditLog->auditable_id)
            ->where('id', '!=', $auditLog->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.audit.show', compact('auditLog', 'relatedLogs'));
    }

    /**
     * Get audit logs via AJAX for real-time updates
     */
    public function logs(Request $request)
    {
        $query = AuditLog::with(['user'])->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('auditable_type', $request->model_type);
        }

        if ($request->filled('hours')) {
            $hours = (int) $request->hours;
            $query->where('created_at', '>=', Carbon::now()->subHours($hours));
        }

        $logs = $query->limit(50)->get();

        return response()->json([
            'success' => true,
            'logs' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'user_name' => $log->user ? $log->user->fullName : 'System',
                    'user_email' => $log->user ? $log->user->email : null,
                    'model_type' => class_basename($log->auditable_type),
                    'model_id' => $log->auditable_id,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'created_at' => $log->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $log->created_at->diffForHumans(),
                    'has_changes' => !empty($log->old_values) || !empty($log->new_values),
                ];
            })
        ]);
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        $query = AuditLog::with(['user'])->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->limit(5000)->get(); // Limit for performance

        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Date & Time',
                'User',
                'User Email',
                'Action',
                'Model Type',
                'Model ID',
                'IP Address',
                'User Agent',
                'Old Values',
                'New Values'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->fullName : 'System',
                    $log->user ? $log->user->email : '',
                    $log->action,
                    class_basename($log->auditable_type),
                    $log->auditable_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->old_values ? json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT) : '',
                    $log->new_values ? json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT) : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete old audit logs (cleanup)
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        $cutoffDate = Carbon::now()->subDays($request->days);
        $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        return redirect()->back()->with('success', "Deleted {$deletedCount} audit log entries older than {$request->days} days.");
    }

    /**
     * Get audit statistics for dashboard widgets
     */
    public function statistics()
    {
        $stats = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', Carbon::today())->count(),
            'week_logs' => AuditLog::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'month_logs' => AuditLog::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'active_users_today' => AuditLog::distinct('user_id')
                ->whereDate('created_at', Carbon::today())
                ->count('user_id'),
            'top_actions' => AuditLog::selectRaw('action, COUNT(*) as count')
                ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'top_models' => AuditLog::selectRaw('auditable_type, COUNT(*) as count')
                ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
                ->groupBy('auditable_type')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    $item->model_name = class_basename($item->auditable_type);
                    return $item;
                }),
        ];

        return response()->json(['success' => true, 'stats' => $stats]);
    }
}
