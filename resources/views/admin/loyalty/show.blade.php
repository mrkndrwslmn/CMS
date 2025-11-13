@extends('layouts.admin')

@section('title', 'Loyalty Profile - ' . $user->name)

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-800 mb-2">{{ $user->name }}'s Loyalty Profile</h1>
            <p class="text-neutral-600">{{ $user->email }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.loyalty.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <button onclick="openAdjustPointsModal()" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Adjust Points
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Points Overview -->
            <div class="glass-card p-8 bg-gradient-to-br from-primary-50 via-white to-success-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500 opacity-5 rounded-full -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Available Points -->
                        <div class="text-center">
                            <p class="text-sm text-neutral-600 mb-2">Available Points</p>
                            <p class="text-5xl font-bold text-primary-600 mb-2">{{ number_format($loyaltyPoint->available_points) }}</p>
                            <p class="text-sm text-neutral-500">Current Balance</p>
                        </div>

                        <!-- Current Tier -->
                        <div class="text-center border-x border-neutral-200">
                            <p class="text-sm text-neutral-600 mb-2">Current Tier</p>
                            <div class="inline-flex items-center justify-center px-6 py-3 rounded-full text-2xl font-bold
                                {{ $loyaltyPoint->tier === 'platinum' ? 'bg-info-100 text-info-700' : '' }}
                                {{ $loyaltyPoint->tier === 'gold' ? 'bg-warning-100 text-warning-700' : '' }}
                                {{ $loyaltyPoint->tier === 'silver' ? 'bg-neutral-200 text-neutral-700' : '' }}
                                {{ $loyaltyPoint->tier === 'bronze' ? 'bg-orange-100 text-orange-700' : '' }}">
                                <i class="fas fa-medal mr-2"></i>{{ ucfirst($loyaltyPoint->tier) }}
                            </div>
                        </div>

                        <!-- Lifetime Stats -->
                        <div class="text-center">
                            <p class="text-sm text-neutral-600 mb-2">Lifetime Earned</p>
                            <p class="text-5xl font-bold text-success-600 mb-2">{{ number_format($loyaltyPoint->lifetime_earned) }}</p>
                            <p class="text-sm text-neutral-500">{{ number_format($loyaltyPoint->lifetime_redeemed) }} redeemed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Total Earned</p>
                        <i class="fas fa-arrow-up text-success-500"></i>
                    </div>
                    <p class="text-3xl font-bold text-success-600">{{ number_format($stats['total_earned']) }}</p>
                </div>

                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Total Redeemed</p>
                        <i class="fas fa-arrow-down text-warning-500"></i>
                    </div>
                    <p class="text-3xl font-bold text-warning-600">{{ number_format($stats['total_redeemed']) }}</p>
                </div>

                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Expired</p>
                        <i class="fas fa-clock text-error-500"></i>
                    </div>
                    <p class="text-3xl font-bold text-error-600">{{ number_format($stats['total_expired']) }}</p>
                </div>

                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Expiring Soon</p>
                        <i class="fas fa-exclamation-triangle text-warning-500"></i>
                    </div>
                    <p class="text-3xl font-bold text-warning-600">{{ number_format($stats['expiring_soon']) }}</p>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-neutral-800">
                        <i class="fas fa-history text-primary-600 mr-2"></i>Recent Transactions
                    </h3>
                    <a href="{{ route('admin.loyalty.user-transactions', $user) }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($recentTransactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th class="text-right">Points</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                            <tr>
                                <td class="text-sm text-neutral-600">{{ $transaction->created_at->format('M d, Y g:i A') }}</td>
                                <td>
                                    <span class="status-badge
                                        {{ $transaction->transaction_type === 'earned' ? 'status-completed' : '' }}
                                        {{ $transaction->transaction_type === 'redeemed' ? 'status-pending' : '' }}
                                        {{ $transaction->transaction_type === 'expired' ? 'status-cancelled' : '' }}
                                        {{ $transaction->transaction_type === 'adjusted' ? 'bg-info-100 text-info-700' : '' }}">
                                        {{ ucfirst($transaction->transaction_type) }}
                                    </span>
                                </td>
                                <td class="text-sm text-neutral-700">{{ $transaction->description }}</td>
                                <td class="text-right">
                                    <span class="text-lg font-bold
                                        {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                    </span>
                                </td>
                                <td class="text-right text-sm font-semibold text-neutral-700">
                                    {{ number_format($transaction->balance_after) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-neutral-500 py-8">No transactions yet</p>
                @endif
            </div>

            <!-- Points Expiring Soon -->
            @if($expiringPoints->count() > 0)
            <div class="glass-card p-6 border-2 border-warning-200 bg-warning-50">
                <h3 class="text-lg font-bold text-warning-800 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Points Expiring Soon
                </h3>

                <div class="space-y-3">
                    @foreach($expiringPoints as $expiring)
                    <div class="bg-white rounded-lg p-4 border border-warning-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-lg font-bold text-warning-700">{{ number_format($expiring->points) }} points</p>
                                <p class="text-xs text-neutral-600 mt-1">{{ $expiring->description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-warning-600">{{ $expiring->expires_at->format('M d, Y') }}</p>
                                <p class="text-xs text-neutral-500">{{ $expiring->expires_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- User Info -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-user text-primary-600 mr-2"></i>User Information
                </h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Name</p>
                        <p class="text-sm font-semibold text-neutral-800">{{ $user->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Email</p>
                        <p class="text-sm text-neutral-700">{{ $user->email }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Member Since</p>
                        <p class="text-sm text-neutral-700">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>

                    <div class="border-t border-neutral-200 pt-4">
                        <p class="text-xs text-neutral-500 mb-1">Last Activity</p>
                        <p class="text-sm text-neutral-700">
                            @if($loyaltyPoint->updated_at)
                                {{ $loyaltyPoint->updated_at->diffForHumans() }}
                            @else
                                No activity
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tier Progress -->
            @if($stats['next_tier'])
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-arrow-up text-success-600 mr-2"></i>Tier Progress
                </h3>

                <div class="mb-4">
                    <div class="flex justify-between text-sm text-neutral-600 mb-2">
                        <span>{{ ucfirst($loyaltyPoint->tier) }}</span>
                        <span>{{ ucfirst($stats['next_tier']) }}</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-primary-500 to-success-500 h-3 rounded-full transition-all" 
                             style="width: {{ $stats['tier_progress'] }}%">
                        </div>
                    </div>
                    <p class="text-xs text-neutral-600 mt-2">{{ number_format($stats['points_to_next_tier']) }} more points needed</p>
                </div>
            </div>
            @endif

            <!-- Tier Benefits -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-gift text-success-600 mr-2"></i>Current Benefits
                </h3>

                <div class="space-y-3">
                    @foreach($currentTierBenefits['benefits'] as $benefit)
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-success-500 mt-1 mr-3"></i>
                        <span class="text-sm text-neutral-700">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-4">
                    <i class="fas fa-bolt text-warning-600 mr-2"></i>Quick Actions
                </h3>

                <div class="space-y-3">
                    <button onclick="openAdjustPointsModal()" class="w-full btn-primary justify-center">
                        <i class="fas fa-edit mr-2"></i>Adjust Points
                    </button>

                    <a href="{{ route('admin.loyalty.user-transactions', $user) }}" 
                       class="block w-full btn-secondary text-center">
                        <i class="fas fa-list mr-2"></i>View All Transactions
                    </a>

                    <a href="{{ route('admin.loyalty.export-user', $user) }}" 
                       class="block w-full btn-secondary text-center">
                        <i class="fas fa-download mr-2"></i>Export Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adjust Points Modal -->
<div id="adjustPointsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-neutral-800">Adjust Points</h3>
            <button onclick="closeAdjustPointsModal()" class="text-neutral-400 hover:text-neutral-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form action="{{ route('admin.loyalty.adjust-points', $user) }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Current Balance -->
                <div class="bg-neutral-100 rounded-lg p-4 text-center">
                    <p class="text-sm text-neutral-600 mb-1">Current Balance</p>
                    <p class="text-3xl font-bold text-primary-600">{{ number_format($loyaltyPoint->available_points) }}</p>
                </div>

                <!-- Action Type -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Action</label>
                    <select name="action" id="adjustAction" class="form-select" required onchange="updateAdjustmentLabel()">
                        <option value="add">Add Points</option>
                        <option value="deduct">Deduct Points</option>
                        <option value="set">Set Points To</option>
                    </select>
                </div>

                <!-- Points -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2" id="pointsLabel">Points to Add</label>
                    <input type="number" name="points" class="form-input" min="0" required placeholder="Enter amount">
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-2">Reason</label>
                    <textarea name="reason" rows="3" class="form-input" required placeholder="Explain why you're adjusting the points"></textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeAdjustPointsModal()" class="btn-secondary flex-1">
                    Cancel
                </button>
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-save mr-2"></i>Apply
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAdjustPointsModal() {
        document.getElementById('adjustPointsModal').classList.remove('hidden');
    }

    function closeAdjustPointsModal() {
        document.getElementById('adjustPointsModal').classList.add('hidden');
    }

    function updateAdjustmentLabel() {
        const action = document.getElementById('adjustAction').value;
        const label = document.getElementById('pointsLabel');
        
        switch(action) {
            case 'add':
                label.textContent = 'Points to Add';
                break;
            case 'deduct':
                label.textContent = 'Points to Deduct';
                break;
            case 'set':
                label.textContent = 'Set Balance To';
                break;
        }
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdjustPointsModal();
        }
    });

    // Close modal on outside click
    document.getElementById('adjustPointsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAdjustPointsModal();
        }
    });
</script>
@endpush
@endsection
