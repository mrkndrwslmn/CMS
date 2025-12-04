@extends('adiutor.layouts.app')

@section('title', 'My Calendar & Task Scheduling')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Calendar', 'icon' => 'calendar'],
    ]" />

    <!-- Header -->
    <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">My Calendar & Task Scheduling</h1>
            <p class="text-sm text-neutral-500 mt-1">View and manage your tasks across all active projects</p>
        </div>
        
        <!-- Right Side Actions -->
        <div class="flex items-center gap-3">
            <!-- Google Calendar Button -->
            @if($integration && $integration->is_connected)
                <x-ui.button variant="success" onclick="showCalendarModal()">
                    <x-lucide-calendar-check class="w-4 h-4" />
                    Google Calendar
                </x-ui.button>
            @else
                <x-ui.button variant="primary" onclick="showCalendarModal()">
                    <x-lucide-calendar-plus class="w-4 h-4" />
                    Connect Calendar
                </x-ui.button>
            @endif
            
            <!-- Project Filter -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-neutral-700">Filter:</label>
                <select id="projectFilter" class="px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                    <option value="">All Projects</option>
                    @foreach($activeProjects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Unscheduled Tasks (Left Side - 4 columns) -->
        <div class="col-span-12 lg:col-span-4">
            <x-ui.card class="h-full">
                <h2 class="text-lg font-medium text-neutral-700 mb-4 flex items-center gap-2">
                    <x-lucide-list-todo class="w-5 h-5 text-neutral-400" />
                    Unscheduled Tasks 
                    <span id="taskCount" class="text-sm font-normal text-neutral-500">({{ $unscheduledTasks->count() }})</span>
                </h2>
                
                <div id="unscheduledTasksList" class="space-y-3 max-h-[calc(100vh-300px)] overflow-y-auto">
                    @forelse($unscheduledTasks as $task)
                        <div class="task-card border rounded-xl p-4 hover:shadow-md transition-shadow {{ $task['has_conflict'] ? 'border-warning-300 bg-warning-50' : 'border-neutral-200 bg-white' }}" 
                             data-task-id="{{ $task['id'] }}"
                             data-project-id="{{ $task['project_id'] }}"
                             data-conflicting-with="{{ $task['conflicting_with'] ?? '' }}">
                            
                            @if($task['has_conflict'])
                                <div class="mb-3 flex items-center gap-2 text-warning-700 bg-warning-100 px-3 py-2 rounded-lg">
                                    <x-lucide-alert-triangle class="w-4 h-4" />
                                    <span class="text-xs font-semibold">Deadline Conflict Detected</span>
                                </div>
                            @endif
                            
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-medium text-neutral-800">{{ $task['title'] }}</h4>
                                <x-ui.badge :type="$task['priority'] === 'high' ? 'error' : ($task['priority'] === 'medium' ? 'warning' : 'success')" size="sm">
                                    {{ ucfirst($task['priority']) }}
                                </x-ui.badge>
                            </div>
                            
                            @if($task['description'])
                                <p class="text-sm text-neutral-500 mb-2">{{ Str::limit($task['description'], 80) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm mb-2">
                                <div class="flex items-center gap-1 text-neutral-500">
                                    <x-lucide-clock class="w-4 h-4" />
                                    {{ $task['estimated_hours'] }} hours
                                </div>
                                @if($task['deadline'])
                                    <div class="flex items-center gap-1 text-neutral-500">
                                        <x-lucide-calendar class="w-4 h-4" />
                                        {{ \Carbon\Carbon::parse($task['deadline'])->format('M d') }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="pt-2 border-t border-neutral-100">
                                <p class="text-xs text-neutral-400 flex items-center gap-1">
                                    <x-lucide-folder-kanban class="w-3 h-3" />
                                    <span class="font-medium text-neutral-500">{{ $task['project_name'] }}</span>
                                </p>
                            </div>
                            
                            <button onclick="scheduleTask({{ $task['id'] }}, {{ $task['has_conflict'] ? 'true' : 'false' }})" class="mt-3 w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-all">
                                <x-lucide-calendar-plus class="w-4 h-4" />
                                Schedule Task
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-12 text-neutral-400">
                            <x-lucide-check-circle class="w-12 h-12 mx-auto mb-3" />
                            <p class="text-sm">All tasks are scheduled!</p>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Calendar Timeline (Right Side - 8 columns) -->
        <div class="col-span-12 lg:col-span-8">
            <x-ui.card>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h2 class="text-lg font-medium text-neutral-700 flex items-center gap-2">
                        <x-lucide-calendar-days class="w-5 h-5 text-neutral-400" />
                        My Calendar Timeline
                    </h2>
                    
                    <!-- Week Navigation -->
                    <div class="flex items-center gap-2">
                        <button onclick="navigateWeek(-1)" class="p-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-all">
                            <x-lucide-chevron-left class="w-4 h-4" />
                        </button>
                        <button onclick="navigateWeek(0)" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all">
                            <x-lucide-calendar class="w-4 h-4" />
                            Today
                        </button>
                        <button onclick="navigateWeek(1)" class="p-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-all">
                            <x-lucide-chevron-right class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <p class="text-sm text-neutral-500 mb-4">
                    Week of <span id="currentWeek" class="font-medium text-neutral-700"></span>
                </p>
                
                @if(!$integration || !$integration->is_connected)
                <!-- Calendar Not Connected Banner -->
                <x-ui.alert type="info" class="mb-4" dismissible>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex-1">
                            <p class="font-medium text-primary-800 mb-1">Google Calendar Not Connected</p>
                            <p class="text-sm text-primary-700">You're viewing only your in-app tasks. Connect your Google Calendar to see all your events in one place.</p>
                        </div>
                        <a href="{{ url('/calendar/connect') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-all shrink-0">
                            <x-lucide-calendar-plus class="w-4 h-4" />
                            Connect Google Calendar
                        </a>
                    </div>
                </x-ui.alert>
                @endif

                <!-- Legend -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-6 mb-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-primary-500 rounded"></div>
                        <span class="text-neutral-600">CMS Tasks</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span class="text-neutral-600">Google Calendar Events</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-success-50 border-2 border-success-300 rounded"></div>
                        <span class="text-neutral-600">Available</span>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div id="calendarGrid" class="bg-white border border-neutral-200 rounded-xl overflow-hidden">
                    <!-- Calendar will be rendered here by JavaScript -->
                    <div class="flex items-center justify-center py-12 text-neutral-400">
                        <x-lucide-loader-2 class="w-5 h-5 mr-2 animate-spin" />
                        Loading calendar...
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

<script>
let currentWeekStart = null;
let selectedProjectId = null;
const adiutorId = {{ $user->id }};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Monday
    
    loadCalendar();
    
    // Setup project filter
    document.getElementById('projectFilter').addEventListener('change', function(e) {
        selectedProjectId = e.target.value || null;
        filterTasks();
    });

    // Auto-open connected modal if preview flag is set
    @if(isset($showConnectedModal) && $showConnectedModal)
        setTimeout(() => {
            showConnectedModal();
        }, 500);
    @endif

    // Auto-open connected modal after successful OAuth connection
    @if(session('showConnectedModal'))
        setTimeout(() => {
            showConnectedModal();
        }, 500);
    @endif
});

function filterTasks() {
    const taskCards = document.querySelectorAll('.task-card');
    let visibleCount = 0;
    
    taskCards.forEach(card => {
        const projectId = card.getAttribute('data-project-id');
        
        if (!selectedProjectId || projectId === selectedProjectId) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    document.getElementById('taskCount').textContent = `(${visibleCount})`;
}

function navigateWeek(direction) {
    if (direction === 0) {
        // Today
        currentWeekStart = new Date();
        currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1);
    } else {
        // Previous/Next week
        currentWeekStart.setDate(currentWeekStart.getDate() + (direction * 7));
    }
    loadCalendar();
}

function loadCalendar() {
    const startDate = formatDate(currentWeekStart);
    document.getElementById('currentWeek').textContent = formatDateDisplay(currentWeekStart);
    
    // Add project filter to the API request
    let url = `/api/schedule/adiutor/${adiutorId}/timeline?start_date=${startDate}`;
    if (selectedProjectId) {
        url += `&project_id=${selectedProjectId}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            renderCalendar(data.slots);
        })
        .catch(error => {
            console.error('Error loading calendar:', error);
            document.getElementById('calendarGrid').innerHTML = `
                <div class="flex items-center justify-center py-12 text-error-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Error loading calendar
                </div>
            `;
        });
}

function renderCalendar(slots) {
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const hours = Array.from({length: 17}, (_, i) => i + 6); // 6 AM to 10 PM
    
    let html = '<div class="grid grid-cols-8 border-b border-neutral-200">';
    
    // Header row
    html += '<div class="p-3 bg-neutral-50 font-medium text-neutral-700 text-sm border-r border-neutral-200 sticky top-0">Time</div>';
    days.forEach((day, index) => {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + index);
        html += `<div class="p-3 bg-neutral-50 font-medium text-neutral-700 text-sm border-r border-neutral-200 last:border-r-0 sticky top-0">
            ${day}<br>
            <span class="text-xs text-neutral-400">${date.getMonth() + 1}/${date.getDate()}</span>
        </div>`;
    });
    html += '</div>';
    
    // Time slots
    hours.forEach(hour => {
        html += '<div class="grid grid-cols-8 border-b border-neutral-200 last:border-b-0">';
        
        // Hour label
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        html += `<div class="p-3 bg-neutral-50 text-sm text-neutral-500 font-medium border-r border-neutral-200">${displayHour}:00 ${ampm}</div>`;
        
        // Day cells
        days.forEach((day, dayIndex) => {
            const date = new Date(currentWeekStart);
            date.setDate(date.getDate() + dayIndex);
            const dateStr = formatDate(date);
            
            // Find slots for this time/day
            const daySlots = slots.filter(slot => slot.date === dateStr && slot.hour === hour);
            
            html += '<div class="p-2 border-r border-neutral-200 last:border-r-0 min-h-[80px] relative bg-success-50">';
            
            if (daySlots.length === 0) {
                // Available slot
                html += '<div class="text-xs text-neutral-400 italic">Available</div>';
            } else {
                daySlots.forEach(slot => {
                    const colorClass = slot.type === 'task' 
                        ? 'bg-primary-100 border-primary-300 text-primary-800' 
                        : 'bg-purple-100 border-purple-300 text-purple-800';
                    const syncIcon = slot.is_synced ? '<svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>' : '';
                    
                    // Create tooltip with full task details
                    const description = slot.description || 'No description';
                    const tooltipText = `Task: ${slot.title}&#10;Description: ${description}&#10;Days: ${slot.duration}${slot.is_synced ? '&#10;Synced with Google Calendar' : ''}`;
                    
                    html += `<div class="${colorClass} border rounded-lg px-2 py-1 text-xs mb-1 cursor-pointer hover:shadow-md transition-shadow" 
                                  title="${tooltipText}">
                        <div class="font-medium truncate">${slot.title} ${syncIcon}</div>
                        <div class="text-xs opacity-75">${slot.duration} day/s</div>
                    </div>`;
                });
            }
            
            html += '</div>';
        });
        
        html += '</div>';
    });
    
    document.getElementById('calendarGrid').innerHTML = html;
}

