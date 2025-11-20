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
        
        <!-- Project Filter -->
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Filter by Project:</label>
            <select id="projectFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Projects</option>
                @foreach($activeProjects as $project)
                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                @endforeach
            </select>
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
                        <div class="task-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow" 
                             data-task-id="{{ $task['id'] }}"
                             data-project-id="{{ $task['project_id'] }}">
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
                            
                            <button onclick="scheduleTask({{ $task['id'] }})" class="mt-3 w-full px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
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
                <div class="relative">
                    <div id="calendarGrid" class="bg-white border border-gray-200 rounded-lg overflow-hidden {{ !$integration || !$integration->is_connected ? 'blur-sm' : '' }}">
                        <!-- Calendar will be rendered here by JavaScript -->
                        <div class="flex items-center justify-center py-12 text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Loading calendar...
                        </div>
                    </div>
                    
                    @if(!$integration || !$integration->is_connected)
                    <!-- Overlay for non-connected calendar -->
                    <div class="absolute inset-0 flex items-center justify-center bg-white/30 backdrop-blur-sm">
                        <div class="text-center">
                            <i class="fas fa-calendar-times text-6xl text-gray-400 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Connect Your Google Calendar</h3>
                            <p class="text-gray-600 mb-6 max-w-md">Sync your schedule with Google Calendar to see all your events and manage your time efficiently.</p>
                            <button onclick="showCalendarModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-lg">
                                <i class="fab fa-google"></i>
                                Connect to Google Calendar
                            </button>
                        </div>
                    </div>
                    @endif
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
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    const hours = Array.from({length: 17}, (_, i) => i + 6); // 6 AM to 10 PM
    
    let html = '<div class="grid grid-cols-6 border-b border-gray-200">';
    
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
        html += '<div class="grid grid-cols-6 border-b border-gray-200 last:border-b-0">';
        
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
                    html += `<div class="${colorClass} border rounded px-2 py-1 text-xs mb-1">
                        <div class="font-semibold truncate">${slot.title} ${syncIcon}</div>
                        <div class="text-xs opacity-75">${slot.duration}</div>
                    </div>`;
                });
            }
            
            html += '</div>';
        });
        
        html += '</div>';
    });
    
    document.getElementById('calendarGrid').innerHTML = html;
}

function scheduleTask(taskId) {
    console.log('scheduleTask called with taskId:', taskId);
    
    // Get task details
    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    console.log('Found task card:', taskCard);
    
    if (!taskCard) {
        console.error('Task card not found for ID:', taskId);
        return;
    }
    
    const taskTitle = taskCard.querySelector('h4').textContent;
    // Find the duration from the first div with clock icon
    const durationText = taskCard.querySelector('.flex.items-center.justify-between .text-gray-600')?.textContent || '4 hours';
    const estimatedHours = durationText.match(/\d+/)?.[0] || '4';
    
    console.log('Task title:', taskTitle, 'Duration:', estimatedHours);
    
    // For demo: show conflict modal
    showConflictModal(taskTitle, estimatedHours);
}

function showConflictModal(taskTitle, estimatedHours) {
    console.log('showConflictModal called');
    const modal = document.getElementById('scheduleConflictModal');
    console.log('Modal element:', modal);
    
    if (!modal) {
        console.error('Modal not found!');
        return;
    }
    
    document.getElementById('conflictModalTaskTitle').textContent = taskTitle;
    document.getElementById('conflictModalDuration').textContent = estimatedHours;
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    console.log('Modal should now be visible');
}

function closeConflictModal() {
    document.getElementById('scheduleConflictModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function applySuggestion() {
    // TODO: Apply the suggested time slot
    alert('Suggestion applied! Task will be scheduled after the meeting.');
    closeConflictModal();
}

function chooseManually() {
    // TODO: Open time picker
    alert('Manual time selection will open here');
    closeConflictModal();
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
                <a href="{{ route('calendar.connect') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-blue-600 text-white font-semibold text-lg rounded-lg hover:bg-blue-700 transition shadow-lg">
                    <i class="fab fa-google text-2xl"></i>
                    Connect Google Calendar
                </a>
            </div>
            
            <!-- Already Connected Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already connected? 
                    <a href="{{ route('calendar.connection') }}" class="text-blue-600 hover:text-blue-700 font-medium">View Settings</a>
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
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="window.location.href='{{ route('calendar.connection') }}'" class="flex-1 px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-cog mr-2"></i>
                    Edit Working Hours
                </button>
                <form action="{{ route('calendar.disconnect') }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to disconnect your calendar?');">
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

<!-- Scheduling Conflict Detection Modal -->
<div id="scheduleConflictModal" class="hidden fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <!-- Modal Header -->
        <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between bg-yellow-50">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl"></i>
                <h2 class="text-2xl font-bold text-gray-900">⚠️ Scheduling Conflict Detected</h2>
            </div>
            <button onclick="closeConflictModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="px-6 py-6">
            <!-- Task Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Task:</p>
                <p class="text-lg font-semibold text-gray-900" id="conflictModalTaskTitle">API Setup</p>
                <p class="text-sm text-gray-600 mt-2">
                    Duration: <span id="conflictModalDuration" class="font-medium">4</span> hours
                </p>
                <p class="text-sm text-gray-600">
                    Attempted Time: <span class="font-medium">Tue, Nov 2, 9:00 AM - 1:00 PM</span>
                </p>
            </div>
            
            <!-- Conflict Details -->
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <h3 class="text-lg font-semibold text-red-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-calendar-times"></i>
                    Conflict:
                </h3>
                <div class="ml-6">
                    <p class="text-red-800 mb-1">
                        <strong>⚫ Google Calendar Event:</strong> "Client Meeting"
                    </p>
                    <p class="text-red-700 text-sm">
                        Time: 9:00 AM - 10:00 AM
                    </p>
                </div>
            </div>
            
            <!-- Suggestions -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Suggestions:</h3>
                <div class="space-y-3">
                    <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded cursor-pointer hover:bg-green-100 transition" onclick="applySuggestion()">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-gray-900">○ Schedule after meeting: 10:00 AM - 2:00 PM</p>
                                <p class="text-sm text-gray-600 mt-1">Best option - Full 4-hour block available</p>
                            </div>
                            <span class="text-green-600 text-xl">✅</span>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-gray-50 border-l-4 border-gray-300 rounded cursor-pointer hover:bg-gray-100 transition">
                        <p class="font-medium text-gray-900">○ Split into 2 sessions: 10-12 AM + 1-3 PM</p>
                        <p class="text-sm text-gray-600 mt-1">Includes lunch break in between</p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 border-l-4 border-gray-300 rounded cursor-pointer hover:bg-gray-100 transition">
                        <p class="font-medium text-gray-900">○ Choose different day (Wednesday 9 AM is free)</p>
                        <p class="text-sm text-gray-600 mt-1">Postpone to next available day</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button onclick="applySuggestion()" class="flex-1 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-check mr-2"></i>
                    Apply Suggestion
                </button>
                <button onclick="chooseManually()" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Choose Manually
                </button>
                <button onclick="closeConflictModal()" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
