@extends('admin.layouts.app')

@section('title', 'Task Scheduling - ' . $project->title)
@section('page-title', 'Task Scheduling')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Task Scheduling</h1>
            <a href="{{ route('admin.calendar.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-medium mb-2">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Calendar Management
            </a>
            <p class="text-gray-600">{{ $project->title }}</p>
            <p class="text-sm text-gray-500">Client: {{ $project->client ? $project->client->fullName : 'N/A' }}</p>
        </div>
        
        <!-- Adiutor Filter -->
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Filter by Adiutor:</label>
            <select id="adiutorFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Adiutors</option>
                @foreach($assignedAdiutors as $adiutor)
                    <option value="{{ $adiutor['id'] }}">{{ $adiutor['name'] }}</option>
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
                
                <div id="unscheduledTasksList" class="space-y-3">
                    @forelse($unscheduledTasks as $task)
                        <div class="task-card border rounded-lg p-4 hover:shadow-md transition-shadow {{ $task['has_conflict'] ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }}" 
                             data-task-id="{{ $task['id'] }}"
                             data-assigned-to="{{ $task['assigned_to_id'] ?? '' }}"
                             data-deadline="{{ $task['deadline'] ?? '' }}"
                             data-estimated-hours="{{ $task['estimated_hours'] ?? 8 }}"
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
                            
                            <div class="flex items-center justify-between text-sm">
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
                            
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    Assigned to: <span class="font-medium">{{ $task['assigned_to'] }}</span>
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
                    <h2 class="text-xl font-semibold text-gray-900">Calendar Timeline</h2>
                    
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
                        <span class="text-gray-700">Calendar Events</span>
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
                    This task has the same deadline as another task assigned to the same adiutor.
                </p>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-800 font-medium mb-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Deadline Conflict Details:
                </p>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Assigned to:</strong> <span id="conflictAssignedTo">-</span></p>
                    <p><strong>Deadline:</strong> <span id="conflictDeadline">-</span></p>
                    <p><strong>Estimated Hours:</strong> <span id="conflictHours">-</span> hours</p>
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
                To avoid overloading the adiutor, please choose one of the following options:
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="manuallyScheduleConflict()" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Manually Schedule This Task
                </button>
                <button onclick="reassignTask()" class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center justify-center">
                    <i class="fas fa-user-edit mr-2"></i>
                    Reassign to Different Adiutor
                </button>
                <button onclick="closeConflictModal()" class="w-full px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Task Scheduling Modal -->
<div id="scheduleTaskModal" class="hidden fixed inset-0 backdrop-blur-md bg-white/30 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Schedule Task</h2>
            <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Task Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-900 mb-2" id="modalTaskTitle">Task Title</h3>
                <p class="text-sm text-gray-600 mb-3" id="modalTaskDescription">Task description</p>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <div>
                        <i class="fas fa-clock mr-1"></i>
                        <span id="modalTaskHours">0</span> hours
                    </div>
                    <div>
                        <i class="fas fa-calendar mr-1"></i>
                        Deadline: <span id="modalTaskDeadline">N/A</span>
                    </div>
                    <div>
                        <i class="fas fa-flag mr-1"></i>
                        Priority: <span id="modalTaskPriority" class="font-medium">Medium</span>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-user mr-1"></i>
                        Currently assigned to: <span id="modalTaskAssignedTo" class="font-medium">Unassigned</span>
                    </p>
                </div>
            </div>

            <!-- Assigned Adiutors List -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Assign to Adiutor</h4>
                <div id="adiutorList" class="space-y-2">
                    @foreach($assignedAdiutors as $adiutor)
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                            <input type="radio" name="selected_adiutor" value="{{ $adiutor['id'] }}" class="mr-3 h-4 w-4 text-blue-600">
                            <div class="flex items-center flex-1">
                                <img src="{{ $adiutor['avatar'] }}" alt="{{ $adiutor['name'] }}" class="w-10 h-10 rounded-full mr-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $adiutor['name'] }}</p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas {{ $adiutor['calendar_connected'] ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} mr-1"></i>
                                        {{ $adiutor['calendar_connected'] ? 'Calendar Connected' : 'Calendar Not Connected' }}
                                    </p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Schedule Date & Time -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">Schedule Details</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time</label>
                        <input type="datetime-local" id="scheduledStart" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date & Time</label>
                        <input type="datetime-local" id="scheduledEnd" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    This will create a time block in the adiutor's calendar
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3">
                <button onclick="closeScheduleModal()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button onclick="confirmSchedule()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Assign to Adiutor
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentWeekStart = null;
let selectedAdiutorId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Monday
    
    loadCalendar();
    
    // Setup adiutor filter
    document.getElementById('adiutorFilter').addEventListener('change', function(e) {
        selectedAdiutorId = e.target.value || null;
        filterTasks();
        loadCalendar();
    });
});

