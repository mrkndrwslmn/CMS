@extends('admin.layouts.app')

@section('title', 'Adiutor Details' . (isset($adiutor) && is_object($adiutor) ? ' - ' . $adiutor->fullName : ''))
@section('page-title', 'Adiutor Management')

@section('content')
<div class="p-6 lg:p-8" x-data="{ adjustBalanceModal: false, adjustmentType: 'add', adjustmentAmount: '', adjustmentReason: '' }">
    @if(!isset($adiutor) || !is_object($adiutor))
        <x-ui.card class="border-l-4 border-error-500">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-error-50 rounded-xl">
                        <x-lucide-alert-circle class="w-6 h-6 text-error-500" />
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-neutral-800">Adiutor Not Found</h4>
                        <p class="text-neutral-600 mt-1">The requested adiutor could not be found.</p>
                        <a href="{{ route('admin.adiutors.index') }}" class="mt-4 inline-block">
                            <x-ui.button variant="secondary" icon="arrow-left">
                                Back to Adiutors
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
        ['label' => 'Adiutors', 'route' => 'admin.adiutors.index', 'icon' => 'hard-hat'],
        ['label' => $adiutor->fullName, 'icon' => 'user'],
    ]" class="mb-6" />

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="bg-violet-100 h-16 w-16 rounded-full flex items-center justify-center">
                <x-lucide-hard-hat class="w-8 h-8 text-violet-600" />
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">{{ $adiutor->fullName }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    <div class="flex items-center gap-1.5 text-neutral-500">
                        <x-lucide-mail class="w-4 h-4" />
                        <span class="text-sm">{{ $adiutor->email }}</span>
                    </div>
                    @if($adiutor->adiutorProfile && $adiutor->adiutorProfile->title)
                        <span class="text-neutral-300">|</span>
                        <span class="text-sm text-neutral-600">{{ $adiutor->adiutorProfile->title }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.adiutors.edit', $adiutor->id) }}">
                <x-ui.button icon="pencil">
                    Edit Adiutor
                </x-ui.button>
            </a>
            <a href="{{ route('admin.adiutors.index') }}">
                <x-ui.button variant="secondary" icon="arrow-left">
                    Back to List
                </x-ui.button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Area -->
        <div class="lg:col-span-2">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <x-ui.stat-card 
                    label="Total Projects" 
                    :value="$stats['total_projects']" 
                    icon="folder-kanban"
                />
                <x-ui.stat-card 
                    label="Completed" 
                    :value="$stats['completed_projects']" 
                    icon="check-circle"
                    iconBg="success"
                />
                <x-ui.stat-card 
                    label="Hours Worked" 
                    :value="number_format($stats['total_hours'], 1)" 
                    icon="clock"
                    iconBg="primary"
                />
                <x-ui.stat-card 
                    label="Total Earned" 
                    value="₱{{ number_format($stats['total_earnings'], 2) }}" 
                    icon="wallet"
                    iconBg="warning"
                />
            </div>

            <!-- Recent Projects -->
            <x-ui.card class="mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-folder-kanban class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Assigned Projects</h2>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentProjects->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentProjects as $assignment)
                                <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-lg">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                            <x-lucide-folder class="w-5 h-5 text-primary-600" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-neutral-800">
                                                @if($assignment->project)
                                                    {{ $assignment->project->name }}
                                                @else
                                                    Unknown Project
                                                @endif
                                            </h4>
                                            <p class="text-xs text-neutral-500">
                                                {{ $assignment->role ?? 'Team Member' }} • Assigned {{ $assignment->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($assignment->project)
                                            @if($assignment->project->status == 'completed')
                                                <x-ui.badge type="success">Completed</x-ui.badge>
                                            @elseif($assignment->project->status == 'in_progress')
                                                <x-ui.badge type="warning">In Progress</x-ui.badge>
                                            @else
                                                <x-ui.badge type="neutral">{{ ucfirst($assignment->project->status) }}</x-ui.badge>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="folder"
                            title="No projects yet"
                            description="This adiutor hasn't been assigned to any projects."
                        />
                    @endif
                </div>
            </x-ui.card>

            <!-- Recent Time Entries -->
            <x-ui.card class="mb-6">
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-clock class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Recent Time Entries</h2>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentTimeEntries->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-xs font-medium text-neutral-500 uppercase">
                                        <th class="pb-3">Date</th>
                                        <th class="pb-3">Project</th>
                                        <th class="pb-3">Hours</th>
                                        <th class="pb-3">Earnings</th>
                                        <th class="pb-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($recentTimeEntries as $entry)
                                        <tr>
                                            <td class="py-3 text-sm text-neutral-700">
                                                {{ $entry->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="py-3 text-sm text-neutral-700">
                                                {{ $entry->project ? $entry->project->name : 'N/A' }}
                                            </td>
                                            <td class="py-3 text-sm text-neutral-700">
                                                {{ number_format($entry->hours_worked ?? 0, 2) }}h
                                            </td>
                                            <td class="py-3 text-sm font-medium text-neutral-800">
                                                ₱{{ number_format($entry->earnings ?? 0, 2) }}
                                            </td>
                                            <td class="py-3">
                                                @if($entry->is_approved)
                                                    <x-ui.badge type="success">Approved</x-ui.badge>
                                                @else
                                                    <x-ui.badge type="warning">Pending</x-ui.badge>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="clock"
                            title="No time entries"
                            description="No time entries have been recorded yet."
                        />
                    @endif
                </div>
            </x-ui.card>

            <!-- Wallet Transactions -->
            <x-ui.card>
                <div class="flex justify-between items-center px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-2">
                        <x-lucide-receipt class="w-5 h-5 text-neutral-400" />
                        <h2 class="text-lg font-medium text-neutral-700">Recent Transactions</h2>
                    </div>
                </div>
                <div class="p-6">
                    @if($walletTransactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($walletTransactions as $transaction)
                                <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        @if($transaction->type == 'credit' || $transaction->type == 'work_earned')
                                            <div class="w-8 h-8 rounded-full bg-success-100 flex items-center justify-center">
                                                <x-lucide-arrow-down-left class="w-4 h-4 text-success-600" />
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-error-100 flex items-center justify-center">
                                                <x-lucide-arrow-up-right class="w-4 h-4 text-error-600" />
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-neutral-800">{{ $transaction->description ?? ucfirst(str_replace('_', ' ', $transaction->type)) }}</p>
                                            <p class="text-xs text-neutral-500">{{ $transaction->created_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold {{ $transaction->type == 'credit' || $transaction->type == 'work_earned' ? 'text-success-600' : 'text-error-600' }}">
                                        {{ $transaction->type == 'credit' || $transaction->type == 'work_earned' ? '+' : '-' }}₱{{ number_format(abs($transaction->amount), 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-ui.empty-state 
                            icon="receipt"
                            title="No transactions"
                            description="No wallet transactions have been recorded."
                        />
                    @endif
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Quick Actions -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-base font-medium text-neutral-700">Status & Actions</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-neutral-600">Account Status</span>
                        @if($adiutor->status == 'active')
                            <x-ui.badge type="success">
                                <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                Active
                            </x-ui.badge>
                        @elseif($adiutor->status == 'inactive')
                            <x-ui.badge type="warning">
                                <span class="w-1.5 h-1.5 bg-warning-500 rounded-full mr-1.5"></span>
                                Inactive
                            </x-ui.badge>
                        @else
                            <x-ui.badge type="error">
                                <span class="w-1.5 h-1.5 bg-error-500 rounded-full mr-1.5"></span>
                                Banned
                            </x-ui.badge>
                        @endif
                    </div>
                    
                    <div class="space-y-2">
                        <button @click="adjustBalanceModal = true" class="w-full">
                            <x-ui.button variant="secondary" icon="wallet" class="w-full justify-center">
                                Adjust Balance
                            </x-ui.button>
                        </button>
                    </div>
                </div>
            </x-ui.card>

            <!-- Wallet Balance -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-base font-medium text-neutral-700">Wallet Balance</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <p class="text-3xl font-bold text-neutral-800">₱{{ number_format($adiutor->work_earnings_balance ?? 0, 2) }}</p>
                        <p class="text-sm text-neutral-500 mt-1">Work Earnings</p>
                    </div>
                    @if(($adiutor->referral_credits ?? 0) > 0)
                        <div class="text-center pt-4 border-t border-neutral-100">
                            <p class="text-lg font-semibold text-primary-600">₱{{ number_format($adiutor->referral_credits, 2) }}</p>
                            <p class="text-xs text-neutral-500">Referral Credits</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Contact Information -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-base font-medium text-neutral-700">Contact Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center">
                            <x-lucide-mail class="w-4 h-4 text-neutral-500" />
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500">Email</p>
                            <p class="text-sm text-neutral-800">{{ $adiutor->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center">
                            <x-lucide-phone class="w-4 h-4 text-neutral-500" />
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500">Phone</p>
                            <p class="text-sm text-neutral-800">{{ $adiutor->phoneNumber ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center">
                            <x-lucide-calendar class="w-4 h-4 text-neutral-500" />
                        </div>
                        <div>
                            <p class="text-xs text-neutral-500">Member Since</p>
                            <p class="text-sm text-neutral-800">{{ $adiutor->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Profile Information -->
            @if($adiutor->adiutorProfile)
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-base font-medium text-neutral-700">Profile Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($adiutor->adiutorProfile->title)
                        <div>
                            <p class="text-xs text-neutral-500">Title</p>
                            <p class="text-sm text-neutral-800">{{ $adiutor->adiutorProfile->title }}</p>
                        </div>
                    @endif
                    @if($adiutor->adiutorProfile->standard_hourly_rate)
                        <div>
                            <p class="text-xs text-neutral-500">Hourly Rate</p>
                            <p class="text-sm text-neutral-800">₱{{ number_format($adiutor->adiutorProfile->standard_hourly_rate, 2) }}/hr</p>
                        </div>
                    @endif
                    @if($adiutor->adiutorProfile->bio)
                        <div>
                            <p class="text-xs text-neutral-500">Bio</p>
                            <p class="text-sm text-neutral-800">{{ $adiutor->adiutorProfile->bio }}</p>
                        </div>
                    @endif
                    @if($adiutor->adiutorProfile->skills && $adiutor->adiutorProfile->skills->count() > 0)
                        <div>
                            <p class="text-xs text-neutral-500 mb-2">Skills</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($adiutor->adiutorProfile->skills as $skill)
                                    <x-ui.badge type="primary">{{ $skill->name }}</x-ui.badge>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </x-ui.card>
            @endif

            <!-- Feedback Summary -->
            @if($feedback->count() > 0)
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100">
                    <h3 class="text-base font-medium text-neutral-700">Client Feedback</h3>
                </div>
                <div class="p-6">
                    <div class="text-center mb-4">
                        <p class="text-3xl font-bold text-neutral-800">{{ number_format($stats['average_rating'] ?? 0, 1) }}</p>
                        <div class="flex justify-center gap-0.5 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($stats['average_rating'] ?? 0))
                                    <x-lucide-star class="w-4 h-4 text-yellow-400 fill-current" />
                                @else
                                    <x-lucide-star class="w-4 h-4 text-neutral-300" />
                                @endif
                            @endfor
                        </div>
                        <p class="text-xs text-neutral-500 mt-1">Based on {{ $feedback->count() }} reviews</p>
                    </div>
                </div>
            </x-ui.card>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Adjust Balance Modal -->
<div x-show="adjustBalanceModal" 
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm transition-opacity" 
         @click="adjustBalanceModal = false"></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all"
             x-show="adjustBalanceModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="adjustBalanceModal = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-neutral-800">Adjust Wallet Balance</h3>
                <button @click="adjustBalanceModal = false" class="text-neutral-400 hover:text-neutral-600">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
            
            <!-- Form -->
            <form action="{{ route('admin.adiutors.adjust-balance', $adiutor->id ?? 0) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <!-- Adjustment Type -->
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Adjustment Type</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="add" x-model="adjustmentType" class="text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Add Funds</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="deduct" x-model="adjustmentType" class="text-primary-600 focus:ring-primary-500">
                                <span class="ml-2 text-sm text-neutral-700">Deduct Funds</span>
                            </label>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-neutral-700 mb-1.5">Amount (₱)</label>
                        <x-ui.input 
                            type="number" 
                            name="amount" 
                            id="amount" 
                            placeholder="0.00"
                            step="0.01"
                            min="0.01"
                            required
                            x-model="adjustmentAmount"
                        />
                    </div>

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-neutral-700 mb-1.5">Reason</label>
                        <textarea 
                            name="reason" 
                            id="reason" 
                            rows="3"
                            class="w-full rounded-lg border-neutral-200 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter reason for this adjustment..."
                            required
                            x-model="adjustmentReason"
                        ></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 justify-end mt-6 pt-4 border-t border-neutral-100">
                    <x-ui.button type="button" variant="secondary" @click="adjustBalanceModal = false">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" icon="check">
                        Confirm Adjustment
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
