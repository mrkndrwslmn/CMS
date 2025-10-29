@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="pt-20 pb-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
                <p class="text-gray-600 mt-1">Stay updated with all system activities</p>
            </div>
            
            @if($notifications->total() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-secondary transition-colors">
                    <i class="fas fa-check-double mr-2"></i> Mark All as Read
                </button>
            </form>
            @endif
        </div>
        
        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow-md">
            @forelse($notifications as $notification)
                @php
                    $actionUrl = $notification->data['action_url'] ?? null;
                    $isClickable = !empty($actionUrl);
                @endphp
                
                <div class="px-6 py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50' }} {{ $isClickable ? 'cursor-pointer' : '' }}"
                     @if($isClickable) onclick="handleNotificationClick('{{ $actionUrl }}', '{{ $notification->id }}')" @endif>
                    <div class="flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center 
                            @if(str_contains($notification->type, 'completed') || str_contains($notification->type, 'approved'))
                                bg-green-100 text-green-600
                            @elseif(str_contains($notification->type, 'rejected') || str_contains($notification->type, 'failed'))
                                bg-red-100 text-red-600
                            @elseif(str_contains($notification->type, 'payment'))
                                bg-yellow-100 text-yellow-600
                            @else
                                bg-blue-100 text-blue-600
                            @endif">
                            <i class="fas 
                                @if(str_contains($notification->type, 'task'))
                                    fa-tasks
                                @elseif(str_contains($notification->type, 'project'))
                                    fa-project-diagram
                                @elseif(str_contains($notification->type, 'payment'))
                                    fa-dollar-sign
                                @elseif(str_contains($notification->type, 'request'))
                                    fa-file-alt
                                @elseif(str_contains($notification->type, 'budget'))
                                    fa-money-bill-wave
                                @else
                                    fa-bell
                                @endif
                                text-xl"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div class="{{ $isClickable ? 'flex-1' : '' }}">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                        @if(!$notification->read_at)
                                            <span class="inline-block w-3 h-3 bg-blue-500 rounded-full ml-2"></span>
                                        @endif
                                    </h3>
                                    <p class="text-gray-700 mt-1">{{ $notification->data['message'] ?? 'No message' }}</p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </p>
                                    
                                    @if($isClickable)
                                    <p class="text-xs text-blue-600 mt-2">
                                        <i class="fas fa-hand-pointer mr-1"></i>
                                        Click to view details
                                    </p>
                                    @endif
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex space-x-2" onclick="event.stopPropagation()">
                                    @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this notification?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Debug Info (only in dev) -->
                            @if(config('app.debug'))
                            <div class="mt-2 p-2 bg-gray-100 rounded text-xs">
                                <strong>Debug:</strong> Type: {{ $notification->type ?? 'Unknown' }} | 
                                Action URL: {{ $actionUrl ?? 'None' }} |
                                Read: {{ $notification->read_at ? 'Yes' : 'No' }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No notifications yet</p>
                    <p class="text-gray-400 text-sm mt-2">You'll see updates about system activities here</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function handleNotificationClick(actionUrl, notificationId) {
    // Mark as read if not already read
    if (actionUrl) {
        // Optional: Mark as read via AJAX before navigation
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        }).catch(error => {
            console.warn('Could not mark notification as read:', error);
        });
        
        // Navigate to the action URL
        window.location.href = actionUrl;
    }
}
</script>
@endsection
