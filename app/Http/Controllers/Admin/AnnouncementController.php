<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    /**
     * Display the announcements index page
     */
    public function index()
    {
        // Get all announcements with creator and updater info, ordered by priority then date
        $announcements = Announcement::with(['creator', 'updater'])
            ->orderByRaw("CASE 
                WHEN priority = 'high' THEN 1 
                WHEN priority = 'medium' THEN 2 
                WHEN priority = 'low' THEN 3 
                ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics based on actual status and expiry
        $stats = [
            'total' => $announcements->count(),
            'active' => $announcements->filter(function($a) {
                return $a->status === 'active' && (!$a->expires_at || $a->expires_at->isFuture());
            })->count(),
            'expired' => $announcements->filter(function($a) {
                return $a->status === 'expired' || ($a->expires_at && $a->expires_at->isPast() && $a->status === 'active');
            })->count(),
            'scheduled' => $announcements->where('status', 'scheduled')->count(),
        ];

        return view('admin.announcements.index', compact('announcements', 'stats'));
    }

    /**
     * Store a new announcement
     */
    public function store(Request $request)
    {
        \Log::info('Store announcement request', $request->all());
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in(['active', 'scheduled', 'draft'])],
            'target_audience' => 'required|array|min:1',
            'target_audience.*' => Rule::in(['client', 'adiutor', 'public', 'all']),
            'starts_at' => 'nullable|date|required_if:status,scheduled',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['target_audience'] = implode(',', $validated['target_audience']);

        \Log::info('Creating announcement with data', $validated);
        
        $announcement = Announcement::create($validated);
        
        \Log::info('Announcement created', ['id' => $announcement->id, 'title' => $announcement->title]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Update an existing announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        \Log::info('Update announcement request', ['id' => $announcement->id, 'data' => $request->all()]);
        
        // Check if announcement is expired and user is trying to keep expired status
        $isCurrentlyExpired = $announcement->status === 'expired' || 
                             ($announcement->expires_at && $announcement->expires_at->isPast());
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in(['active', 'scheduled', 'draft', 'expired'])],
            'target_audience' => 'required|array|min:1',
            'target_audience.*' => Rule::in(['client', 'adiutor', 'public', 'all']),
            'starts_at' => 'nullable|date|required_if:status,scheduled',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['updated_by'] = Auth::id();
        $validated['target_audience'] = implode(',', $validated['target_audience']);

        // If currently expired and trying to set to active/scheduled with past expiry, warn user
        if ($isCurrentlyExpired && in_array($validated['status'], ['active', 'scheduled'])) {
            if (!empty($validated['expires_at']) && \Carbon\Carbon::parse($validated['expires_at'])->isPast()) {
                return redirect()->route('admin.announcements.index')
                    ->with('error', 'Cannot activate an expired announcement. Please set a future expiry date or remove the expiry date to reactivate.');
            }
            
            // If no expiry date set, allow reactivation
            if (empty($validated['expires_at'])) {
                \Log::info('Reactivating expired announcement without expiry date');
            }
        }

        // If changing status to active/scheduled and expires_at is in the past, clear it
        if (in_array($validated['status'], ['active', 'scheduled'])) {
            if (!empty($validated['expires_at']) && \Carbon\Carbon::parse($validated['expires_at'])->isPast()) {
                $validated['expires_at'] = null;
                \Log::info('Cleared past expiry date when reactivating announcement');
            }
        }

        \Log::info('Updating announcement with data', $validated);
        
        $announcement->update($validated);
        
        \Log::info('Announcement updated', ['id' => $announcement->id, 'title' => $announcement->title]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete an announcement
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully!'
        ]);
    }

    /**
     * Get active announcements for API/public display
     */
    public function getActive()
    {
        // Update statuses first
        $this->updateAnnouncementStatuses();

        $announcements = Announcement::active()
            ->orderByRaw("CASE 
                WHEN priority = 'high' THEN 1 
                WHEN priority = 'medium' THEN 2 
                WHEN priority = 'low' THEN 3 
                ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($announcements);
    }

    /**
     * Update announcement statuses (scheduled to active, active to expired)
     */
    private function updateAnnouncementStatuses()
    {
        // Activate scheduled announcements that should be active
        Announcement::where('status', 'scheduled')
            ->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->update(['status' => 'active']);

        // Expire active/scheduled announcements that have passed their expiry
        Announcement::where('expires_at', '<=', now())
            ->whereNotNull('expires_at')
            ->whereIn('status', ['active', 'scheduled'])
            ->update(['status' => 'expired']);
    }
}
