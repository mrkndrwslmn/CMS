@extends('layouts.admin')

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
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
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
                <div class="px-6 py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-blue-50' }}">
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
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </h3>
                                    <p class="text-gray-700 mt-1">{{ $notification->data['message'] ?? 'No message' }}</p>
                                    <p class="text-sm text-gray-500 mt-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    @if(!$notification->read_at)
                                    <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm" title="Mark as read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Action Link (if available) -->
                            @if(isset($notification->data['action_url']))
                            <a href="{{ $notification->data['action_url'] }}" 
                               class="inline-block mt-3 text-primary hover:text-secondary font-medium text-sm">
                                View Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
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
@endsection
