@extends('admin.layouts.app')

@section('title', 'Project Earnings - ' . ($project->title ?? 'Unknown'))

@section('content')
<div class="w-full">
    <div class="w-full px-4 py-5">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Platform Earnings', 'route' => 'admin.platform-earnings.index', 'icon' => 'trending-up'],
            ['label' => Str::limit($project->title ?? 'Project', 30), 'icon' => 'folder'],
        ]" />

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-neutral-800 flex items-center gap-3">
                        <div class="p-2 bg-primary-50 rounded-xl">
                            <x-lucide-folder-kanban class="w-6 h-6 text-primary-600" />
                        </div>
                        {{ $project->title ?? 'Unknown Project' }}
                    </h1>
                    <p class="text-neutral-500 mt-1">
                        Client: {{ $project->client->fullName ?? 'No Client' }} • 
                        Status: <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.platform-earnings.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-xl transition-colors">
                        <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                        Back to List
                    </a>
                    <form action="{{ route('admin.platform-earnings.calculate', $project->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-xl transition-colors">
                            <x-lucide-calculator class="w-4 h-4 mr-2" />
                            Recalculate
                        </button>
                    </form>
                    @if($earnings && $earnings->status !== 'finalized' && $project->status === 'completed')
                        <form action="{{ route('admin.platform-earnings.finalize', $project->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-xl transition-colors"
                                    onclick="return confirm('Finalize earnings for this project? This cannot be undone.')">
                                <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                Finalize Earnings
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if($earnings)
            <!-- Earnings Status Banner -->
            @if($earnings->status === 'finalized')
                <div class="bg-success-50 border border-success-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <x-lucide-check-circle-2 class="w-5 h-5 text-success-600" />
                        <div>
                            <p class="font-medium text-success-800">Earnings Finalized</p>
                            <p class="text-sm text-success-600">
                                Finalized on {{ $earnings->finalized_at ? $earnings->finalized_at->format('F j, Y \a\t g:i A') : 'Unknown' }}
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($project->status === 'completed')
                <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <x-lucide-alert-circle class="w-5 h-5 text-warning-600" />
                            <div>
                                <p class="font-medium text-warning-800">Project Completed - Pending Finalization</p>
                                <p class="text-sm text-warning-600">Review the earnings breakdown and finalize when ready</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Project Budget -->
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-neutral-500 text-sm font-medium">Project Budget</p>
                            <p class="text-2xl font-bold text-neutral-800 mt-2">₱{{ number_format($earnings->project_budget ?? 0, 2) }}</p>
                            <p class="text-neutral-500 text-sm mt-1">Client payment</p>
                        </div>
                        <div class="p-3 bg-neutral-100 rounded-xl">
                            <x-lucide-banknote class="w-5 h-5 text-neutral-500" />
                        </div>
                    </div>
                </div>

                <!-- Platform Fee -->
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-neutral-500 text-sm font-medium">Platform Fee ({{ $earnings->fee_percentage ?? config('financial.platform.fee_percentage', 15) }}%)</p>
                            <p class="text-2xl font-bold text-blue-600 mt-2">₱{{ number_format($earnings->platform_fee ?? 0, 2) }}</p>
                            <p class="text-neutral-500 text-sm mt-1">Guaranteed revenue</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-xl">
                            <x-lucide-percent class="w-5 h-5 text-blue-500" />
                        </div>
                    </div>
                </div>

                <!-- Margin Earnings -->
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-neutral-500 text-sm font-medium">Margin Earnings</p>
                            <p class="text-2xl font-bold text-purple-600 mt-2">₱{{ number_format($earnings->margin_earnings ?? 0, 2) }}</p>
                            <p class="text-neutral-500 text-sm mt-1">Budget surplus</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-xl">
                            <x-lucide-piggy-bank class="w-5 h-5 text-purple-500" />
                        </div>
                    </div>
                </div>

                <!-- Total Earnings -->
                <div class="bg-gradient-to-br from-success-500 to-success-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-success-100 text-sm font-medium">Total Platform Earnings</p>
                            <p class="text-3xl font-bold mt-2">₱{{ number_format($earnings->total_earnings ?? 0, 2) }}</p>
                            <p class="text-success-100 text-sm mt-1">Fee + Margin</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl">
                            <x-lucide-wallet class="w-6 h-6" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Earnings Calculation Breakdown -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-neutral-100 shadow-sm">
                    <div class="p-5 border-b border-neutral-100">
                        <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                            <x-lucide-calculator class="w-5 h-5 text-primary-500" />
                            Earnings Calculation Breakdown
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <!-- Budget Section -->
                        <div class="bg-neutral-50 rounded-xl p-4">
                            <h4 class="font-medium text-neutral-700 mb-3 flex items-center gap-2">
                                <x-lucide-wallet class="w-4 h-4 text-neutral-500" />
                                Project Budget
                            </h4>
                            <div class="flex justify-between items-center text-lg">
                                <span class="text-neutral-600">Client Budget</span>
                                <span class="font-bold text-neutral-800">₱{{ number_format($earnings->project_budget ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Platform Fee Calculation -->
                        <div class="bg-blue-50 rounded-xl p-4">
                            <h4 class="font-medium text-blue-700 mb-3 flex items-center gap-2">
                                <x-lucide-percent class="w-4 h-4 text-blue-500" />
                                Platform Fee Calculation
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-600">Budget × {{ $earnings->fee_percentage ?? 15 }}%</span>
                                    <span class="font-medium text-blue-700">
                                        ₱{{ number_format($earnings->project_budget ?? 0, 2) }} × {{ ($earnings->fee_percentage ?? 15) / 100 }}
                                    </span>
                                </div>
                                @if(($earnings->platform_fee ?? 0) < config('financial.platform.minimum_fee', 500))
                                    <div class="flex justify-between text-orange-600">
                                        <span>Minimum Fee Applied</span>
                                        <span class="font-medium">₱{{ number_format(config('financial.platform.minimum_fee', 500), 2) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3 pt-3 border-t border-blue-200 flex justify-between items-center">
                                <span class="font-medium text-blue-700">Platform Fee</span>
                                <span class="text-xl font-bold text-blue-600">₱{{ number_format($earnings->platform_fee ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Working Budget & Adiutor Costs -->
                        <div class="bg-warning-50 rounded-xl p-4">
                            <h4 class="font-medium text-warning-700 mb-3 flex items-center gap-2">
                                <x-lucide-users class="w-4 h-4 text-warning-500" />
                                Adiutor Costs
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-warning-600">Working Budget (After Fee)</span>
                                    <span class="font-medium text-warning-700">
                                        ₱{{ number_format(($earnings->project_budget ?? 0) - ($earnings->platform_fee ?? 0), 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-warning-600">Total Adiutor Payments</span>
                                    <span class="font-medium text-warning-700">- ₱{{ number_format($earnings->total_adiutor_cost ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-warning-200">
                                <div class="text-xs text-warning-600 mb-2">
                                    Includes: Fixed rates + Hourly time entries
                                </div>
                            </div>
                        </div>

                        <!-- Margin Calculation -->
                        <div class="bg-purple-50 rounded-xl p-4">
                            <h4 class="font-medium text-purple-700 mb-3 flex items-center gap-2">
                                <x-lucide-trending-up class="w-4 h-4 text-purple-500" />
                                Margin Calculation
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-purple-600">Working Budget</span>
                                    <span class="font-medium text-purple-700">
                                        ₱{{ number_format(($earnings->project_budget ?? 0) - ($earnings->platform_fee ?? 0), 2) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-purple-600">Adiutor Costs</span>
                                    <span class="font-medium text-purple-700">- ₱{{ number_format($earnings->total_adiutor_cost ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-purple-200 flex justify-between items-center">
                                <span class="font-medium text-purple-700">Margin Earnings (Surplus)</span>
                                <span class="text-xl font-bold text-purple-600">₱{{ number_format($earnings->margin_earnings ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-success-50 rounded-xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-success-700 text-lg">Total Platform Earnings</span>
                                    <p class="text-xs text-success-600 mt-1">Platform Fee + Margin Earnings</p>
                                </div>
                                <span class="text-2xl font-bold text-success-600">₱{{ number_format($earnings->total_earnings ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Info & Adiutor Breakdown -->
                <div class="space-y-6">
                    <!-- Project Info -->
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                        <div class="p-5 border-b border-neutral-100">
                            <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                                <x-lucide-info class="w-5 h-5 text-primary-500" />
                                Project Info
                            </h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Status</span>
                                @php
                                    $statusConfig = match($project->status) {
                                        'completed' => ['class' => 'bg-success-50 text-success-700', 'label' => 'Completed'],
                                        'active' => ['class' => 'bg-blue-50 text-blue-700', 'label' => 'Active'],
                                        'in_progress' => ['class' => 'bg-primary-50 text-primary-700', 'label' => 'In Progress'],
                                        'review' => ['class' => 'bg-warning-50 text-warning-700', 'label' => 'Review'],
                                        'cancelled' => ['class' => 'bg-error-50 text-error-700', 'label' => 'Cancelled'],
                                        default => ['class' => 'bg-neutral-100 text-neutral-600', 'label' => ucfirst($project->status)]
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Client</span>
                                <span class="text-neutral-800 font-medium">{{ $project->client->fullName ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Created</span>
                                <span class="text-neutral-800">{{ $project->created_at?->format('M j, Y') ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Last Updated</span>
                                <span class="text-neutral-800">{{ $earnings->updated_at?->format('M j, Y') ?? 'N/A' }}</span>
                            </div>
                            @if($earnings->profit_margin_percentage !== null)
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">Profit Margin</span>
                                    <span class="font-bold {{ $earnings->profit_margin_percentage >= 0 ? 'text-success-600' : 'text-error-600' }}">
                                        {{ number_format($earnings->profit_margin_percentage, 1) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Adiutor Costs Breakdown -->
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm">
                        <div class="p-5 border-b border-neutral-100">
                            <h3 class="text-lg font-semibold text-neutral-800 flex items-center gap-2">
                                <x-lucide-users class="w-5 h-5 text-primary-500" />
                                Adiutor Breakdown
                            </h3>
                        </div>
                        <div class="p-5">
                            @if($adiutorBreakdown && count($adiutorBreakdown) > 0)
                                <div class="space-y-3">
                                    @foreach($adiutorBreakdown as $adiutor)
                                        <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-xl">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                                                    <span class="text-primary-600 text-sm font-medium">
                                                        {{ strtoupper(substr($adiutor['name'] ?? 'A', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-neutral-800">{{ $adiutor['name'] ?? 'Unknown' }}</p>
                                                    <p class="text-xs text-neutral-500">{{ $adiutor['type'] ?? 'Mixed' }}</p>
                                                </div>
                                            </div>
                                            <span class="font-medium text-neutral-800">₱{{ number_format($adiutor['total'] ?? 0, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 pt-4 border-t border-neutral-100 flex justify-between">
                                    <span class="font-medium text-neutral-600">Total Adiutor Costs</span>
                                    <span class="font-bold text-neutral-800">₱{{ number_format($earnings->total_adiutor_cost ?? 0, 2) }}</span>
                                </div>
                            @else
                                <div class="text-center py-6 text-neutral-500">
                                    <x-lucide-users class="w-10 h-10 mx-auto mb-2 text-neutral-300" />
                                    <p>No adiutor assignments yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- History/Notes -->
            @if($earnings->notes)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-3 flex items-center gap-2">
                        <x-lucide-file-text class="w-5 h-5 text-primary-500" />
                        Notes
                    </h3>
                    <div class="text-neutral-600 prose prose-sm max-w-none">
                        {{ $earnings->notes }}
                    </div>
                </div>
            @endif
        @else
            <!-- No Earnings Record -->
            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-12 text-center">
                <x-lucide-calculator class="w-16 h-16 mx-auto mb-4 text-neutral-300" />
                <h3 class="text-xl font-semibold text-neutral-800 mb-2">No Earnings Calculated</h3>
                <p class="text-neutral-500 mb-6">Platform earnings have not been calculated for this project yet.</p>
                <form action="{{ route('admin.platform-earnings.calculate', $project->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white rounded-xl transition-colors">
                        <x-lucide-calculator class="w-5 h-5 mr-2" />
                        Calculate Earnings Now
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
