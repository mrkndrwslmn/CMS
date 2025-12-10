@extends('admin.layouts.app')

@section('title', 'User Details' . (isset($user) && is_object($user) ? ' - ' . $user->fullName : ''))
@section('page-title', 'User Management')

@section('content')
<div class="p-6 lg:p-8" data-user-id="{{ isset($user) && is_object($user) ? $user->id : 0 }}">
    @if(!isset($user) || !is_object($user))
        <x-ui.card class="border-l-4 border-error-500">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-error-50 rounded-xl">
                        <x-lucide-alert-circle class="w-6 h-6 text-error-500" />
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-neutral-800">User Not Found</h4>
                        <p class="text-neutral-600 mt-1">The requested user could not be found.</p>
                        <a href="{{ route('admin.users.index') }}" class="mt-4 inline-block">
                            <x-ui.button variant="secondary" icon="arrow-left">
                                Back to Users
                            </x-ui.button>
                        </a>
                    </div>
                </div>
            </div>
        </x-ui.card>
    @else
    
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'users'],
        ['label' => $user->fullName, 'icon' => 'user'],
    ]" class="mb-6" />

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            @if($user->profilePic)
                <img src="{{ $user->profilePic }}" alt="{{ $user->fullName }}" class="h-16 w-16 rounded-full object-cover">
            @else
                <div class="bg-primary-100 h-16 w-16 rounded-full flex items-center justify-center">
                    <x-lucide-user class="w-8 h-8 text-primary-600" />
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">{{ $user->fullName }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    <div class="flex items-center gap-1.5 text-neutral-500">
                        <x-lucide-mail class="w-4 h-4" />
                        <span class="text-sm">{{ $user->email }}</span>
                    </div>
                    @if($user->role == 'admin')
                        <x-ui.badge type="secondary">
                            <x-lucide-shield class="w-3 h-3 mr-1" />
                            Admin
                        </x-ui.badge>
                    @elseif($user->role == 'adiutor')
                        <x-ui.badge type="info">
                            <x-lucide-user-check class="w-3 h-3 mr-1" />
                            Adiutor
                        </x-ui.badge>
                    @else
                        <x-ui.badge type="accent">
                            <x-lucide-briefcase class="w-3 h-3 mr-1" />
                            Client
                        </x-ui.badge>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.edit', $user->id) }}">
                <x-ui.button icon="pencil">
                    Edit User
                </x-ui.button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">User Profile</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-5">
                        <div>
                            <div class="text-sm font-medium text-neutral-500 mb-1.5">Status</div>
                            @if($user->status == 'active')
                                <x-ui.badge type="success">
                                    <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                    Active
                                </x-ui.badge>
                            @elseif($user->status == 'inactive')
                                <x-ui.badge type="warning">
                                    <span class="w-1.5 h-1.5 bg-warning-500 rounded-full mr-1.5"></span>
                                    Inactive
                                </x-ui.badge>
                            @else
                                <x-ui.badge type="error">
                                    <span class="w-1.5 h-1.5 bg-error-500 rounded-full mr-1.5"></span>
                                    {{ ucfirst($user->status ?? 'Unknown') }}
                                </x-ui.badge>
                            @endif
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-user class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Full Name</div>
                                <div class="text-neutral-800">{{ $user->fullName }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-mail class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Email Address</div>
                                <div class="text-neutral-800 break-all">{{ $user->email }}</div>
                                @if($user->email_verified_at)
                                    <div class="flex items-center gap-1 text-success-600 text-xs mt-1">
                                        <x-lucide-check-circle class="w-3 h-3" />
                                        <span>Verified {{ $user->email_verified_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1 text-warning-600 text-xs mt-1">
                                        <x-lucide-alert-circle class="w-3 h-3" />
                                        <span>Not Verified</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-phone class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Phone Number</div>
                                <div class="text-neutral-800">{{ $user->phoneNumber ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-shield class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Role</div>
                                <div class="text-neutral-800 capitalize">{{ $user->role }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-key class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Auth Provider</div>
                                <div class="text-neutral-800 capitalize">{{ $user->auth_provider ?? 'Local' }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-calendar class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Member Since</div>
                                <div class="text-neutral-800">{{ $user->created_at->format('F d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 border-t border-neutral-100 pt-4">
                            <x-lucide-clock class="w-5 h-5 text-neutral-400 mt-0.5" />
                            <div>
                                <div class="text-sm font-medium text-neutral-500">Last Updated</div>
                                <div class="text-neutral-800">{{ $user->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
            
            <!-- Earnings & Referrals Card (for adiutors/clients) -->
            @if($user->role == 'adiutor' || $user->role == 'client')
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-wallet class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Earnings & Referrals</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($user->role == 'adiutor')
                        <div class="bg-neutral-50 rounded-xl p-4">
                            <div class="text-sm font-medium text-neutral-500 mb-2">Work Earnings</div>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <div class="text-lg font-semibold text-neutral-800">₱{{ number_format($user->work_earnings_balance ?? 0, 2) }}</div>
                                    <div class="text-xs text-neutral-500">Balance</div>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-warning-600">₱{{ number_format($user->work_earnings_pending ?? 0, 2) }}</div>
                                    <div class="text-xs text-neutral-500">Pending</div>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-success-600">₱{{ number_format($user->work_earnings_withdrawn ?? 0, 2) }}</div>
                                    <div class="text-xs text-neutral-500">Withdrawn</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="bg-neutral-50 rounded-xl p-4">
                            <div class="text-sm font-medium text-neutral-500 mb-2">Referral Credits</div>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <div class="text-lg font-semibold text-neutral-800">₱{{ number_format($user->referral_credits ?? 0, 2) }}</div>
                                    <div class="text-xs text-neutral-500">Balance</div>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-warning-600">₱{{ number_format($user->referral_credits_pending ?? 0, 2) }}</div>
                                    <div class="text-xs text-neutral-500">Pending</div>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-success-600">₱{{ number_format($user->referral_credits_withdrawn ?? 0, 2) }}</div>
                                    <div class="text-xs text-neutral-500">Withdrawn</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($user->referred_by_code)
                        <div class="flex items-center gap-2 text-sm text-neutral-600 pt-2">
                            <x-lucide-link class="w-4 h-4" />
                            <span>Referred by: <span class="font-medium">{{ $user->referred_by_code }}</span></span>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>
            @endif
            
            <!-- Quick Actions Card -->
            <x-ui.card class="mb-6">
                <div class="px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-zap class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Quick Actions</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-neutral-50 hover:bg-primary-50 text-neutral-700 hover:text-primary-700 rounded-xl transition-colors group">
                            <span class="flex items-center gap-3">
                                <x-lucide-pencil class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                                <span>Edit User</span>
                            </span>
                            <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500" />
                        </a>
                        
                        @if($user->status == 'active')
                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="flex items-center justify-between w-full px-4 py-3 bg-neutral-50 hover:bg-warning-50 text-neutral-700 hover:text-warning-700 rounded-xl transition-colors group">
                                <span class="flex items-center gap-3">
                                    <x-lucide-pause-circle class="w-5 h-5 text-neutral-400 group-hover:text-warning-500" />
                                    <span>Deactivate User</span>
                                </span>
                                <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-warning-500" />
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="flex items-center justify-between w-full px-4 py-3 bg-neutral-50 hover:bg-success-50 text-neutral-700 hover:text-success-700 rounded-xl transition-colors group">
                                <span class="flex items-center gap-3">
                                    <x-lucide-check-circle class="w-5 h-5 text-neutral-400 group-hover:text-success-500" />
                                    <span>Activate User</span>
                                </span>
                                <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-success-500" />
                            </button>
                        </form>
                        @endif
                        
                        <a href="#" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-neutral-50 hover:bg-primary-50 text-neutral-700 hover:text-primary-700 rounded-xl transition-colors group">
                            <span class="flex items-center gap-3">
                                <x-lucide-mail class="w-5 h-5 text-neutral-400 group-hover:text-primary-500" />
                                <span>Send Message</span>
                            </span>
                            <x-lucide-chevron-right class="w-4 h-4 text-neutral-400 group-hover:text-primary-500" />
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>
        
        <!-- Main Content Area -->
        <div class="lg:col-span-2">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <x-ui.stat-card 
                    label="Total Tasks" 
                    :value="$stats['total_tasks'] ?? 0" 
                    icon="clipboard-list"
                />
                <x-ui.stat-card 
                    label="Active Tasks" 
                    :value="$stats['active_tasks'] ?? 0" 
                    icon="clock"
                    iconBg="warning"
                />
                <x-ui.stat-card 
                    label="Completed" 
                    :value="$stats['completed_tasks'] ?? 0" 
                    icon="check-circle"
                    iconBg="success"
                />
                <x-ui.stat-card 
                    label="Feedback" 
                    :value="$stats['total_feedback'] ?? 0" 
                    icon="message-square"
                    iconBg="primary"
                />
            </div>

            <!-- Recent Tasks -->
            <x-ui.card class="mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-clipboard-list class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Recent Tasks</h2>
                    </div>
                    @if($user->tasks && $user->tasks->count() > 0)
                    <a href="{{ route('admin.tasks.index', ['client_id' => $user->id]) }}" class="text-sm text-primary-600 hover:text-primary-700 flex items-center gap-1 transition-colors">
                        <span>View All</span>
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                    @endif
                </div>
                <div class="p-6">
                    @if($user->tasks && $user->tasks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-neutral-50 border-b border-neutral-100">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-l-lg">Task</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-r-lg">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($user->tasks->take(5) as $task)
                                    <tr class="hover:bg-neutral-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.tasks.show', $task->taskID) }}" class="font-medium text-neutral-800 hover:text-primary-600">
                                                {{ $task->taskTitle ?? 'Task #' . $task->taskID }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($task->status == 'completed')
                                                <x-ui.badge type="success">Completed</x-ui.badge>
                                            @elseif($task->status == 'in_progress')
                                                <x-ui.badge type="warning">In Progress</x-ui.badge>
                                            @elseif($task->status == 'pending')
                                                <x-ui.badge type="neutral">Pending</x-ui.badge>
                                            @else
                                                <x-ui.badge type="neutral">{{ ucfirst($task->status ?? 'Unknown') }}</x-ui.badge>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600 text-sm">
                                            @if($task->deadline)
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y') }}
                                            @else
                                                <span class="text-neutral-400">Not set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="clipboard-list"
                            title="No tasks found"
                            description="Tasks associated with this user will appear here"
                        />
                    @endif
                </div>
            </x-ui.card>

            <!-- Service Requests -->
            <x-ui.card class="mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-file-text class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Service Requests</h2>
                    </div>
                    @if($user->serviceRequests && $user->serviceRequests->count() > 0)
                    <a href="{{ route('admin.requests.index', ['client_id' => $user->id]) }}" class="text-sm text-primary-600 hover:text-primary-700 flex items-center gap-1 transition-colors">
                        <span>View All</span>
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                    @endif
                </div>
                <div class="p-6">
                    @if($user->serviceRequests && $user->serviceRequests->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-neutral-50 border-b border-neutral-100">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-l-lg">Request</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider rounded-r-lg">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($user->serviceRequests->take(5) as $request)
                                    <tr class="hover:bg-neutral-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.requests.show', $request->id) }}" class="font-medium text-neutral-800 hover:text-primary-600">
                                                {{ $request->service->name ?? 'Request #' . $request->id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($request->status == 'completed')
                                                <x-ui.badge type="success">Completed</x-ui.badge>
                                            @elseif($request->status == 'in_progress')
                                                <x-ui.badge type="warning">In Progress</x-ui.badge>
                                            @elseif($request->status == 'pending')
                                                <x-ui.badge type="info">Pending</x-ui.badge>
                                            @else
                                                <x-ui.badge type="neutral">{{ ucfirst($request->status ?? 'Unknown') }}</x-ui.badge>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-neutral-600 text-sm">
                                            {{ $request->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="file-text"
                            title="No service requests found"
                            description="Service requests from this user will appear here"
                        />
                    @endif
                </div>
            </x-ui.card>

            <!-- Recent Feedback -->
            <x-ui.card class="mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-message-square class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Recent Feedback</h2>
                    </div>
                    @if($user->feedbacks && $user->feedbacks->count() > 0)
                    <a href="{{ route('admin.feedback.index', ['client_id' => $user->id]) }}" class="text-sm text-primary-600 hover:text-primary-700 flex items-center gap-1 transition-colors">
                        <span>View All</span>
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                    @endif
                </div>
                <div class="p-6">
                    @if($user->feedbacks && $user->feedbacks->count() > 0)
                        <div class="space-y-4">
                            @foreach($user->feedbacks->take(3) as $feedback)
                            <div class="bg-neutral-50 rounded-xl p-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= ($feedback->rating ?? 0))
                                                <x-lucide-star class="w-4 h-4 text-warning-500 fill-warning-500" />
                                            @else
                                                <x-lucide-star class="w-4 h-4 text-neutral-300" />
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-neutral-500">{{ $feedback->created_at->diffForHumans() }}</span>
                                </div>
                                @if($feedback->comment)
                                <p class="text-sm text-neutral-700">{{ Str::limit($feedback->comment, 150) }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="message-square"
                            title="No feedback found"
                            description="Feedback from this user will appear here"
                        />
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>
    
    @endif
</div>
@endsection
