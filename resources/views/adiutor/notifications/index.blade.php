@extends('adiutor.layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Notifications'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Notifications</h1>
            <p class="text-sm text-neutral-500 mt-1">Stay updated with your projects and tasks</p>
        </div>
        
        @if($notifications->total() > 0)
        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors">
                <x-lucide-check-check class="w-4 h-4" />
                Mark All as Read
            </button>
        </form>
        @endif
    </div>
    
    <!-- Notifications List -->
    <x-ui.card class="overflow-hidden">
        @forelse($notifications as $notification)
            @php
                $actionUrl = $notification->data['action_url'] ?? null;
                $isClickable = !empty($actionUrl);
            @endphp
            
            <div class="px-6 py-4 border-b border-neutral-100 hover:bg-neutral-50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-primary-50/50' }} {{ $isClickable ? 'cursor-pointer' : '' }}"
                 @if($isClickable) onclick="handleNotificationClick('{{ $actionUrl }}', '{{ $notification->id }}')" @endif>
                <div class="flex items-start gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center 
                        @if(str_contains($notification->type, 'completed') || str_contains($notification->type, 'approved'))
                            bg-success-100
                        @elseif(str_contains($notification->type, 'rejected') || str_contains($notification->type, 'failed'))
                            bg-error-100
                        @elseif(str_contains($notification->type, 'payment'))
                            bg-warning-100
                        @else
                            bg-primary-100
                        @endif">
                        @if(str_contains($notification->type, 'task'))
                            <x-lucide-check-square class="w-5 h-5 {{ str_contains($notification->type, 'completed') ? 'text-success-600' : 'text-primary-600' }}" />
                        @elseif(str_contains($notification->type, 'project'))
                            <x-lucide-folder-kanban class="w-5 h-5 {{ str_contains($notification->type, 'completed') ? 'text-success-600' : 'text-primary-600' }}" />
                        @elseif(str_contains($notification->type, 'payment'))
                            <x-lucide-banknote class="w-5 h-5 text-warning-600" />
                        @elseif(str_contains($notification->type, 'request'))
                            <x-lucide-file-text class="w-5 h-5 {{ str_contains($notification->type, 'approved') ? 'text-success-600' : (str_contains($notification->type, 'rejected') ? 'text-error-600' : 'text-primary-600') }}" />
                        @elseif(str_contains($notification->type, 'budget'))
                            <x-lucide-wallet class="w-5 h-5 text-warning-600" />
                        @else
                            <x-lucide-bell class="w-5 h-5 text-primary-600" />
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-4">
                            <div class="{{ $isClickable ? 'flex-1' : '' }}">
                                <h3 class="text-base font-semibold text-neutral-800 flex items-center gap-2">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                    @if(!$notification->read_at)
                                        <span class="inline-block w-2 h-2 bg-primary-500 rounded-full"></span>
                                    @endif
                                </h3>
                                <p class="text-sm text-neutral-600 mt-1">{{ $notification->data['message'] ?? 'No message' }}</p>
                                <p class="flex items-center gap-1.5 text-sm text-neutral-400 mt-2">
                                    <x-lucide-clock class="w-3.5 h-3.5" />
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </p>
                                
                                @if($isClickable)
                                <p class="flex items-center gap-1.5 text-xs text-primary-600 mt-2">
                                    <x-lucide-mouse-pointer-click class="w-3.5 h-3.5" />
                                    Click to view details
                                </p>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                                @if(!$notification->read_at)
                                <button onclick="markAsRead('{{ $notification->id }}')" class="p-2 text-primary-500 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors" title="Mark as read">
                                    <x-lucide-check class="w-4 h-4" />
                                </button>
                                @endif
                                
                                <button onclick="deleteNotification('{{ $notification->id }}')" class="p-2 text-neutral-400 hover:text-error-600 hover:bg-error-50 rounded-lg transition-colors" title="Delete">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        
                        <!-- Debug Info (only in dev) -->
                        @if(config('app.debug'))
                        <div class="mt-3 p-2 bg-neutral-100 rounded-lg text-xs text-neutral-500">
                            <strong>Debug:</strong> Type: {{ $notification->type ?? 'Unknown' }} | 
                            Action URL: {{ $actionUrl ?? 'None' }} |
                            Read: {{ $notification->read_at ? 'Yes' : 'No' }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="flex justify-center mb-4">
                    <div class="p-4 bg-neutral-100 rounded-full">
                        <x-lucide-bell-off class="w-10 h-10 text-neutral-400" />
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-neutral-800 mb-1">No notifications yet</h3>
                <p class="text-sm text-neutral-500">You'll see updates about your projects here</p>
            </div>
        @endforelse
    </x-ui.card>
    
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
    const confirmed = await window.Alerts.confirm({
        title: 'Delete Notification',
        message: 'Are you sure you want to delete this notification?',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        variant: 'danger'
    });
    if (confirmed) {
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