function scheduleTask(taskId, hasConflict) {
    // Get task details from the task card
    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    if (!taskCard) return;
    
    const title = taskCard.querySelector('h4').textContent;
    // Use data attributes or find elements by their content structure
    const hoursEl = taskCard.querySelector('[data-hours]') || taskCard.querySelectorAll('.text-neutral-500')[0];
    const deadlineEl = taskCard.querySelector('[data-deadline]') || taskCard.querySelectorAll('.text-neutral-500')[1];
    const projectEl = taskCard.querySelector('.font-medium.text-neutral-500');
    
    const hours = hoursEl?.textContent?.trim() || 'N/A';
    const deadline = deadlineEl?.textContent?.trim() || 'N/A';
    const project = projectEl?.textContent || 'N/A';
    const conflictingWith = taskCard.dataset.conflictingWith || 'Unknown Task';
    
    // If task has a conflict, show conflict modal
    if (hasConflict) {
        // Fill conflict modal with task details
        document.getElementById('conflictTaskTitle').textContent = title;
        document.getElementById('conflictDeadline').textContent = deadline;
        document.getElementById('conflictHours').textContent = hours.replace(/[^\d]/g, '');
        document.getElementById('conflictProject').textContent = project;
        document.getElementById('conflictingTaskName').textContent = conflictingWith;
        
        // Store task ID for later use
        document.getElementById('scheduleConflictModal').dataset.taskId = taskId;
        
        // Show conflict modal
        document.getElementById('scheduleConflictModal').classList.remove('hidden');
        return;
    }
    
    // Otherwise, handle regular task scheduling
    alert('Regular task scheduling functionality to be implemented');
}

