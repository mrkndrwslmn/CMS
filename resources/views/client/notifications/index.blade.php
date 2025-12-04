@extends('client.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Notifications', 'icon' => 'bell'],
    ]" />

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Notifications</h1>
            <p class="text-sm text-neutral-500 mt-1">Stay updated with your projects and tasks</p>
        </div>
        
        @if($notifications->total() > 0)
        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                <x-lucide-check-check class="w-4 h-4" />
                Mark All as Read
            </button>
        </form>
        @endif
    </div>
    
    <!-- Notifications List -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
            @forelse($notifications as $notification)
                @php
                    $actionUrl = $notification->data['action_url'] ?? null;
                    $isClickable = !empty($actionUrl);
                    
                    // Determine notification type for styling
                    $isSuccess = str_contains($notification->type, 'completed') || str_contains($notification->type, 'approved');
                    $isError = str_contains($notification->type, 'rejected') || str_contains($notification->type, 'failed');
                    $isWarning = str_contains($notification->type, 'payment') || str_contains($notification->type, 'budget');
                    
                    // Determine icon based on type
                    $iconName = 'bell';
                    if (str_contains($notification->type, 'task')) $iconName = 'check-square';
                    elseif (str_contains($notification->type, 'project')) $iconName = 'folder-kanban';
                    elseif (str_contains($notification->type, 'payment') || str_contains($notification->type, 'budget')) $iconName = 'wallet';
                    elseif (str_contains($notification->type, 'request')) $iconName = 'file-text';
                    elseif (str_contains($notification->type, 'message')) $iconName = 'message-square';
                @endphp
                
                <div class="px-6 py-4 border-b border-neutral-100 hover:bg-neutral-50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-primary-50/50' }} {{ $isClickable ? 'cursor-pointer' : '' }}"
                     @if($isClickable) onclick="handleNotificationClick('{{ $actionUrl }}', '{{ $notification->id }}')" @endif>
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center 
                            @if($isSuccess)
                                bg-success-50 text-success-600
                            @elseif($isError)
                                bg-error-50 text-error-600
                            @elseif($isWarning)
                                bg-warning-50 text-warning-600
                            @else
                                bg-primary-50 text-primary-600
                            @endif">
                            @switch($iconName)
                                @case('check-square')
                                    <x-lucide-check-square class="w-5 h-5" />
                                    @break
                                @case('folder-kanban')
                                    <x-lucide-folder-kanban class="w-5 h-5" />
                                    @break
                                @case('wallet')
                                    <x-lucide-wallet class="w-5 h-5" />
                                    @break
                                @case('file-text')
                                    <x-lucide-file-text class="w-5 h-5" />
                                    @break
                                @case('message-square')
                                    <x-lucide-message-square class="w-5 h-5" />
                                    @break
                                @default
                                    <x-lucide-bell class="w-5 h-5" />
                            @endswitch
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-medium text-neutral-800 flex items-center gap-2">
                                        <span class="truncate">{{ $notification->data['title'] ?? 'Notification' }}</span>
                                        @if(!$notification->read_at)
                                            <span class="inline-block w-2 h-2 bg-primary-500 rounded-full flex-shrink-0"></span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-neutral-600 mt-1">{{ $notification->data['message'] ?? 'No message' }}</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <p class="flex items-center gap-1.5 text-xs text-neutral-400">
                                            <x-lucide-clock class="w-3.5 h-3.5" />
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </p>
                                        
                                        @if($isClickable)
                                        <p class="flex items-center gap-1 text-xs text-primary-600">
                                            <x-lucide-mouse-pointer-click class="w-3.5 h-3.5" />
                                            Click to view details
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                                    @if(!$notification->read_at)
                                    <button onclick="markAsRead('{{ $notification->id }}')" class="p-2 text-primary-500 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors" title="Mark as read">
                                        <x-lucide-check class="w-4 h-4" />
                                    </button>
                                    @endif
                                    
                                    <button onclick="deleteNotification('{{ $notification->id }}')" class="p-2 text-error-500 hover:text-error-700 hover:bg-error-50 rounded-lg transition-colors" title="Delete">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Debug Info (only in dev) -->
                            @if(config('app.debug'))
                            <div class="mt-3 p-2 bg-neutral-50 rounded-lg text-xs text-neutral-500 border border-neutral-100">
                                <span class="font-medium">Debug:</span> Type: {{ $notification->type ?? 'Unknown' }} | 
                                Action URL: {{ $actionUrl ?? 'None' }} |
                                Read: {{ $notification->read_at ? 'Yes' : 'No' }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 px-8 text-center">
                    <div class="w-14 h-14 bg-neutral-50 rounded-2xl flex items-center justify-center mb-6">
                        <x-lucide-bell-off class="w-7 h-7 text-neutral-400" />
                    </div>
                    <h3 class="text-base font-medium text-neutral-700 mb-2">No notifications yet</h3>
                    <p class="text-sm text-neutral-500 max-w-sm">You'll see updates about your projects here</p>
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

<script>
async function handleNotificationClick(actionUrl, notificationId) {
    if (!actionUrl) return;
    
    try {
        // Mark as read first
        await fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        });
        
        // Navigate to the action URL
        window.location.href = actionUrl;
    } catch (error) {
        console.error('Error handling notification click:', error);
        // Still navigate even if marking as read fails
        window.location.href = actionUrl;
    }
}

async function markAsRead(id) {
    try {
        const response = await fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        });
        if (response.ok) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteNotification(id) {
    if (confirm('Are you sure you want to delete this notification?')) {
        try {
            const response = await fetch(`/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            });
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
}
</script>
@endsection
