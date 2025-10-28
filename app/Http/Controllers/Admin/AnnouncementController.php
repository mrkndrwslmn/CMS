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
        // Update scheduled and expired announcements first
        $this->updateAnnouncementStatuses();

        // Get all announcements with creator and updater info, ordered by priority then date
        $announcements = Announcement::with(['creator', 'updater'])
            ->orderByRaw("CASE 
                WHEN priority = 'high' THEN 1 
                WHEN priority = 'medium' THEN 2 
                WHEN priority = 'low' THEN 3 
                ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total' => $announcements->count(),
            'active' => $announcements->where('status', 'active')->count(),
            'expired' => $announcements->where('status', 'expired')->count(),
            'scheduled' => $announcements->where('status', 'scheduled')->count(),
        ];

        return view('admin.announcements.index', compact('announcements', 'stats'));
    }

    /**
     * Store a new announcement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in(['active', 'scheduled', 'draft'])],
            'starts_at' => 'nullable|date|required_if:status,scheduled',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['created_by'] = Auth::id();

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Update an existing announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'status' => ['required', Rule::in(['active', 'scheduled', 'draft'])],
            'starts_at' => 'nullable|date|required_if:status,scheduled',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        $validated['updated_by'] = Auth::id();

        $announcement->update($validated);

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