function closeConflictModal() {
    document.getElementById('scheduleConflictModal').classList.add('hidden');
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatDateDisplay(date) {
    const options = { month: 'short', day: 'numeric', year: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

function showCalendarModal() {
    @if($integration && $integration->is_connected)
        showConnectedModal();
    @else
        const modal = document.getElementById('calendarConnectionModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    @endif
}

function closeCalendarModal() {
    const modal = document.getElementById('calendarConnectionModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function showConnectedModal() {
    const modal = document.getElementById('calendarConnectedModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeConnectedModal() {
    const modal = document.getElementById('calendarConnectedModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function syncAllTasks() {
    const button = event.target;
    const statusEl = document.getElementById('syncStatus');
    
    // Store original button content
    const originalContent = button.innerHTML;
    
    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = '<svg class="w-4 h-4 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>Syncing...';
    statusEl.classList.add('hidden');
    
    fetch('{{ url('/calendar/sync-all') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>Sync Timeline Tasks to Google Calendar';
        
        if (data.success) {
            statusEl.textContent = `Successfully synced ${data.synced_count} timeline task(s)`;
            statusEl.className = 'mt-2 text-sm text-center text-success-600';
            statusEl.classList.remove('hidden');
            
            // Reload calendar timeline to show synced tasks
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            statusEl.textContent = `Error: ${data.error}`;
            statusEl.className = 'mt-2 text-sm text-center text-error-600';
            statusEl.classList.remove('hidden');
        }
    })
    .catch(error => {
        button.disabled = false;
        button.innerHTML = '<svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>Sync Timeline Tasks to Google Calendar';
        statusEl.textContent = `Failed to sync: ${error.message}`;
        statusEl.className = 'mt-2 text-sm text-center text-error-600';
        statusEl.classList.remove('hidden');
    });
}
</script>

<!-- Calendar Connection Modal (Not Connected) -->
<div id="calendarConnectionModal" class="hidden fixed inset-0 backdrop-blur-sm bg-neutral-900/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="border-b border-neutral-100 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-neutral-800">Connect Your Google Calendar</h2>
            <button onclick="closeCalendarModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-6">
            <!-- Why Connect Section -->
            <div class="mb-6">
                <h3 class="text-base font-medium text-neutral-700 mb-4 flex items-center gap-2">
                    <x-lucide-calendar class="w-5 h-5 text-primary-500" />
                    Why connect your calendar?
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                        <span class="text-sm text-neutral-600">Admins can see your real availability</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                        <span class="text-sm text-neutral-600">Tasks auto-sync to your Google Calendar</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                        <span class="text-sm text-neutral-600">Get reminders for upcoming tasks</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                        <span class="text-sm text-neutral-600">Prevent double-booking and conflicts</span>
                    </li>
                </ul>
            </div>
            
            <!-- Privacy Section -->
            <x-ui.alert type="info" class="mb-6">
                <h3 class="text-sm font-medium text-primary-800 mb-2 flex items-center gap-2">
                    <x-lucide-lock class="w-4 h-4" />
                    We only access:
                </h3>
                <ul class="space-y-1 text-sm text-primary-700">
                    <li class="flex items-center gap-2">
                        <span class="text-primary-500">-</span>
                        Free/Busy times (not event details)
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-primary-500">-</span>
                        Calendar events we create
                    </li>
                </ul>
            </x-ui.alert>
            
            <!-- Connect Button -->
            <div class="text-center mb-4">
                <a href="{{ url('/calendar/connect') }}" class="inline-flex items-center gap-3 px-8 py-3 bg-primary-600 text-white font-medium text-base rounded-lg hover:bg-primary-700 transition-all shadow-sm hover:shadow-md">
                    <x-lucide-calendar-plus class="w-5 h-5" />
                    Connect Google Calendar
                </a>
            </div>
            
            <!-- Already Connected Link -->
            <div class="text-center">
                <p class="text-sm text-neutral-500">
                    Already connected? 
                    <a href="{{ url('/calendar/connection') }}" class="text-primary-600 hover:text-primary-700 font-medium">View Settings</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Connected Successfully Modal -->
<div id="calendarConnectedModal" class="hidden fixed inset-0 backdrop-blur-sm bg-neutral-900/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg max-w-2xl w-full">
        <!-- Modal Header -->
        <div class="border-b border-neutral-100 px-6 py-4 flex items-center justify-between bg-success-50 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <x-lucide-check-circle class="w-6 h-6 text-success-600" />
                <h2 class="text-lg font-semibold text-neutral-800">Calendar Connected Successfully</h2>
            </div>
            <button onclick="closeConnectedModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-6">
            <!-- Account Info -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-neutral-50 rounded-xl">
                    <p class="text-sm text-neutral-500 mb-1">Account</p>
                    <p class="font-medium text-neutral-800">{{ $integration && $integration->calendar_id ? $integration->calendar_id : 'N/A' }}</p>
                </div>
                <div class="p-4 bg-neutral-50 rounded-xl">
                    <p class="text-sm text-neutral-500 mb-1">Status</p>
                    <p class="font-medium text-success-600 flex items-center gap-2">
                        <span class="w-2 h-2 bg-success-500 rounded-full"></span>
                        Active
                    </p>
                </div>
            </div>
            
            <div class="mb-6 p-4 bg-neutral-50 rounded-xl">
                <p class="text-sm text-neutral-500 mb-1">Last Synced</p>
                <p class="font-medium text-neutral-800">
                    @if($integration && $integration->last_synced_at)
                        {{ $integration->last_synced_at->diffForHumans() }}
                    @else
                        Never
                    @endif
                </p>
            </div>
            
            <!-- Working Hours -->
            <x-ui.alert type="info" class="mb-6">
                <h3 class="text-sm font-medium text-primary-800 mb-2">Working Hours (Default):</h3>
                <p class="text-sm text-primary-700 mb-1"><strong>Monday - Friday:</strong> 9:00 AM - 5:00 PM</p>
                <p class="text-sm text-primary-700"><strong>Timezone:</strong> Asia/Manila (GMT+8)</p>
            </x-ui.alert>
            
            <!-- Sync Info -->
            <div class="mb-6 p-4 bg-success-50 border border-success-200 rounded-xl">
                <h3 class="text-sm font-medium text-neutral-800 mb-2 flex items-center gap-2">
                    <x-lucide-refresh-cw class="w-4 h-4 text-success-600" />
                    Auto-Sync Features
                </h3>
                <ul class="space-y-2 text-sm text-neutral-600">
                    <li class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-success-600 mt-0.5 shrink-0" />
                        <span>New tasks are automatically synced when placed on timeline</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-lucide-info class="w-4 h-4 text-primary-500 mt-0.5 shrink-0" />
                        <span>Tasks in "Unscheduled" section will NOT be synced</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-lucide-info class="w-4 h-4 text-primary-500 mt-0.5 shrink-0" />
                        <span>Click below to sync timeline tasks added before connecting</span>
                    </li>
                </ul>
                <button onclick="syncAllTasks()" class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-all">
                    <x-lucide-calendar-plus class="w-4 h-4" />
                    Sync Timeline Tasks to Google Calendar
                </button>
                <p id="syncStatus" class="mt-2 text-sm text-center hidden"></p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="window.location.href='{{ url('/calendar/connection') }}'" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-neutral-100 text-neutral-700 font-medium rounded-lg hover:bg-neutral-200 transition-all">
                    <x-lucide-settings class="w-4 h-4" />
                    Edit Working Hours
                </button>
                <form action="{{ url('/calendar/disconnect') }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to disconnect your calendar?');">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-error-600 text-white font-medium rounded-lg hover:bg-error-700 transition-all">
                        <x-lucide-unlink class="w-4 h-4" />
                        Disconnect
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Conflict Modal -->
<div id="scheduleConflictModal" class="hidden fixed inset-0 backdrop-blur-sm bg-neutral-900/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-lg max-w-md w-full">
        <!-- Modal Header -->
        <div class="bg-warning-50 border-b border-warning-200 px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <div class="flex items-center gap-2">
                <x-lucide-alert-triangle class="w-5 h-5 text-warning-600" />
                <h2 class="text-lg font-semibold text-warning-800">Schedule Conflict</h2>
            </div>
            <button onclick="closeConflictModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-4">
                <h3 class="font-medium text-neutral-800 mb-2" id="conflictTaskTitle">Task Title</h3>
                <p class="text-sm text-neutral-500 mb-3">
                    This task has the same deadline as another task assigned to you.
                </p>
            </div>

            <div class="bg-warning-50 border border-warning-200 rounded-xl p-4 mb-4">
                <p class="text-sm text-warning-800 font-medium mb-2 flex items-center gap-2">
                    <x-lucide-info class="w-4 h-4" />
                    Deadline Conflict Details:
                </p>
                <div class="text-sm text-neutral-600 space-y-1">
                    <p><strong>Deadline:</strong> <span id="conflictDeadline">-</span></p>
                    <p><strong>Estimated Hours:</strong> <span id="conflictHours">-</span> hours</p>
                    <p><strong>Project:</strong> <span id="conflictProject">-</span></p>
                </div>
            </div>

            <div class="bg-error-50 border-l-4 border-error-500 rounded-r-lg p-4 mb-6">
                <p class="text-sm text-error-800 font-medium mb-1 flex items-center gap-2">
                    <x-lucide-calendar-x class="w-4 h-4" />
                    Conflicts with:
                </p>
                <p class="text-sm text-error-900 font-semibold" id="conflictingTaskName">-</p>
            </div>

            <p class="text-sm text-neutral-500 mb-6">
                You have multiple tasks due on the same day. Please contact your admin to reschedule or adjust the deadline.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <x-ui.button variant="primary" class="w-full" onclick="closeConflictModal()">
                    <x-lucide-check class="w-4 h-4" />
                    Understood
                </x-ui.button>
                <x-ui.button variant="ghost" class="w-full" onclick="closeConflictModal()">
                    Cancel
                </x-ui.button>
            </div>
        </div>
    </div>
</div>

@endsection
