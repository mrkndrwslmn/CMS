@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Notifications', 'icon' => 'bell'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-header 
            title="Notifications" 
            description="Stay updated with all system activities"
        />
        
        @if($notifications->total() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-check-check class="w-4 h-4" />
                    Mark All as Read
                </x-ui.button>
            </form>
        @endif
    </div>
    
    <!-- Notifications List -->
    <x-ui.card class="overflow-hidden">
        @forelse($notifications as $notification)
            @php
                $actionUrl = $notification->data['action_url'] ?? null;
                $isClickable = !empty($actionUrl);
                
                // Determine icon and colors based on notification type
                $iconName = 'bell';
                $iconBg = 'bg-primary-100';
                $iconColor = 'text-primary-600';
                
                if (str_contains($notification->type, 'task')) {
                    $iconName = 'list-checks';
                } elseif (str_contains($notification->type, 'project')) {
                    $iconName = 'folder-kanban';
                } elseif (str_contains($notification->type, 'payment')) {
                    $iconName = 'credit-card';
                    $iconBg = 'bg-warning-100';
                    $iconColor = 'text-warning-600';
                } elseif (str_contains($notification->type, 'request')) {
                    $iconName = 'file-text';
                } elseif (str_contains($notification->type, 'budget')) {
                    $iconName = 'banknote';
                    $iconBg = 'bg-warning-100';
                    $iconColor = 'text-warning-600';
                } elseif (str_contains($notification->type, 'message')) {
                    $iconName = 'message-square';
                }
                
                // Override colors for status-based notifications
                if (str_contains($notification->type, 'completed') || str_contains($notification->type, 'approved')) {
                    $iconBg = 'bg-success-100';
                    $iconColor = 'text-success-600';
                } elseif (str_contains($notification->type, 'rejected') || str_contains($notification->type, 'failed')) {
                    $iconBg = 'bg-error-100';
                    $iconColor = 'text-error-600';
                }
            @endphp
            
            <div class="px-6 py-4 border-b border-neutral-200 hover:bg-neutral-50 transition-colors {{ !$notification->read_at ? 'bg-primary-50/30 border-l-4 border-l-primary-600' : '' }} {{ $isClickable ? 'cursor-pointer' : '' }}"
                 @if($isClickable) onclick="handleNotificationClick('{{ $actionUrl }}', '{{ $notification->id }}')" @endif>
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-11 h-11 rounded-xl {{ $iconBg }} flex items-center justify-center">
                        <x-dynamic-component :component="'lucide-' . $iconName" class="w-5 h-5 {{ $iconColor }}" />
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-base font-semibold text-neutral-800 truncate">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h3>
                                    @if(!$notification->read_at)
                                        <span class="inline-block w-2 h-2 bg-primary-600 rounded-full flex-shrink-0"></span>
                                    @endif
                                </div>
                                <p class="text-sm text-neutral-600 mb-2">{{ $notification->data['message'] ?? 'No message' }}</p>
                                <div class="flex items-center gap-4 text-xs text-neutral-500">
                                    <span class="flex items-center gap-1.5">
                                        <x-lucide-clock class="w-3.5 h-3.5" />
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </span>
                                    @if($isClickable)
                                        <span class="flex items-center gap-1.5 text-primary-600">
                                            <x-lucide-mouse-pointer-click class="w-3.5 h-3.5" />
                                            Click to view details
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-1 flex-shrink-0" onclick="event.stopPropagation()">
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                                                title="Mark as read">
                                            <x-lucide-check class="w-4 h-4" />
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors" 
                                            title="Delete" 
                                            onclick="return confirm('Are you sure you want to delete this notification?')">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100 mb-4">
                    <x-lucide-bell-off class="w-8 h-8 text-neutral-400" />
                </div>
                <p class="text-neutral-600 text-base font-medium">No notifications yet</p>
                <p class="text-neutral-400 text-sm mt-1">You'll see updates about system activities here</p>
            </div>
        @endforelse
    </x-ui.card>
    
    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            <x-ui.pagination :paginator="$notifications" />
        </div>
    @endif
</div>

<script>
function handleNotificationClick(actionUrl, notificationId) {
    if (actionUrl) {
        // Mark as read via AJAX before navigation
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
