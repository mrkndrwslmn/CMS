@extends('admin.layouts.app')

@section('title', 'Calendar Management')
@section('page-title', 'Calendar Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Calendar', 'icon' => 'calendar'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Calendar Management" 
        description="View and manage project schedules and assigned adiutors"
        class="mb-6"
    />

    <!-- Projects List -->
    <div class="space-y-6">
        @forelse($projects as $project)
            <x-ui.card>
                <div class="p-6">
                    <!-- Project Info -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-lg text-neutral-800">{{ $project['project_name'] }}</h3>
                                @if($project['status'] === 'active')
                                    <x-ui.badge type="success">
                                        <span class="w-1.5 h-1.5 bg-success-500 rounded-full mr-1.5"></span>
                                        Active
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge type="info">
                                        <span class="w-1.5 h-1.5 bg-primary-500 rounded-full mr-1.5"></span>
                                        In Progress
                                    </x-ui.badge>
                                @endif
                            </div>
                            <p class="text-sm text-neutral-600 flex items-center gap-1.5">
                                <x-lucide-user class="w-4 h-4 text-neutral-400" />
                                Client: <span class="font-medium text-neutral-800">{{ $project['client_name'] }}</span>
                            </p>
                            <p class="text-sm text-neutral-600 flex items-center gap-1.5 mt-1">
                                <x-lucide-calendar class="w-4 h-4 text-neutral-400" />
                                {{ \Carbon\Carbon::parse($project['start_date'])->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($project['deadline'])->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Assigned Adiutors -->
                    @if($project['adiutors']->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-neutral-700 mb-3">Assigned Adiutors:</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($project['adiutors'] as $adiutor)
                                    <div class="flex items-center bg-neutral-50 rounded-xl px-4 py-2.5 border border-neutral-100">
                                        @if($adiutor['profilePic'])
                                            <img src="{{ $adiutor['avatar'] }}" alt="{{ $adiutor['name'] }}" class="w-8 h-8 rounded-full object-cover mr-3 ring-2 ring-neutral-100">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center mr-3">
                                                <span class="text-primary-600 text-sm font-medium">{{ substr($adiutor['name'], 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-neutral-800">{{ $adiutor['name'] }}</p>
                                            @if($adiutor['calendar_connected'])
                                                <p class="text-xs text-success-600 flex items-center gap-1">
                                                    <x-lucide-check-circle class="w-3 h-3" />
                                                    Calendar Synced
                                                </p>
                                            @else
                                                <p class="text-xs text-neutral-500 flex items-center gap-1">
                                                    <x-lucide-x-circle class="w-3 h-3" />
                                                    Not Connected
                                                </p>
                                            @endif
                                        </div>
                                        <button onclick="viewAdiutorSchedule({{ $adiutor['id'] }}, '{{ $adiutor['name'] }}')" 
                                                class="ml-3 p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                                title="View Schedule">
                                            <x-lucide-calendar-days class="w-4 h-4" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <p class="text-sm text-neutral-500 italic">No adiutors assigned yet</p>
                        </div>
                    @endif

                    <!-- View Project Details -->
                    <div class="flex gap-3 pt-4 border-t border-neutral-100">
                        <a href="{{ route('admin.projects.schedule', $project['id']) }}">
                            <x-ui.button icon="calendar-check">
                                Schedule Tasks
                            </x-ui.button>
                        </a>
                        <a href="{{ route('admin.projects.show', $project['id']) }}">
                            <x-ui.button variant="secondary" icon="eye">
                                View Project Details
                            </x-ui.button>
                        </a>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <x-ui.empty-state 
                icon="folder-kanban"
                title="No Active Projects"
                description="There are no active or in-progress projects to display."
                class="py-12"
            />
        @endforelse
    </div>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="hidden fixed inset-0 bg-neutral-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden border border-neutral-100">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between bg-neutral-50">
            <div>
                <h3 class="text-xl font-semibold text-neutral-800">Schedule Overview</h3>
                <p class="text-sm text-neutral-600 mt-1">
                    <span id="scheduleName"></span> - Week of <span id="scheduleWeek"></span>
                </p>
            </div>
            <button onclick="hideScheduleModal()" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
            <!-- Calendar Navigation -->
            <div class="flex items-center justify-between mb-6">
                <button onclick="navigateWeek(-1)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors">
                    <x-lucide-chevron-left class="w-4 h-4" />
                    Previous Week
                </button>
                <button onclick="navigateWeek(0)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                    <x-lucide-calendar class="w-4 h-4" />
                    Today
                </button>
                <button onclick="navigateWeek(1)" class="inline-flex items-center gap-2 px-4 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors">
                    Next Week
                    <x-lucide-chevron-right class="w-4 h-4" />
                </button>
            </div>

            <!-- Legend -->
            <div class="flex items-center gap-6 mb-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-primary-500 rounded mr-2"></div>
                    <span class="text-neutral-700">CMS Tasks</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-purple-500 rounded mr-2"></div>
                    <span class="text-neutral-700">Calendar Events</span>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div id="scheduleCalendar" class="bg-white border border-neutral-200 rounded-xl overflow-hidden">
                <!-- Calendar will be rendered here by JavaScript -->
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-neutral-100 bg-neutral-50 flex justify-end">
            <button onclick="hideScheduleModal()" class="px-4 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg font-medium transition-colors">
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
    
    let html = '<div class="grid grid-cols-6 border-b border-neutral-200">';
    
    // Header row
    html += '<div class="p-3 bg-neutral-50 font-medium text-neutral-700 text-sm border-r border-neutral-200">Time</div>';
    days.forEach((day, index) => {
        const date = new Date(currentWeekStart);
        date.setDate(date.getDate() + index);
        html += `<div class="p-3 bg-neutral-50 font-medium text-neutral-700 text-sm border-r border-neutral-200 last:border-r-0">
            ${day}<br>
            <span class="text-xs text-neutral-500">${date.getMonth() + 1}/${date.getDate()}</span>
        </div>`;
    });
    html += '</div>';
    
    // Time slots
    hours.forEach(hour => {
        html += '<div class="grid grid-cols-6 border-b border-neutral-200 last:border-b-0">';
        
        // Hour label
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        html += `<div class="p-3 bg-neutral-50 text-sm text-neutral-600 font-medium border-r border-neutral-200">${displayHour}:00 ${ampm}</div>`;
        
        // Day cells
        days.forEach((day, dayIndex) => {
            const date = new Date(currentWeekStart);
            date.setDate(date.getDate() + dayIndex);
            const dateStr = formatDate(date);
            
            // Find slots for this time/day
            const daySlots = slots.filter(slot => slot.date === dateStr && slot.hour === hour);
            
            html += '<div class="p-2 border-r border-neutral-200 last:border-r-0 min-h-[60px] relative">';
            
            daySlots.forEach(slot => {
                const colorClass = slot.type === 'task' ? 'bg-primary-100 border-primary-300 text-primary-800' : 'bg-purple-100 border-purple-300 text-purple-800';
                html += `<div class="${colorClass} border rounded-lg px-2 py-1 text-xs mb-1">
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
