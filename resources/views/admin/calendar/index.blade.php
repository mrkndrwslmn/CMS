@extends('admin.layouts.app')

@section('title', 'Calendar Management')
@section('page-title', 'Calendar Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Calendar Management</h1>
        <p class="text-gray-600 mt-2">View and manage project schedules and assigned adiutors</p>
    </div>

    <!-- Projects List -->
    <div class="space-y-4">
        @foreach($projects as $project)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <!-- Project Info -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="font-semibold text-lg text-gray-900 mr-3">{{ $project['project_name'] }}</h3>
                                @if($project['status'] === 'active')
                                    <span class="inline-flex items-center text-xs text-green-700 bg-green-100 px-2 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs text-blue-700 bg-blue-100 px-2 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                        In Progress
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-user mr-1"></i>
                                Client: <span class="font-medium">{{ $project['client_name'] }}</span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($project['start_date'])->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($project['deadline'])->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Assigned Adiutors -->
                    @if($project['adiutors']->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Assigned Adiutors:</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($project['adiutors'] as $adiutor)
                                    <div class="flex items-center bg-gray-50 rounded-lg px-3 py-2">
                                        <img src="{{ $adiutor['avatar'] }}" alt="{{ $adiutor['name'] }}" class="w-8 h-8 rounded-full object-cover mr-2">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $adiutor['name'] }}</p>
                                            @if($adiutor['calendar_connected'])
                                                <p class="text-xs text-green-600">
                                                    <i class="fas fa-check-circle mr-1"></i>Calendar Synced
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-500">
                                                    <i class="fas fa-times-circle mr-1"></i>Not Connected
                                                </p>
                                            @endif
                                        </div>
                                        <button onclick="viewAdiutorSchedule({{ $adiutor['id'] }}, '{{ $adiutor['name'] }}')" 
                                                class="ml-3 text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-calendar-alt"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 italic">No adiutors assigned yet</p>
                        </div>
                    @endif

                    <!-- View Project Details -->
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.projects.schedule', $project['id']) }}" 
                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Schedule Tasks
                        </a>
                        <a href="{{ route('admin.projects.show', $project['id']) }}" 
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                            <i class="fas fa-eye mr-2"></i>
                            View Project Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($projects->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-project-diagram text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Projects</h3>
            <p class="text-gray-600">There are no active or in-progress projects to display.</p>
        </div>
    @endif
</div>

<!-- Schedule Modal (same as in projects/show.blade.php) -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Schedule Overview</h3>
                <p class="text-sm text-gray-600 mt-1">
                    <span id="scheduleName"></span> - Week of <span id="scheduleWeek"></span>
                </p>
            </div>
            <button onclick="hideScheduleModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
            <!-- Calendar Navigation -->
            <div class="flex items-center justify-between mb-6">
                <button onclick="navigateWeek(-1)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                    <i class="fas fa-chevron-left mr-2"></i>
                    Previous Week
                </button>
                <button onclick="navigateWeek(0)" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-calendar-day mr-2"></i>
                    Today
                </button>
                <button onclick="navigateWeek(1)" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                    Next Week
                    <i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>

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
            </div>

            <!-- Calendar Grid -->
            <div id="scheduleCalendar" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <!-- Calendar will be rendered here by JavaScript -->
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="hideScheduleModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
let currentAdiutorId = null;
let currentAdiutorName = '';
let currentWeekStart = null;

function viewAdiutorSchedule(adiutorId, adiutorName) {
    currentAdiutorId = adiutorId;
    currentAdiutorName = adiutorName;
    currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay() + 1); // Monday
    
    document.getElementById('scheduleName').textContent = adiutorName;
    document.getElementById('scheduleModal').classList.remove('hidden');
    loadSchedule();
}

function hideScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    currentAdiutorId = null;
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
    loadSchedule();
}

function loadSchedule() {
    const startDate = formatDate(currentWeekStart);
    document.getElementById('scheduleWeek').textContent = formatDateDisplay(currentWeekStart);
    
    fetch(`/api/schedule/adiutor/${currentAdiutorId}/timeline?start_date=${startDate}`)
        .then(response => response.json())
        .then(data => {
            renderSchedule(data.slots);
        })
        .catch(error => {
            console.error('Error loading schedule:', error);
        });
}

function renderSchedule(slots) {
    const calendar = document.getElementById('scheduleCalendar');
    const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    const hours = Array.from({length: 10}, (_, i) => i + 8); // 8 AM to 6 PM
    
    let html = '<div class="grid grid-cols-6 border-b border-gray-200">';
    
    // Header row
    html += '<div class="p-3 bg-gray-50 font-medium text-gray-700 text-sm border-r border-gray-200">Time</div>';
    days.forEach((day, index) => {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + index);
        html += `<div class="p-3 bg-gray-50 font-medium text-gray-700 text-sm border-r border-gray-200 last:border-r-0">
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
            
            html += '<div class="p-2 border-r border-gray-200 last:border-r-0 min-h-[60px] relative">';
            
            daySlots.forEach(slot => {
                const colorClass = slot.type === 'task' ? 'bg-blue-100 border-blue-300 text-blue-800' : 'bg-purple-100 border-purple-300 text-purple-800';
                html += `<div class="${colorClass} border rounded px-2 py-1 text-xs mb-1">
                    <div class="font-semibold truncate">${slot.title}</div>
                    <div class="text-xs opacity-75">${slot.duration}</div>
                </div>`;
            });
            
            html += '</div>';
        });
        
        html += '</div>';
    });
    
    calendar.innerHTML = html;
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

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideScheduleModal();
    }
});
</script>
@endsection
