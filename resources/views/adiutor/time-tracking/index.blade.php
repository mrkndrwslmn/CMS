@extends('adiutor.layouts.app')

@section('title', 'Time Tracking')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="timeTracker()">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Time Tracking', 'icon' => 'clock'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Time Tracking</h1>
        <p class="text-neutral-500 mt-2">Track your time spent on project tasks and manage your work hours.</p>
    </div>

    <!-- Active Timer Card -->
    <x-ui.card class="p-6 mb-8 border-l-4 border-l-success-500" x-show="activeTimer.active">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-success-50 rounded-xl flex items-center justify-center">
                    <x-lucide-timer class="w-6 h-6 text-success-500 animate-pulse" />
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700">
                            <x-lucide-circle class="w-2 h-2 fill-current animate-pulse" />
                            Recording
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-800 mt-1" x-text="activeTimer.timer ? activeTimer.timer.task_name : 'No active timer'"></h3>
                    <p class="text-sm text-neutral-500" x-text="activeTimer.timer ? activeTimer.timer.project_name : ''"></p>
                    <p class="text-xs text-neutral-400 mt-0.5" x-text="activeTimer.timer ? activeTimer.timer.description : ''"></p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-neutral-800 font-mono" x-text="formatTime(activeTimer.elapsed)">00:00:00</div>
                <p class="text-sm text-neutral-500">Elapsed time</p>
                <x-ui.button @click="stopTimer()" variant="danger" size="sm" class="mt-3">
                    <x-lucide-square class="w-4 h-4 mr-2" />
                    Stop Timer
                </x-ui.button>
            </div>
        </div>
    </x-ui.card>

    <!-- Start Timer Card -->
    <x-ui.card class="p-6 mb-8" x-show="!activeTimer.active">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-primary-50 rounded-lg">
                <x-lucide-play class="w-5 h-5 text-primary-500" />
            </div>
            <h3 class="text-lg font-semibold text-neutral-800">Start New Timer</h3>
        </div>
        
        <form @submit.prevent="startTimer()" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="task_id" class="block text-sm font-medium text-neutral-700 mb-1">Select Task <span class="text-error-500">*</span></label>
                    <select id="task_id" x-model="newTimer.task_id" required
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-xl bg-white text-sm text-neutral-700 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-colors"
                            @change="updateSelectedTaskInfo()">
                        <option value="">Choose a task...</option>
                        @foreach($availableTasks as $task)
                            @php $maxHoursStatus = $task->getMaxHoursStatus(); @endphp
                            <option value="{{ $task->taskID }}" 
                                    data-max-hours="{{ $maxHoursStatus['max_hours'] ?? '' }}"
                                    data-used-hours="{{ $maxHoursStatus['used_hours'] ?? 0 }}"
                                    data-remaining-hours="{{ $maxHoursStatus['remaining_hours'] ?? '' }}"
                                    data-has-limit="{{ $maxHoursStatus['has_limit'] ? 'true' : 'false' }}"
                                    data-status="{{ $maxHoursStatus['status'] }}"
                                    {{ isset($preSelectedTaskId) && $preSelectedTaskId == $task->taskID ? 'selected' : '' }}>
                                {{ $task->project->title }} - {{ $task->taskTitle }}
                                @if($maxHoursStatus['has_limit'])
                                    ({{ $maxHoursStatus['remaining_hours'] }}h remaining)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-neutral-500 mt-1">Available tasks: {{ count($availableTasks) }}</p>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-1">Description (Optional)</label>
                    <input type="text" id="description" x-model="newTimer.description" 
                           placeholder="What are you working on?"
                           class="w-full px-4 py-2.5 border border-neutral-300 rounded-xl bg-white text-sm text-neutral-700 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-colors">
                </div>
            </div>

            <!-- Task Max Hours Info Panel -->
            <div x-show="selectedTaskInfo.hasLimit" x-cloak
                 :class="{
                     'bg-error-50 border-error-200': selectedTaskInfo.status === 'reached',
                     'bg-warning-50 border-warning-200': selectedTaskInfo.status === 'approaching',
                     'bg-success-50 border-success-200': selectedTaskInfo.status === 'normal'
                 }"
                 class="rounded-xl border p-4">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <template x-if="selectedTaskInfo.status === 'reached'">
                            <div class="p-2 bg-error-100 rounded-lg">
                                <x-lucide-alert-circle class="w-5 h-5 text-error-600" />
                            </div>
                        </template>
                        <template x-if="selectedTaskInfo.status === 'approaching'">
                            <div class="p-2 bg-warning-100 rounded-lg">
                                <x-lucide-alert-triangle class="w-5 h-5 text-warning-600" />
                            </div>
                        </template>
                        <template x-if="selectedTaskInfo.status === 'normal'">
                            <div class="p-2 bg-success-100 rounded-lg">
                                <x-lucide-clock class="w-5 h-5 text-success-600" />
                            </div>
                        </template>
                        <div>
                            <p class="text-sm font-semibold" 
                               :class="{
                                   'text-error-700': selectedTaskInfo.status === 'reached',
                                   'text-warning-700': selectedTaskInfo.status === 'approaching',
                                   'text-success-700': selectedTaskInfo.status === 'normal'
                               }">
                                Task Max Hours: <span x-text="selectedTaskInfo.maxHours + 'h'"></span>
                            </p>
                            <p class="text-xs text-neutral-600">
                                <span x-text="selectedTaskInfo.usedHours.toFixed(1) + 'h used'"></span>
                                <span class="mx-1">•</span>
                                <span x-text="selectedTaskInfo.remainingHours.toFixed(1) + 'h remaining'"></span>
                            </p>
                        </div>
                    </div>
                    <template x-if="selectedTaskInfo.status === 'reached' || selectedTaskInfo.status === 'approaching'">
                        <a :href="'{{ route('adiutor.hour-requests.create') }}?task_id=' + newTimer.task_id"
                           class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium rounded-lg transition-colors"
                           :class="{
                               'bg-error-100 text-error-700 hover:bg-error-200': selectedTaskInfo.status === 'reached',
                               'bg-warning-100 text-warning-700 hover:bg-warning-200': selectedTaskInfo.status === 'approaching'
                           }">
                            <x-lucide-plus-circle class="w-4 h-4" />
                            Request More Hours
                        </a>
                    </template>
                </div>
                <!-- Progress bar -->
                <div class="mt-3 h-2 rounded-full overflow-hidden"
                     :class="{
                         'bg-error-200': selectedTaskInfo.status === 'reached',
                         'bg-warning-200': selectedTaskInfo.status === 'approaching',
                         'bg-success-200': selectedTaskInfo.status === 'normal'
                     }">
                    <div class="h-full rounded-full transition-all"
                         :class="{
                             'bg-error-500': selectedTaskInfo.status === 'reached',
                             'bg-warning-500': selectedTaskInfo.status === 'approaching',
                             'bg-success-500': selectedTaskInfo.status === 'normal'
                         }"
                         :style="'width: ' + Math.min(100, (selectedTaskInfo.usedHours / selectedTaskInfo.maxHours) * 100) + '%'">
                    </div>
                </div>
                <template x-if="selectedTaskInfo.status === 'reached'">
                    <p class="mt-2 text-xs text-error-600">
                        <strong>Warning:</strong> This task has reached its maximum hours. New time tracked will be marked as non-billable.
                    </p>
                </template>
            </div>
            
            <x-ui.button type="submit" x-bind:disabled="!newTimer.task_id || loading" variant="success">
                <x-lucide-play class="w-4 h-4 mr-2" />
                <span x-text="loading ? 'Starting...' : 'Start Timer'"></span>
            </x-ui.button>
        </form>
    </x-ui.card>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Today's Hours -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Today's Hours</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($todayHours, 1) }}h</p>
                    <p class="text-xs text-neutral-400 mt-1">{{ now()->format('l, M j') }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-sun class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
        
        <!-- This Week -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">This Week</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($weekHours, 1) }}h</p>
                    <p class="text-xs text-neutral-400 mt-1">{{ now()->startOfWeek()->format('M j') }} - {{ now()->endOfWeek()->format('M j') }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-calendar-days class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>
        
        <!-- This Month -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">This Month</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($monthHours, 1) }}h</p>
                    <p class="text-xs text-neutral-400 mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-calendar-range class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Recent Time Entries -->
    <x-ui.card>
        <div class="p-6 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-history class="w-5 h-5 text-neutral-500" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-800">Recent Time Entries</h3>
                        <p class="text-sm text-neutral-500">This week's recorded hours</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <x-ui.button @click="loadEntries()" variant="secondary" size="sm">
                        <x-lucide-refresh-cw class="w-4 h-4 mr-1" />
                        Refresh
                    </x-ui.button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <x-lucide-folder-kanban class="w-4 h-4" />
                                Task & Project
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <x-lucide-file-text class="w-4 h-4" />
                                Description
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <x-lucide-timer class="w-4 h-4" />
                                Duration
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <x-lucide-calendar class="w-4 h-4" />
                                Date
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <x-lucide-activity class="w-4 h-4" />
                                Status
                            </span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <x-lucide-settings class="w-4 h-4" />
                                Actions
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse($currentWeekEntries as $entry)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-neutral-800">{{ $entry->task->taskTitle }}</div>
                                    <div class="text-sm text-neutral-500">{{ $entry->task->project->title }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-neutral-700">
                                    {{ $entry->description ?: 'No description' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-neutral-800">
                                    @if($entry->duration_minutes)
                                        {{ floor($entry->duration_minutes / 60) }}h {{ $entry->duration_minutes % 60 }}m
                                        @if($entry->admin_adjusted && $entry->original_duration_minutes)
                                            <span class="text-xs text-neutral-400 line-through ml-1">
                                                {{ floor($entry->original_duration_minutes / 60) }}h {{ $entry->original_duration_minutes % 60 }}m
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1 text-warning-500">
                                            <x-lucide-loader class="w-3 h-3 animate-spin" />
                                            Running...
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $entry->start_time->format('M j, g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($entry->is_paid)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                                        <x-lucide-banknote class="w-3 h-3" />
                                        Paid
                                    </span>
                                @elseif($entry->is_approved)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700">
                                        <x-lucide-check-circle class="w-3 h-3" />
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-50 text-warning-700">
                                        <x-lucide-clock class="w-3 h-3" />
                                        Pending
                                    </span>
                                @endif
                                @if($entry->admin_adjusted)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600 ml-1" title="{{ $entry->adjustment_reason }}">
                                        <x-lucide-pencil class="w-3 h-3" />
                                        Adjusted
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(!$entry->is_approved && $entry->end_time)
                                    <div class="flex items-center space-x-2">
                                        <button @click="editEntry({{ $entry->id }})" 
                                                class="p-1.5 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors" title="Edit Entry">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </button>
                                        <button @click="deleteEntry({{ $entry->id }})" 
                                                class="p-1.5 text-error-600 hover:text-error-700 hover:bg-error-50 rounded-lg transition-colors" title="Delete Entry">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                @elseif($entry->is_approved)
                                    <span class="inline-flex items-center gap-1 text-xs text-neutral-400" title="Approved entries cannot be edited">
                                        <x-lucide-lock class="w-3 h-3" />
                                        Locked
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-warning-500" title="Timer still running">
                                        <x-lucide-loader class="w-3 h-3 animate-spin" />
                                        Active
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 bg-neutral-50 rounded-full mb-4">
                                        <x-lucide-clock class="w-8 h-8 text-neutral-400" />
                                    </div>
                                    <h4 class="text-base font-medium text-neutral-700 mb-1">No Time Entries Yet</h4>
                                    <p class="text-sm text-neutral-500 max-w-sm">Start a timer above to begin tracking your work hours on project tasks.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <!-- Success/Error Messages -->
    <div x-show="message.show" x-transition 
         :class="message.type === 'success' ? 'bg-success-500' : 'bg-error-500'"
         class="fixed top-4 right-4 z-50 text-white px-6 py-3 rounded-xl shadow-lg">
        <div class="flex items-center">
            <template x-if="message.type === 'success'">
                <x-lucide-check-circle class="w-5 h-5 mr-2" />
            </template>
            <template x-if="message.type !== 'success'">
                <x-lucide-alert-circle class="w-5 h-5 mr-2" />
            </template>
            <span x-text="message.text"></span>
            <button @click="message.show = false" class="ml-4 text-white/80 hover:text-white">
                <x-lucide-x class="w-4 h-4" />
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function timeTracker() {
    return {
        loading: false,
        timerInterval: null,
        activeTimer: {
            active: {{ $activeTimer ? 'true' : 'false' }},
            @if($activeTimer)
            timer: {
                id: {{ $activeTimer->id }},
                task_name: @json($activeTimer->task->taskTitle),
                project_name: @json($activeTimer->task->project->title),
                description: @json($activeTimer->description ?? ''),
                start_time: @json($activeTimer->start_time->toIso8601String())
            },
            @else
            timer: null,
            @endif
            elapsed: 0
        },
        newTimer: {
            task_id: '{{ $preSelectedTaskId ?? '' }}',
            description: ''
        },
        selectedTaskInfo: {
            hasLimit: false,
            maxHours: 0,
            usedHours: 0,
            remainingHours: 0,
            status: 'unlimited'
        },
        message: {
            show: false,
            type: 'success',
            text: ''
        },
        
        init() {
            // Update selected task info on init if task is pre-selected
            if (this.newTimer.task_id) {
                this.updateSelectedTaskInfo();
            }
            
            if (this.activeTimer.active) {
                this.updateElapsedTime();
                this.startTimerInterval();
            }
        },

        updateSelectedTaskInfo() {
            const select = document.getElementById('task_id');
            const selectedOption = select.options[select.selectedIndex];
            
            if (!selectedOption || !selectedOption.value) {
                this.selectedTaskInfo = {
                    hasLimit: false,
                    maxHours: 0,
                    usedHours: 0,
                    remainingHours: 0,
                    status: 'unlimited'
                };
                return;
            }
            
            const hasLimit = selectedOption.dataset.hasLimit === 'true';
            const maxHours = parseFloat(selectedOption.dataset.maxHours) || 0;
            const usedHours = parseFloat(selectedOption.dataset.usedHours) || 0;
            const remainingHours = parseFloat(selectedOption.dataset.remainingHours) || 0;
            const status = selectedOption.dataset.status || 'unlimited';
            
            this.selectedTaskInfo = {
                hasLimit,
                maxHours,
                usedHours,
                remainingHours,
                status
            };
        },
        
        startTimerInterval() {
            // Clear any existing interval first
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
            }
            // Start new interval
            this.timerInterval = setInterval(() => this.updateElapsedTime(), 1000);
        },
        
        stopTimerInterval() {
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
        },
        
        updateElapsedTime() {
            if (this.activeTimer.timer && this.activeTimer.timer.start_time) {
                const start = new Date(this.activeTimer.timer.start_time);
                if (!isNaN(start.getTime())) {
                    const now = new Date();
                    this.activeTimer.elapsed = Math.floor((now - start) / 1000);
                } else {
                    console.error('Invalid start_time:', this.activeTimer.timer.start_time);
                    this.activeTimer.elapsed = 0;
                }
            }
        },
        
        formatTime(seconds) {
            if (isNaN(seconds) || seconds < 0) {
                return '00:00:00';
            }
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = Math.floor(seconds % 60);
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        },
        
        async startTimer() {
            if (!this.newTimer.task_id) {
                this.showMessage('Please select a task', 'error');
                return;
            }
            
            this.loading = true;
            
            try {
                const response = await fetch('/adiutor/time-tracking/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.newTimer)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Map API response to match expected structure
                    this.activeTimer = {
                        active: true,
                        timer: {
                            id: data.timer.id,
                            task_name: data.timer.task_name,
                            project_name: data.timer.project_name,
                            description: data.timer.description || '',
                            start_time: data.timer.started_at || data.timer.start_time
                        },
                        elapsed: 0
                    };
                    this.newTimer = { task_id: '', description: '' };
                    this.showMessage('Timer started successfully!', 'success');
                    // Update elapsed time immediately and start interval
                    this.updateElapsedTime();
                    this.startTimerInterval();
                } else {
                    this.showMessage(data.message || 'Error starting timer', 'error');
                }
            } catch (error) {
                console.error('Start timer error:', error);
                this.showMessage('Error starting timer: ' + error.message, 'error');
            }
            
            this.loading = false;
        },
        
        async stopTimer() {
            this.loading = true;
            
            try {
                const response = await fetch('/adiutor/time-tracking/stop', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.stopTimerInterval();
                    this.activeTimer = { active: false, timer: null, elapsed: 0 };
                    this.showMessage(`Timer stopped! Duration: ${data.duration.formatted}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showMessage(data.message, 'error');
                }
            } catch (error) {
                this.showMessage('Error stopping timer', 'error');
            }
            
            this.loading = false;
        },
        
        async editEntry(entryId) {
            // Redirect to edit page or open edit modal
            // For now, we'll just show a message that this feature needs implementation
            this.showMessage('Edit functionality will be available soon', 'info');
            
            // Alternative: If you have an edit route, use this:
            // window.location.href = `/adiutor/time-tracking/entries/${entryId}/edit`;
        },
        
        async deleteEntry(entryId) {
            const confirmed = await window.Alerts.confirm({
                title: 'Delete Time Entry',
                message: 'Are you sure you want to delete this time entry?',
                confirmText: 'Delete',
                cancelText: 'Cancel',
                variant: 'danger'
            });
            if (!confirmed) return;
            
            try {
                const response = await fetch(`/adiutor/time-tracking/entries/${entryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showMessage('Time entry deleted successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    this.showMessage(data.message, 'error');
                }
            } catch (error) {
                this.showMessage('Error deleting entry', 'error');
            }
        },
        
        showMessage(text, type = 'success') {
            this.message = { show: true, text, type };
            setTimeout(() => this.message.show = false, 5000);
        },
        
        loadEntries() {
            location.reload();
        }
    }
}
</script>
@endpush