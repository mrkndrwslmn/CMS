<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Fetch user's notifications (for dropdown)
     */
    public function fetch()
    {
        $user = Auth::user();
        
        // Get notifications for the authenticated user
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                $notification->data = json_decode($notification->data, true);
                return $notification;
            });
        
        // Count unread notifications
        $unreadCount = DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
    
    /**
     * Display all notifications page
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($notification) {
                $notification->data = json_decode($notification->data, true);
                return $notification;
            });
        
        // Get the appropriate layout based on user role
        $layout = $user->role . '.notifications.index';
        
        return view($layout, compact('notifications'));
    }
    
    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        DB::table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $user->id)
            ->delete();
        
        return response()->json(['success' => true]);
    }
}
