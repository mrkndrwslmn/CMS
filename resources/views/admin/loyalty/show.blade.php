@extends('admin.layouts.app')

@section('title', 'Loyalty Profile - ' . $user->name)

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Loyalty Program', 'route' => 'admin.loyalty.index', 'icon' => 'award'],
        ['label' => $user->name, 'icon' => 'user'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <x-ui.page-header 
            :title="$user->name . \"'s Loyalty Profile\""
            :description="$user->email"
        />
        <div class="flex gap-3">
            <x-ui.button href="{{ route('admin.loyalty.index') }}" variant="secondary">
                <x-lucide-arrow-left class="w-4 h-4" />
                Back
            </x-ui.button>
            <x-ui.button type="button" onclick="openAdjustPointsModal()" variant="primary">
                <x-lucide-pencil class="w-4 h-4" />
                Adjust Points
            </x-ui.button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Points Overview -->
            <x-ui.card>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Available Points -->
                        <x-loyalty.points-display 
                            :points="$loyaltyPoint->available_points" 
                            label="Available Points"
                            size="lg"
                        >
                            Current Balance
                        </x-loyalty.points-display>

                        <!-- Current Tier -->
                        <div class="text-center border-x border-neutral-200">
                            <p class="text-sm text-neutral-600 mb-2">Current Tier</p>
                            <x-loyalty.tier-badge :tier="$loyaltyPoint->tier" size="xl" />
                        </div>

                        <!-- Lifetime Stats -->
                        <x-loyalty.points-display 
                            :points="$loyaltyPoint->lifetime_earned" 
                            label="Lifetime Earned"
                            type="earned"
                            size="lg"
                        >
                            {{ number_format($loyaltyPoint->lifetime_redeemed) }} redeemed
                        </x-loyalty.points-display>
                    </div>
                </div>
            </x-ui.card>

            <!-- Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Total Earned</p>
                        <x-lucide-trending-up class="w-5 h-5 text-success-500" />
                    </div>
                    <p class="text-2xl font-bold text-success-600">{{ number_format($stats['total_earned']) }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Total Redeemed</p>
                        <x-lucide-trending-down class="w-5 h-5 text-warning-500" />
                    </div>
                    <p class="text-2xl font-bold text-warning-600">{{ number_format($stats['total_redeemed']) }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Expired</p>
                        <x-lucide-clock class="w-5 h-5 text-error-500" />
                    </div>
                    <p class="text-2xl font-bold text-error-600">{{ number_format($stats['total_expired']) }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm text-neutral-600">Expiring Soon</p>
                        <x-lucide-alert-triangle class="w-5 h-5 text-warning-500" />
                    </div>
                    <p class="text-2xl font-bold text-warning-600">{{ number_format($stats['expiring_soon']) }}</p>
                </div>
            </div>

            <!-- Recent Transactions -->
            <x-ui.card>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <x-lucide-history class="w-5 h-5 text-primary-600" />
                            Recent Transactions
                        </h3>
                        <a href="{{ route('admin.loyalty.user-transactions', $user) }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold flex items-center gap-1">
                            View All
                            <x-lucide-arrow-right class="w-4 h-4" />
                        </a>
                    </div>

                    @if($recentTransactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-neutral-50 border-b border-neutral-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Points</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-neutral-200">
                                @foreach($recentTransactions as $transaction)
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-neutral-600">{{ $transaction->created_at->format('M d, Y g:i A') }}</td>
                                    <td class="px-4 py-3">
                                        <x-ui.badge 
                                            :variant="$transaction->transaction_type === 'earned' ? 'success' : ($transaction->transaction_type === 'redeemed' ? 'warning' : ($transaction->transaction_type === 'expired' ? 'error' : 'info'))">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-neutral-700">{{ $transaction->description }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-lg font-bold {{ $transaction->points > 0 ? 'text-success-600' : 'text-error-600' }}">
                                            {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-semibold text-neutral-700">
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
            </x-ui.card>

            <!-- Points Expiring Soon -->
            @if($expiringPoints->count() > 0)
            <x-ui.card class="border-2 border-warning-200 bg-warning-50">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-warning-800 mb-4 flex items-center gap-2">
                        <x-lucide-alert-triangle class="w-5 h-5" />
                        Points Expiring Soon
                    </h3>

                    <div class="space-y-3">
                        @foreach($expiringPoints as $expiring)
                        <div class="bg-white rounded-xl p-4 border border-warning-200">
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
            </x-ui.card>
            @endif
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- User Info -->
            <x-ui.card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-6 flex items-center gap-2">
                        <x-lucide-user class="w-5 h-5 text-primary-600" />
                        User Information
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
            </x-ui.card>

            <!-- Tier Progress -->
            @if($stats['next_tier'])
            <x-ui.card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                        <x-lucide-arrow-up-circle class="w-5 h-5 text-success-600" />
                        Tier Progress
                    </h3>

                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-neutral-600 mb-2">
                            <span>{{ ucfirst($loyaltyPoint->tier) }}</span>
                            <span>{{ ucfirst($stats['next_tier']) }}</span>
                        </div>
                        <div class="w-full bg-neutral-200 rounded-full h-3">
                            <div class="bg-primary-500 h-3 rounded-full transition-all" 
                                 style="width: {{ $stats['tier_progress'] }}%">
                            </div>
                        </div>
                        <p class="text-xs text-neutral-600 mt-2">{{ number_format($stats['points_to_next_tier']) }} more points needed</p>
                    </div>
                </div>
            </x-ui.card>
            @endif

            <!-- Tier Benefits -->
            <x-ui.card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                        <x-lucide-gift class="w-5 h-5 text-success-600" />
                        Current Benefits
                    </h3>

                    <div class="space-y-3">
                        @foreach($currentTierBenefits['benefits'] as $benefit)
                        <div class="flex items-start gap-3">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500 flex-shrink-0 mt-0.5" />
                            <span class="text-sm text-neutral-700">{{ $benefit }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </x-ui.card>

            <!-- Actions -->
            <x-ui.card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4 flex items-center gap-2">
                        <x-lucide-zap class="w-5 h-5 text-warning-600" />
                        Quick Actions
                    </h3>

                    <div class="space-y-3">
                        <x-ui.button type="button" onclick="openAdjustPointsModal()" variant="primary" class="w-full justify-center">
                            <x-lucide-pencil class="w-4 h-4" />
                            Adjust Points
                        </x-ui.button>

                        <x-ui.button href="{{ route('admin.loyalty.user-transactions', $user) }}" variant="secondary" class="w-full justify-center">
                            <x-lucide-list class="w-4 h-4" />
                            View All Transactions
                        </x-ui.button>

                        <x-ui.button href="{{ route('admin.loyalty.export-user', $user) }}" variant="secondary" class="w-full justify-center">
                            <x-lucide-download class="w-4 h-4" />
                            Export Report
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

<!-- Adjust Points Modal -->
<div id="adjustPointsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-neutral-800">Adjust Points</h3>
            <button onclick="closeAdjustPointsModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <x-lucide-x class="w-6 h-6" />
            </button>
        </div>

        <form action="{{ route('admin.loyalty.adjust-points', $user) }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Current Balance -->
                <div class="bg-neutral-100 rounded-xl p-4 text-center">
                    <p class="text-sm text-neutral-600 mb-1">Current Balance</p>
                    <p class="text-3xl font-bold text-primary-600">{{ number_format($loyaltyPoint->available_points) }}</p>
                </div>

                <!-- Action Type -->
                <x-ui.select label="Action" name="action" id="adjustAction" required onchange="updateAdjustmentLabel()">
                    <option value="add">Add Points</option>
                    <option value="deduct">Deduct Points</option>
                    <option value="set">Set Points To</option>
                </x-ui.select>

                <!-- Points -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2" id="pointsLabel">Points to Add</label>
                    <x-ui.input type="number" name="points" min="0" required placeholder="Enter amount" />
                </div>

                <!-- Reason -->
                <x-ui.textarea label="Reason" name="reason" rows="3" required placeholder="Explain why you're adjusting the points" />
            </div>

            <div class="flex gap-3 mt-6">
                <x-ui.button type="button" onclick="closeAdjustPointsModal()" variant="secondary" class="flex-1">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" class="flex-1">
                    <x-lucide-save class="w-4 h-4" />
                    Apply
                </x-ui.button>
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