function filterTasks() {
    const taskCards = document.querySelectorAll('.task-card');
    let visibleCount = 0;
    
    taskCards.forEach(card => {
        const assignedTo = card.getAttribute('data-assigned-to');
        
        if (!selectedAdiutorId || assignedTo === selectedAdiutorId) {
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
    
    // If no specific adiutor selected, load all adiutors' schedules
    let url = selectedAdiutorId 
        ? `/api/schedule/adiutor/${selectedAdiutorId}/timeline?start_date=${startDate}`
        : `/api/schedule/project/{{ $project->id }}/timeline?start_date=${startDate}`;
    
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
                    
                    // Show full title (with adiutor name) if viewing all adiutors
                    const displayTitle = selectedAdiutorId ? slot.title.split(' - ')[0] : slot.title;
                    
                    // Extract task name and adiutor for tooltip
                    const fullTaskTitle = slot.title.includes(' - ') ? slot.title.split(' - ')[0] : slot.title;
                    const adiutorName = slot.adiutor || (slot.title.includes(' - ') ? slot.title.split(' - ')[1] : 'N/A');
                    const description = slot.description || 'No description';
                    
                    html += `<div class="${colorClass} border rounded px-2 py-1 text-xs mb-1 cursor-pointer hover:shadow-md transition-shadow" 
                                  title="Task: ${fullTaskTitle}&#10;Assigned to: ${adiutorName}&#10;Description: ${description}&#10;Days: ${slot.duration}">
                        <div class="font-semibold truncate">${displayTitle}</div>
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
    const description = taskCard.querySelector('p')?.textContent || 'No description';
    const hours = taskCard.querySelector('.fa-clock').parentElement.textContent.trim();
    const deadline = taskCard.querySelector('.fa-calendar')?.parentElement.textContent.trim() || 'N/A';
    const priority = taskCard.querySelector('.rounded-full')?.textContent.trim() || 'Medium';
    const assignedTo = taskCard.querySelector('.fa-user').parentElement.querySelector('.font-medium')?.textContent || 'Unassigned';
    const assignedToId = taskCard.dataset.assignedTo;
    const conflictingWith = taskCard.dataset.conflictingWith || 'Unknown Task';
    
    // If task has a conflict, show conflict modal instead
    if (hasConflict) {
        // Fill conflict modal with task details
        document.getElementById('conflictTaskTitle').textContent = title;
        document.getElementById('conflictAssignedTo').textContent = assignedTo;
        document.getElementById('conflictDeadline').textContent = deadline.replace('', '').trim();
        document.getElementById('conflictHours').textContent = hours.replace(/[^\d]/g, '');
        document.getElementById('conflictingTaskName').textContent = conflictingWith;
        
        // Store task ID for later use
        document.getElementById('scheduleConflictModal').dataset.taskId = taskId;
        
        // Show conflict modal
        document.getElementById('scheduleConflictModal').classList.remove('hidden');
        return;
    }
    
    // Otherwise, show regular schedule modal for tasks without deadline
    // Fill modal with task details
    document.getElementById('modalTaskTitle').textContent = title;
    document.getElementById('modalTaskDescription').textContent = description;
    document.getElementById('modalTaskHours').textContent = hours.replace(/[^\d]/g, '');
    document.getElementById('modalTaskDeadline').textContent = deadline.replace('', '').trim();
    document.getElementById('modalTaskPriority').textContent = priority;
    document.getElementById('modalTaskAssignedTo').textContent = assignedTo;
    
    // Pre-select the assigned adiutor if exists
    if (assignedToId) {
        const radio = document.querySelector(`input[name="selected_adiutor"][value="${assignedToId}"]`);
        if (radio) radio.checked = true;
    }
    
    // Set dates based on task deadline
    const taskDeadline = taskCard.dataset.deadline;
    const estimatedHours = parseInt(taskCard.dataset.estimatedHours) || 8;
    
    let startDate, endDate;
    
    if (taskDeadline) {
        // Use the deadline date, set end time to deadline
        endDate = new Date(taskDeadline);
        // If deadline has no time, set to 5 PM
        if (endDate.getHours() === 0 && endDate.getMinutes() === 0) {
            endDate.setHours(17, 0, 0, 0);
        }
        
        // Start on the same day as deadline (9 AM)
        startDate = new Date(endDate);
        startDate.setHours(9, 0, 0, 0);
        
        // If estimated hours is less than 8, calculate actual start time
        if (estimatedHours < 8) {
            const calculatedStart = new Date(endDate);
            calculatedStart.setHours(calculatedStart.getHours() - estimatedHours);
            
            // Use calculated start if it's after 9 AM
            if (calculatedStart.getHours() >= 9) {
                startDate = calculatedStart;
            }
        }
    } else {
        // Fallback: use tomorrow 9 AM to 5 PM
        startDate = new Date();
        startDate.setDate(startDate.getDate() + 1);
        startDate.setHours(9, 0, 0, 0);
        endDate = new Date(startDate);
        endDate.setHours(17, 0, 0, 0);
    }
    
    document.getElementById('scheduledStart').value = formatDateTimeLocal(startDate);
    document.getElementById('scheduledEnd').value = formatDateTimeLocal(endDate);
    
    // Store task ID for later use
    document.getElementById('scheduleTaskModal').dataset.taskId = taskId;
    
    // Show modal
    document.getElementById('scheduleTaskModal').classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('scheduleTaskModal').classList.add('hidden');
}

function confirmSchedule() {
    console.log('===== confirmSchedule called =====');
    
    const modal = document.getElementById('scheduleTaskModal');
    const taskId = modal.dataset.taskId;
    const selectedAdiutor = document.querySelector('input[name="selected_adiutor"]:checked');
    const startTime = document.getElementById('scheduledStart').value;
    const endTime = document.getElementById('scheduledEnd').value;
    
    console.log('Modal:', modal);
    console.log('Task ID:', taskId);
    console.log('Selected Adiutor:', selectedAdiutor);
    console.log('Selected Adiutor Value:', selectedAdiutor ? selectedAdiutor.value : 'NONE');
    console.log('Start Time:', startTime);
    console.log('End Time:', endTime);
    
    if (!selectedAdiutor) {
        alert('Please select an adiutor');
        return;
    }
    
    if (!startTime || !endTime) {
        alert('Please select start and end times');
        return;
    }
    
    console.log('About to send request to /api/schedule/task');
    
    // Send to backend
    fetch('/api/schedule/task', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            task_id: taskId,
            adiutor_id: selectedAdiutor.value,
            scheduled_start: startTime,
            scheduled_end: endTime
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text().then(text => {
            console.log('Response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Server returned invalid JSON: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        console.log('Parsed data:', data);
        if (data.success) {
            alert('Task scheduled successfully!');
            console.log('SUCCESS RESPONSE:', JSON.stringify(data, null, 2));
            closeScheduleModal();
            location.reload(); // Refresh to update the view
        } else {
            alert('Error: ' + (data.message || 'Failed to schedule task'));
            console.error('ERROR RESPONSE:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while scheduling the task: ' + error.message);
    });
}

function formatDateTimeLocal(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
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

function closeConflictModal() {
    document.getElementById('scheduleConflictModal').classList.add('hidden');
}

function manuallyScheduleConflict() {
    // Get task ID from conflict modal
    const taskId = document.getElementById('scheduleConflictModal').dataset.taskId;
    
    // Close conflict modal
    closeConflictModal();
    
    // Open regular schedule modal to manually set time
    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    if (taskCard) {
        // Call scheduleTask with hasConflict=false to show regular modal
        scheduleTask(taskId, false);
    }
}

function reassignTask() {
    const taskId = document.getElementById('scheduleConflictModal').dataset.taskId;
    
    // Close conflict modal
    closeConflictModal();
    
    // Open regular schedule modal with different adiutor selection
    const taskCard = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    if (taskCard) {
        // Call scheduleTask with hasConflict=false to show regular modal
        // User can select a different adiutor
        scheduleTask(taskId, false);
        
        // Show a hint to select a different adiutor
        setTimeout(() => {
            alert('Please select a different adiutor to avoid the conflict.');
        }, 300);
    }
}
</script>
@endsection
