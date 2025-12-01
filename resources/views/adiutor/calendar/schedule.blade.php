@extends('adiutor.layouts.app')

@section('title', 'My Calendar & Task Scheduling')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('adiutor.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">Calendar</span>
    </nav>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Calendar & Task Scheduling</h1>
            <p class="text-gray-600">View and manage your tasks across all active projects</p>
        </div>
        
        <!-- Right Side Actions -->
        <div class="flex items-center gap-3">
            <!-- Google Calendar Button -->
            @if($integration && $integration->is_connected)
                <button onclick="showCalendarModal()" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fab fa-google"></i>
                    <span class="font-medium">Google Calendar</span>
                </button>
            @else
                <button onclick="showCalendarModal()" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fab fa-google"></i>
                    <span class="font-medium">Connect Calendar</span>
                </button>
            @endif
            
            <!-- Project Filter -->
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Filter:</label>
                <select id="projectFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
        <div class="col-span-4">
            <div class="bg-white rounded-lg shadow-md p-6 h-full">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Unscheduled Tasks 
                    <span id="taskCount" class="text-sm font-normal text-gray-600">({{ $unscheduledTasks->count() }})</span>
                </h2>
                
                <div id="unscheduledTasksList" class="space-y-3 max-h-[calc(100vh-300px)] overflow-y-auto">
                    @forelse($unscheduledTasks as $task)
                        <div class="task-card border rounded-lg p-4 hover:shadow-md transition-shadow {{ $task['has_conflict'] ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }}" 
                             data-task-id="{{ $task['id'] }}"
                             data-project-id="{{ $task['project_id'] }}"
                             data-conflicting-with="{{ $task['conflicting_with'] ?? '' }}">
                            
                            @if($task['has_conflict'])
                                <div class="mb-3 flex items-center gap-2 text-yellow-700 bg-yellow-100 px-3 py-2 rounded-lg">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="text-xs font-semibold">Deadline Conflict Detected</span>
                                </div>
                            @endif
                            
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $task['title'] }}</h4>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    {{ $task['priority'] === 'high' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $task['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $task['priority'] === 'low' ? 'bg-green-100 text-green-700' : '' }}">
                                    {{ ucfirst($task['priority']) }}
                                </span>
                            </div>
                            
                            @if($task['description'])
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($task['description'], 80) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between text-sm mb-2">
                                <div class="text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $task['estimated_hours'] }} hours
                                </div>
                                @if($task['deadline'])
                                    <div class="text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($task['deadline'])->format('M d') }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="pt-2 border-t border-gray-200">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-project-diagram mr-1"></i>
                                    <span class="font-medium">{{ $task['project_name'] }}</span>
                                </p>
                            </div>
                            
                            <button onclick="scheduleTask({{ $task['id'] }}, {{ $task['has_conflict'] ? 'true' : 'false' }})" class="mt-3 w-full px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-calendar-plus mr-2"></i>
                                Schedule Task
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-check-circle text-4xl mb-3"></i>
                            <p>All tasks are scheduled!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Calendar Timeline (Right Side - 8 columns) -->
        <div class="col-span-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">My Calendar Timeline</h2>
                    
                    <!-- Week Navigation -->
                    <div class="flex items-center gap-3">
                        <button onclick="navigateWeek(-1)" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button onclick="navigateWeek(0)" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="fas fa-calendar-day mr-2"></i>
                            Today
                        </button>
                        <button onclick="navigateWeek(1)" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Week of <span id="currentWeek"></span>
                </p>
                
                @if(!$integration || !$integration->is_connected)
                <!-- Calendar Not Connected Banner -->
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <i class="fab fa-google text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-blue-900 mb-1">Google Calendar Not Connected</h4>
                            <p class="text-sm text-blue-700 mb-3">You're viewing only your in-app tasks. Connect your Google Calendar to see all your events in one place.</p>
                            <a href="{{ url('/calendar/connect') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                <i class="fab fa-google"></i>
                                Connect Google Calendar
                            </a>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-blue-400 hover:text-blue-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Legend -->
                <div class="flex items-center gap-6 mb-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span class="text-gray-700">CMS Tasks</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
                        <span class="text-gray-700">Google Calendar Events</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-100 border-2 border-green-300 rounded mr-2"></div>
                        <span class="text-gray-700">Available</span>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div id="calendarGrid" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <!-- Calendar will be rendered here by JavaScript -->
                    <div class="flex items-center justify-center py-12 text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Loading calendar...
                    </div>
                </div>
            </div>
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
                <div class="flex items-center justify-center py-12 text-red-600">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Error loading calendar
                </div>
            `;
        });
}

function renderCalendar(slots) {
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    const hours = Array.from({length: 17}, (_, i) => i + 6); // 6 AM to 10 PM
    
    let html = '<div class="grid grid-cols-8 border-b border-gray-200">';
    
    // Header row
    html += '<div class="p-3 bg-gray-50 font-medium text-gray-700 text-sm border-r border-gray-200 sticky top-0">Time</div>';
    days.forEach((day, index) => {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + index);
        html += `<div class="p-3 bg-gray-50 font-medium text-gray-700 text-sm border-r border-gray-200 last:border-r-0 sticky top-0">
            ${day}<br>
            <span class="text-xs text-gray-500">${date.getMonth() + 1}/${date.getDate()}</span>
        </div>`;
    });
    html += '</div>';
    
    // Time slots
    hours.forEach(hour => {
        html += '<div class="grid grid-cols-8 border-b border-gray-200 last:border-b-0">';
        
        // Hour label
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        html += `<div class="p-3 bg-gray-50 text-sm text-gray-600 font-medium border-r border-gray-200">${displayHour}:00 ${ampm}</div>`;
        
        // Day cells
        days.forEach((day, dayIndex) => {
            const date = new Date(currentWeekStart);
            date.setDate(date.getDate() + dayIndex);
            const dateStr = formatDate(date);
            
            // Find slots for this time/day
            const daySlots = slots.filter(slot => slot.date === dateStr && slot.hour === hour);
            
            html += '<div class="p-2 border-r border-gray-200 last:border-r-0 min-h-[80px] relative bg-green-50">';
            
            if (daySlots.length === 0) {
                // Available slot
                html += '<div class="text-xs text-gray-400 italic">Available</div>';
            } else {
                daySlots.forEach(slot => {
                    const colorClass = slot.type === 'task' 
                        ? 'bg-blue-100 border-blue-300 text-blue-800' 
                        : 'bg-purple-100 border-purple-300 text-purple-800';
                    const syncIcon = slot.is_synced ? '<i class="fas fa-sync-alt text-xs ml-1"></i>' : '';
                    
                    // Create tooltip with full task details
                    const description = slot.description || 'No description';
                    const tooltipText = `Task: ${slot.title}&#10;Description: ${description}&#10;Days: ${slot.duration}${slot.is_synced ? '&#10;Synced with Google Calendar' : ''}`;
                    
                    html += `<div class="${colorClass} border rounded px-2 py-1 text-xs mb-1 cursor-pointer hover:shadow-md transition-shadow" 
                                  title="${tooltipText}">
                        <div class="font-semibold truncate">${slot.title} ${syncIcon}</div>
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
    const hours = taskCard.querySelector('.fa-clock').parentElement.textContent.trim();
    const deadline = taskCard.querySelector('.fa-calendar')?.parentElement.textContent.trim() || 'N/A';
    const project = taskCard.querySelector('.fa-project-diagram').parentElement.querySelector('.font-medium')?.textContent || 'N/A';
    const conflictingWith = taskCard.dataset.conflictingWith || 'Unknown Task';
    
    // If task has a conflict, show conflict modal
    if (hasConflict) {
        // Fill conflict modal with task details
        document.getElementById('conflictTaskTitle').textContent = title;
        document.getElementById('conflictDeadline').textContent = deadline.replace('', '').trim();
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
    
    // Disable button and show loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Syncing...';
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
        button.innerHTML = '<i class="fas fa-calendar-plus mr-2"></i>Sync Timeline Tasks to Google Calendar';
        
        if (data.success) {
            statusEl.textContent = `✓ Successfully synced ${data.synced_count} timeline task(s)`;
            statusEl.className = 'mt-2 text-sm text-center text-green-600';
            statusEl.classList.remove('hidden');
            
            // Reload calendar timeline to show synced tasks
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            statusEl.textContent = `✗ Error: ${data.error}`;
            statusEl.className = 'mt-2 text-sm text-center text-red-600';
            statusEl.classList.remove('hidden');
        }
    })
    .catch(error => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-calendar-plus mr-2"></i>Sync Timeline Tasks to Google Calendar';
        statusEl.textContent = `✗ Failed to sync: ${error.message}`;
        statusEl.className = 'mt-2 text-sm text-center text-red-600';
        statusEl.classList.remove('hidden');
    });
}
</script>

<!-- Calendar Connection Modal (Not Connected) -->
<div id="calendarConnectionModal" class="hidden fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Connect Your Google Calendar</h2>
            <button onclick="closeCalendarModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-6">
            <!-- Why Connect Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                    Why connect your calendar?
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span class="text-gray-700">Admins can see your real availability</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span class="text-gray-700">Tasks auto-sync to your Google Calendar</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span class="text-gray-700">Get reminders for upcoming tasks</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <span class="text-gray-700">Prevent double-booking and conflicts</span>
                    </li>
                </ul>
            </div>
            
            <!-- Privacy Section -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-lock text-blue-600"></i>
                    We only access:
                </h3>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span class="text-gray-700">Free/Busy times (not event details)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-600">•</span>
                        <span class="text-gray-700">Calendar events we create</span>
                    </li>
                </ul>
            </div>
            
            <!-- Connect Button -->
            <div class="text-center mb-4">
                <a href="{{ url('/calendar/connect') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-blue-600 text-white font-semibold text-lg rounded-lg hover:bg-blue-700 transition shadow-lg">
                    <i class="fab fa-google text-2xl"></i>
                    Connect Google Calendar
                </a>
            </div>
            
            <!-- Already Connected Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already connected? 
                    <a href="{{ url('/calendar/connection') }}" class="text-blue-600 hover:text-blue-700 font-medium">View Settings</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Calendar Connected Successfully Modal -->
<div id="calendarConnectedModal" class="hidden fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between bg-green-50">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                <h2 class="text-2xl font-bold text-gray-900">Calendar Connected Successfully</h2>
            </div>
            <button onclick="closeConnectedModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-6">
            <!-- Account Info -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Account</p>
                    <p class="font-semibold text-gray-900">{{ $integration && $integration->calendar_id ? $integration->calendar_id : 'N/A' }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    <p class="font-semibold text-green-600 flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                        Active
                    </p>
                </div>
            </div>
            
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Last Synced</p>
                <p class="font-semibold text-gray-900">
                    @if($integration && $integration->last_synced_at)
                        {{ $integration->last_synced_at->diffForHumans() }}
                    @else
                        Never
                    @endif
                </p>
            </div>
            
            <!-- Working Hours -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Working Hours (Default):</h3>
                <p class="text-gray-700 mb-1"><strong>Monday - Friday:</strong> 9:00 AM - 5:00 PM</p>
                <p class="text-gray-700"><strong>Timezone:</strong> Asia/Manila (GMT+8)</p>
            </div>
            
            <!-- Sync Info -->
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center gap-2">
                    <i class="fas fa-sync-alt text-green-600"></i>
                    Auto-Sync Features
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check text-green-600 mt-0.5"></i>
                        <span>New tasks are automatically synced when placed on timeline</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <span>Tasks in "Unscheduled" section will NOT be synced</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                        <span>Click below to sync timeline tasks added before connecting</span>
                    </li>
                </ul>
                <button onclick="syncAllTasks()" class="mt-3 w-full px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Sync Timeline Tasks to Google Calendar
                </button>
                <p id="syncStatus" class="mt-2 text-sm text-center hidden"></p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="window.location.href='{{ url('/calendar/connection') }}'" class="flex-1 px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-cog mr-2"></i>
                    Edit Working Hours
                </button>
                <form action="{{ url('/calendar/disconnect') }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to disconnect your calendar?');">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-unlink mr-2"></i>
                        Disconnect
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Conflict Modal -->
<div id="scheduleConflictModal" class="hidden fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <!-- Modal Header -->
        <div class="bg-yellow-50 border-b border-yellow-200 px-6 py-4 flex items-center justify-between rounded-t-lg">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                <h2 class="text-xl font-bold text-yellow-800">Schedule Conflict</h2>
            </div>
            <button onclick="closeConflictModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-4">
                <h3 class="font-semibold text-gray-900 mb-2" id="conflictTaskTitle">Task Title</h3>
                <p class="text-sm text-gray-600 mb-3">
                    This task has the same deadline as another task assigned to you.
                </p>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800 font-medium mb-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Deadline Conflict Details:
                </p>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Deadline:</strong> <span id="conflictDeadline">-</span></p>
                    <p><strong>Estimated Hours:</strong> <span id="conflictHours">-</span> hours</p>
                    <p><strong>Project:</strong> <span id="conflictProject">-</span></p>
                </div>
            </div>

            <div class="bg-red-50 border-l-4 border-red-500 rounded p-4 mb-6">
                <p class="text-sm text-red-800 font-medium mb-1">
                    <i class="fas fa-calendar-times mr-1"></i>
                    Conflicts with:
                </p>
                <p class="text-sm text-red-900 font-semibold" id="conflictingTaskName">-</p>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                You have multiple tasks due on the same day. Please contact your admin to reschedule or adjust the deadline.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="closeConflictModal()" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-check mr-2"></i>
                    Understood
                </button>
                <button onclick="closeConflictModal()" class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
