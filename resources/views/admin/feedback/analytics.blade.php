@extends('admin.layouts.app')

@section('title', 'Feedback Analytics')
@section('page-title', 'Feedback Analytics')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Feedback', 'route' => 'admin.feedback.index', 'icon' => 'message-square-text'],
        ['label' => 'Analytics', 'icon' => 'bar-chart-3'],
    ]" class="mb-6" />

    <!-- Page Header with Period Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <x-ui.page-header 
            title="Feedback Analytics" 
            description="Comprehensive feedback insights and trends"
        />
        
        <form method="GET" action="{{ route('admin.feedback.analytics') }}" class="mt-4 md:mt-0">
            <div class="flex items-center gap-3">
                <x-ui.select name="period" onchange="this.form.submit()">
                    <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ $period == '365' ? 'selected' : '' }}>Last year</option>
                </x-ui.select>
            </div>
        </form>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-6">
        <!-- Total Feedback -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Feedback</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($analytics['total_feedback']) }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-message-square-text class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <!-- Period Feedback -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Period Feedback</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($analytics['period_feedback']) }}</p>
                </div>
                <div class="p-3 bg-info-50 rounded-xl">
                    <x-lucide-calendar-days class="w-5 h-5 text-info-500" />
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Average Rating</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $analytics['average_rating'] ?? 'N/A' }}/5</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-star class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <!-- Period Average -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Period Avg Rating</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $analytics['period_average_rating'] ?? 'N/A' }}/5</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-trending-up class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <!-- Response Rate -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Response Rate</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $analytics['response_rate'] }}%</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-message-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <!-- Resolution Rate -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Resolution Rate</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $analytics['resolution_rate'] }}%</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Rating Distribution -->
        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center gap-2">
                    <x-lucide-bar-chart-3 class="w-5 h-5 text-neutral-400" />
                    <h2 class="text-lg font-medium text-neutral-700">Rating Distribution</h2>
                </div>
            </x-slot:header>
            
            <div class="space-y-4">
                @for($i = 5; $i >= 1; $i--)
                    @php
                        $ratingData = $ratingDistribution->firstWhere('rating', $i);
                        $count = $ratingData->count ?? 0;
                        $total = $ratingDistribution->sum('count') ?: 1;
                        $percentage = round(($count / $total) * 100);
                    @endphp
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1 w-20">
                            <span class="text-sm font-medium text-neutral-700">{{ $i }}</span>
                            <x-lucide-star class="w-4 h-4 text-warning-400 fill-warning-400" />
                        </div>
                        <div class="flex-1 bg-neutral-100 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full {{ $i >= 4 ? 'bg-success-500' : ($i >= 3 ? 'bg-warning-500' : 'bg-error-500') }}" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-neutral-500 w-16 text-right">{{ $count }} ({{ $percentage }}%)</span>
                    </div>
                @endfor
            </div>
        </x-ui.card>

        <!-- Feedback by Type -->
        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center gap-2">
                    <x-lucide-pie-chart class="w-5 h-5 text-neutral-400" />
                    <h2 class="text-lg font-medium text-neutral-700">Feedback by Type</h2>
                </div>
            </x-slot:header>
            
            <div class="space-y-4">
                @forelse($topIssues as $issue)
                    @php
                        $typeColors = [
                            'general' => 'bg-neutral-500',
                            'service' => 'bg-primary-500',
                            'technical' => 'bg-info-500',
                            'complaint' => 'bg-error-500',
                            'suggestion' => 'bg-success-500',
                        ];
                        $bgColor = $typeColors[$issue->type] ?? 'bg-neutral-500';
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full {{ $bgColor }}"></div>
                            <span class="text-sm font-medium text-neutral-700 capitalize">{{ $issue->type }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-neutral-500">{{ $issue->count }} feedback</span>
                            <div class="flex items-center gap-1">
                                <x-lucide-star class="w-4 h-4 text-warning-400 fill-warning-400" />
                                <span class="text-sm font-medium text-neutral-700">{{ number_format($issue->avg_rating, 1) }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-neutral-400 text-sm text-center py-4">No feedback data available</p>
                @endforelse
            </div>
        </x-ui.card>
    </div>

    <!-- Adiutor Performance -->
    <x-ui.card class="mb-6">
        <x-slot:header>
            <div class="flex items-center gap-2">
                <x-lucide-users class="w-5 h-5 text-neutral-400" />
                <h2 class="text-lg font-medium text-neutral-700">Adiutor Performance (Based on Project Feedback)</h2>
            </div>
        </x-slot:header>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Adiutor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Feedback Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Positive Feedback</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Average Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Satisfaction</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($adiutorPerformance as $adiutor)
                        @php
                            $satisfactionRate = $adiutor->received_feedback_count > 0 
                                ? round(($adiutor->positive_feedback_count / $adiutor->received_feedback_count) * 100) 
                                : 0;
                        @endphp
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($adiutor->profilePic)
                                        <img src="{{ filter_var($adiutor->profilePic, FILTER_VALIDATE_URL) ? $adiutor->profilePic : asset('storage/' . $adiutor->profilePic) }}" 
                                             alt="{{ $adiutor->fullName }}" 
                                             class="w-10 h-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold mr-3">
                                            {{ substr($adiutor->fullName, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-neutral-700">{{ $adiutor->fullName }}</p>
                                        <p class="text-xs text-neutral-400">{{ $adiutor->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700">{{ $adiutor->received_feedback_count }}</td>
                            <td class="px-6 py-4 text-sm text-neutral-700">{{ $adiutor->positive_feedback_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <x-lucide-star class="w-4 h-4 text-warning-400 fill-warning-400" />
                                    <span class="text-sm font-medium text-neutral-700">{{ number_format($adiutor->received_feedback_avg_rating, 1) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 max-w-24 bg-neutral-100 rounded-full h-2 overflow-hidden">
                                        <div class="h-full rounded-full {{ $satisfactionRate >= 80 ? 'bg-success-500' : ($satisfactionRate >= 60 ? 'bg-warning-500' : 'bg-error-500') }}" 
                                             style="width: {{ $satisfactionRate }}%"></div>
                                    </div>
                                    <span class="text-sm text-neutral-500">{{ $satisfactionRate }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-neutral-400">
                                No adiutor performance data available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <!-- Top Clients by Satisfaction -->
    <x-ui.card>
        <x-slot:header>
            <div class="flex items-center gap-2">
                <x-lucide-heart class="w-5 h-5 text-neutral-400" />
                <h2 class="text-lg font-medium text-neutral-700">Top Clients by Satisfaction</h2>
            </div>
        </x-slot:header>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            @forelse($clientSatisfaction as $client)
                <div class="p-4 bg-neutral-50 rounded-xl text-center">
                    @if($client->profilePic)
                        <img src="{{ $client->getProfilePictureUrl() }}" 
                             alt="{{ $client->fullName }}" 
                             class="w-12 h-12 rounded-full object-cover mx-auto mb-3">
                    @else
                        <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold mx-auto mb-3">
                            {{ substr($client->fullName, 0, 1) }}
                        </div>
                    @endif
                    <p class="text-sm font-medium text-neutral-700 truncate">{{ $client->fullName }}</p>
                    <div class="flex items-center justify-center gap-1 mt-2">
                        <x-lucide-star class="w-4 h-4 text-warning-400 fill-warning-400" />
                        <span class="text-sm font-medium text-neutral-700">{{ number_format($client->feedbacks_avg_rating, 1) }}</span>
                    </div>
                    <p class="text-xs text-neutral-400 mt-1">{{ $client->feedbacks_count }} reviews</p>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-neutral-400">
                    No client satisfaction data available
                </div>
            @endforelse
        </div>
    </x-ui.card>
</div>
@endsection
