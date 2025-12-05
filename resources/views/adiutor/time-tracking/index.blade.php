@extends('adiutor.layouts.app')

@section('title', 'Time Tracking')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="timeTracker()">
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
    <x-ui.card class="p-6 mb-8" x-show="activeTimer.active">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center">
                    <x-lucide-play class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-neutral-800" x-text="activeTimer.timer ? activeTimer.timer.task_name : 'No active timer'"></h3>
                    <p class="text-neutral-500" x-text="activeTimer.timer ? activeTimer.timer.project_name : ''"></p>
                    <p class="text-sm text-neutral-400" x-text="activeTimer.timer ? activeTimer.timer.description : ''"></p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-semibold text-primary-600" x-text="formatTime(activeTimer.elapsed)">00:00:00</div>
                <p class="text-sm text-neutral-500">Running time</p>
                <x-ui.button @click="stopTimer()" variant="danger" size="sm" class="mt-2">
                    <x-lucide-square class="w-4 h-4 mr-2" />
                    Stop Timer
                </x-ui.button>
            </div>
        </div>
    </x-ui.card>

    <!-- Start Timer Card -->
    <x-ui.card class="p-6 mb-8" x-show="!activeTimer.active">
        <h3 class="text-lg font-semibold text-neutral-800 mb-4">Start New Timer</h3>
        
        <form @submit.prevent="startTimer()" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="task_id" class="block text-sm font-medium text-neutral-700 mb-1">Select Task <span class="text-error-500">*</span></label>
                    <select id="task_id" x-model="newTimer.task_id" required
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-xl bg-white text-sm text-neutral-700 focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-colors"
                            @change="console.log('Task selected:', newTimer.task_id)">
                        <option value="">Choose a task...</option>
                        @foreach($availableTasks as $task)
                            <option value="{{ $task->taskID }}">{{ $task->project->title }} - {{ $task->taskTitle }}</option>
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
            
            <x-ui.button type="submit" :disabled="!newTimer.task_id || loading" variant="success">
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
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-calendar class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
        
        <!-- This Week -->
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">This Week</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($weekHours, 1) }}h</p>
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
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-calendar-range class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Recent Time Entries -->
    <x-ui.card>
        <div class="p-6 border-b border-neutral-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-neutral-800">Recent Time Entries</h3>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Task & Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
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
                                    @else
                                        <span class="text-warning-500">Running...</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                {{ $entry->start_time->format('M j, g:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($entry->is_approved)
                                    <x-ui.badge variant="success">Approved</x-ui.badge>
                                @else
                                    <x-ui.badge variant="warning">Pending</x-ui.badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if(!$entry->is_approved && $entry->end_time)
                                    <div class="flex items-center space-x-2">
                                        <button @click="editEntry({{ $entry->id }})" 
                                                class="text-primary-600 hover:text-primary-700 transition-colors" title="Edit">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </button>
                                        <button @click="deleteEntry({{ $entry->id }})" 
                                                class="text-error-600 hover:text-error-700 transition-colors" title="Delete">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                @else
                                    <span class="text-neutral-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-neutral-500">
                                    <x-lucide-clock class="w-8 h-8 mx-auto mb-2 text-neutral-400" />
                                    <p class="text-neutral-600">No time entries for this week yet.</p>
                                    <p class="text-sm text-neutral-500">Start a timer to begin tracking your work!</p>
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
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <template x-if="message.type !== 'success'">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <span x-text="message.text"></span>
            <button @click="message.show = false" class="ml-4 text-white/80 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
        activeTimer: {
            active: {{ $activeTimer ? 'true' : 'false' }},
            @if($activeTimer)
            timer: {
                id: {{ $activeTimer->id }},
                task_name: '{{ $activeTimer->task->taskTitle }}',
                project_name: '{{ $activeTimer->task->project->title }}',
                description: '{{ $activeTimer->description }}',
                start_time: '{{ $activeTimer->start_time->toISOString() }}'
            },
            @else
            timer: null,
            @endif
            elapsed: 0
        },
        newTimer: {
            task_id: '',
            description: ''
        },
        message: {
            show: false,
            type: 'success',
            text: ''
        },
        
        init() {
            
            if (this.activeTimer.active) {
                this.updateElapsedTime();
                setInterval(() => this.updateElapsedTime(), 1000);
            }
        },
        
        updateElapsedTime() {
            if (this.activeTimer.timer) {
                const start = new Date(this.activeTimer.timer.start_time);
                const now = new Date();
                this.activeTimer.elapsed = Math.floor((now - start) / 1000);
            }
        },
        
        formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
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
                    this.activeTimer = {
                        active: true,
                        timer: data.timer,
                        elapsed: 0
                    };
                    this.newTimer = { task_id: '', description: '' };
                    this.showMessage('Timer started successfully!', 'success');
                    this.updateElapsedTime();
                    setInterval(() => this.updateElapsedTime(), 1000);
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