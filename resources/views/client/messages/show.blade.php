@extends('client.layouts.app')

@section('title', 'Messages - ' . $project->title)

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Messages', 'route' => 'client.messages.index', 'icon' => 'message-square'],
        ['label' => $project->title, 'icon' => 'folder'],
    ]" />

    <!-- Chat Container -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-primary-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <x-lucide-message-square class="w-5 h-5 text-white" />
                        <h1 class="text-lg font-semibold text-white truncate">{{ $project->title }}</h1>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-white/90">
                        <div class="flex items-center gap-1.5">
                            <x-lucide-folder class="w-4 h-4" />
                            Project #{{ $project->id }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.projects.show', $project->id) }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-white text-primary-600 rounded-lg hover:bg-neutral-50 transition-colors text-sm font-medium">
                    <x-lucide-external-link class="w-4 h-4" />
                    View Project
                </a>
            </div>
        </div>

        <!-- Meeting Actions Bar -->
        <div class="border-b border-neutral-100 bg-neutral-50 px-6 py-4">
            <button type="button" 
                    onclick="openScheduleMeetingModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                <x-lucide-calendar class="w-4 h-4" />
                Schedule a Meeting
            </button>
        </div>

        <!-- Pinned Meeting Card (if exists) -->
        <div id="pinned-meeting-container"></div>

        <!-- Messages Container -->
        <div id="messages-container" 
             class="h-[600px] overflow-y-auto bg-neutral-50 p-6 scroll-smooth">
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-50 mb-3">
                        <x-lucide-loader-2 class="w-6 h-6 text-primary-600 animate-spin" />
                    </div>
                    <p class="text-sm text-neutral-500">Loading messages...</p>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="border-t border-neutral-100 bg-white p-6">
            <form id="message-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <textarea 
                            id="message-textarea"
                            name="message" 
                            rows="3" 
                            placeholder="Type your message here... (Press Enter to send, Shift+Enter for new line)"
                            required
                            maxlength="5000"
                            class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none text-sm placeholder-neutral-400"></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs text-neutral-500">
                                <span id="char-count" class="font-medium">0</span>/5000 characters
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <label for="attachments" class="inline-flex items-center gap-2 px-4 py-2 border border-neutral-200 rounded-lg hover:bg-neutral-50 cursor-pointer transition-colors text-sm font-medium text-neutral-700">
                                <x-lucide-paperclip class="w-4 h-4" />
                                Attach Files
                            </label>
                            <input 
                                type="file" 
                                id="attachments" 
                                name="attachments[]" 
                                multiple 
                                class="hidden"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                            <span class="text-xs text-neutral-500">Max 10MB per file</span>
                        </div>
                        
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium">
                            <x-lucide-send class="w-4 h-4" />
                            Send Message
                        </button>
                    </div>
                    
                    <div id="selected-files" class="flex flex-wrap gap-2"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div id="scheduleMeetingModal" class="fixed inset-0 bg-neutral-900/50 z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-primary-600 px-6 py-4 rounded-t-2xl flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Schedule a Meeting</h3>
            <button type="button" onclick="closeScheduleMeetingModal()" class="text-white/80 hover:text-white transition-colors">
                <x-lucide-x class="w-5 h-5" />
            </button>
        </div>

        <!-- Modal Body -->
        <form id="schedule-meeting-form" class="p-6 space-y-4">
            <!-- Meeting Title -->
            <div>
                <label for="meeting-title" class="block text-sm font-medium text-neutral-700 mb-1">
                    Meeting Title <span class="text-error-500">*</span>
                </label>
                <input type="text" 
                       id="meeting-title" 
                       required
                       maxlength="255"
                       placeholder="e.g., Project Review Meeting"
                       class="w-full px-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
            </div>

            <!-- Preferred Date -->
            <div>
                <label for="meeting-date" class="block text-sm font-medium text-neutral-700 mb-1">
                    Preferred Date <span class="text-error-500">*</span>
                </label>
                <input type="date" 
                       id="meeting-date" 
                       required
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full px-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
            </div>

            <!-- Preferred Time -->
            <div>
                <label for="meeting-time" class="block text-sm font-medium text-neutral-700 mb-1">
                    Preferred Time <span class="text-error-500">*</span>
                </label>
                <input type="time" 
                       id="meeting-time" 
                       required
                       class="w-full px-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
            </div>

            <!-- Meeting Agenda/Description -->
            <div>
                <label for="meeting-description" class="block text-sm font-medium text-neutral-700 mb-1">
                    Meeting Agenda (Optional)
                </label>
                <textarea id="meeting-description" 
                          rows="3" 
                          maxlength="1000"
                          placeholder="What would you like to discuss?"
                          class="w-full px-4 py-2 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 resize-none"></textarea>
                <p class="text-xs text-neutral-500 mt-1">Max 1000 characters</p>
            </div>

            <!-- Info Box -->
            <div class="bg-primary-50 border border-primary-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <x-lucide-info class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" />
                    <div class="text-sm text-primary-800">
                        <p class="font-medium">Meeting Request Process</p>
                        <ul class="mt-2 space-y-1 text-xs text-primary-700">
                            <li>Your request will be sent to the admin for review</li>
                            <li>Admin can approve, reschedule, or decline</li>
                            <li>You'll receive a notification with the status</li>
                            <li>Zoom link will be generated upon approval</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-4">
                <button type="button" 
                        onclick="closeScheduleMeetingModal()"
                        class="flex-1 px-4 py-2 border border-neutral-200 rounded-lg text-neutral-700 hover:bg-neutral-50 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Meeting Modal Functions
    function openScheduleMeetingModal() {
        document.getElementById('scheduleMeetingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeScheduleMeetingModal() {
        document.getElementById('scheduleMeetingModal').classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
        document.getElementById('schedule-meeting-form').reset();
    }

    // Handle Meeting Request Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('schedule-meeting-form');
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            
            const formData = {
                project_id: {{ $project->id }},
                title: document.getElementById('meeting-title').value,
                requested_date: document.getElementById('meeting-date').value,
                requested_time: document.getElementById('meeting-time').value,
                description: document.getElementById('meeting-description').value || null
            };
            
            try {
                const response = await fetch('/api/meetings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Show success message
                    alert('Meeting request submitted successfully! You will be notified when the admin reviews it.');
                    closeScheduleMeetingModal();
                    loadMeetings(); // Reload meetings list
                } else {
                    // Show error message
                    alert(data.message || 'Failed to submit meeting request. Please try again.');
                }
            } catch (error) {
                console.error('Error submitting meeting request:', error);
                alert('An error occurred. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
        
        // Load meetings on page load
        loadMeetings();
        
        // Refresh meetings every 30 seconds
        setInterval(loadMeetings, 30000);
    });

    // Load Meetings for the Project
    async function loadMeetings() {
        try {
            const response = await fetch('/api/meetings/projects/{{ $project->id }}', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                // The API returns { success: true, meetings: [...] }
                renderPinnedMeetings(data.meetings || []);
            }
        } catch (error) {
            console.error('Error loading meetings:', error);
        }
    }

    // Render Pinned Meetings
    function renderPinnedMeetings(meetings) {
        const container = document.getElementById('pinned-meeting-container');
        
        // Filter for upcoming approved/rescheduled meetings
        const upcomingMeetings = meetings.filter(meeting => {
            if (meeting.status !== 'approved' && meeting.status !== 'rescheduled') {
                return false;
            }
            
            // For rescheduled meetings, use rescheduled_date/time; for approved, use scheduled_date/time
            let meetingDate, meetingTime;
            if (meeting.status === 'rescheduled') {
                meetingDate = meeting.rescheduled_date;
                meetingTime = meeting.rescheduled_time;
            } else {
                meetingDate = meeting.scheduled_date;
                meetingTime = meeting.scheduled_time;
            }
            
            if (!meetingDate || !meetingTime) {
                return false;
            }
            
            // Extract date part from ISO string (YYYY-MM-DD)
            const datePart = meetingDate.split('T')[0];
            const dateTimeString = `${datePart} ${meetingTime}`;
            const meetingDateTime = new Date(dateTimeString);
            
            return meetingDateTime > new Date();
        });
        
        if (upcomingMeetings.length === 0) {
            container.innerHTML = '';
            return;
        }
        
        // Sort by scheduled/rescheduled datetime
        upcomingMeetings.sort((a, b) => {
            const getDateTime = (meeting) => {
                let meetingDate, meetingTime;
                if (meeting.status === 'rescheduled') {
                    meetingDate = meeting.rescheduled_date;
                    meetingTime = meeting.rescheduled_time;
                } else {
                    meetingDate = meeting.scheduled_date;
                    meetingTime = meeting.scheduled_time;
                }
                return new Date(meetingDate.split('T')[0] + ' ' + meetingTime);
            };
            
            return getDateTime(a) - getDateTime(b);
        });
        
        // Render the next upcoming meeting
        const meeting = upcomingMeetings[0];
        
        // Get the appropriate date and time based on status
        let displayDate, displayTime;
        if (meeting.status === 'rescheduled') {
            displayDate = meeting.rescheduled_date;
            displayTime = meeting.rescheduled_time;
        } else {
            displayDate = meeting.scheduled_date;
            displayTime = meeting.scheduled_time;
        }
        
        const datePart = displayDate.split('T')[0];
        const scheduledDateTime = new Date(datePart + ' ' + displayTime);
        
        // Different rendering based on status
        const isRescheduled = meeting.status === 'rescheduled';
        const headerText = isRescheduled ? 'Rescheduled Meeting - Pending Your Approval' : 'Upcoming Meeting';
        const headerClass = isRescheduled ? 'text-warning-700' : 'text-primary-700';
        const bgClass = isRescheduled ? 'bg-warning-50 border-warning-200' : 'bg-primary-50 border-primary-200';
        
        container.innerHTML = `
            <div class="${bgClass} border rounded-xl p-6 shadow-sm mx-6 mt-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 ${isRescheduled ? 'text-warning-600' : 'text-primary-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${isRescheduled ? 
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>' :
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>'
                                }
                            </svg>
                            <span class="text-xs font-semibold ${headerClass} uppercase tracking-wide">${headerText}</span>
                        </div>
                        <h4 class="text-base font-semibold text-neutral-800 mb-2">${escapeHtml(meeting.title)}</h4>
                        <div class="flex flex-col gap-2 text-sm text-neutral-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">${formatDateTime(scheduledDateTime)}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="meeting-countdown-${meeting.id}" class="${isRescheduled ? 'text-warning-600' : 'text-primary-600'} font-medium"></span>
                            </div>
                        </div>
                        ${meeting.description ? `<p class="mt-3 text-sm text-neutral-500 line-clamp-2">${escapeHtml(meeting.description)}</p>` : ''}
                    </div>
                    <div class="flex flex-col gap-2">
                        ${isRescheduled ? `
                            <div class="flex flex-col gap-2">
                                <button onclick="approveReschedule(${meeting.id})" 
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-success-600 hover:bg-success-700 text-white rounded-lg font-medium shadow-sm hover:shadow transition-all text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Approve Time
                                </button>
                                <button onclick="rejectReschedule(${meeting.id})" 
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-neutral-500 hover:bg-neutral-600 text-white rounded-lg font-medium shadow-sm hover:shadow transition-all text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Decline
                                </button>
                            </div>
                        ` : `
                            <a href="${meeting.zoom_join_url}" 
                               target="_blank"
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium shadow-sm hover:shadow transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Join Meeting
                            </a>
                            ${meeting.zoom_password ? `
                                <div class="text-xs text-center">
                                    <span class="text-neutral-500">Password:</span>
                                    <code class="ml-1 px-2 py-1 bg-white rounded font-mono text-neutral-700">${meeting.zoom_password}</code>
                                </div>
                            ` : ''}
                        `}
                    </div>
                </div>
                ${meeting.admin_notes ? `
                    <div class="mt-4 p-3 bg-white/60 rounded-lg border ${isRescheduled ? 'border-warning-200' : 'border-primary-100'}">
                        <p class="text-xs font-medium text-neutral-700 mb-1">${isRescheduled ? 'Reschedule Reason:' : 'Admin Note:'}</p>
                        <p class="text-sm text-neutral-600">${escapeHtml(meeting.admin_notes)}</p>
                    </div>
                ` : ''}
            </div>
        `;
        
        // Start countdown timer
        startCountdown(meeting.id, scheduledDateTime);
    }

    // Countdown Timer
    function startCountdown(meetingId, scheduledDateTime) {
        const countdownElement = document.getElementById(`meeting-countdown-${meetingId}`);
        if (!countdownElement) return;
        
        function updateCountdown() {
            const now = new Date();
            const diff = scheduledDateTime - now;
            
            if (diff <= 0) {
                countdownElement.textContent = 'Meeting is starting now!';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            let countdownText = '';
            if (days > 0) {
                countdownText = `${days}d ${hours}h ${minutes}m`;
            } else if (hours > 0) {
                countdownText = `${hours}h ${minutes}m`;
            } else {
                countdownText = `${minutes} minutes`;
            }
            
            countdownElement.textContent = `Starts in ${countdownText}`;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 60000); // Update every minute
    }

    // Utility Functions
    function formatDateTime(date) {
        return date.toLocaleString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('scheduleMeetingModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeScheduleMeetingModal();
            }
        }
    });

    // Close modal when clicking outside
    document.getElementById('scheduleMeetingModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeScheduleMeetingModal();
        }
    });
</script>
@endpush

<style>
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-in;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const projectId = {{ $project->id }};
    const messageForm = document.getElementById('message-form');
    const messageTextarea = document.getElementById('message-textarea');
    const attachmentsInput = document.getElementById('attachments');
    const selectedFilesDiv = document.getElementById('selected-files');
    const charCount = document.getElementById('char-count');
    const messagesContainer = document.getElementById('messages-container');
    const messagingService = window.messagingService;

    // Check if all required elements exist
    if (!messageForm || !messageTextarea || !attachmentsInput || !selectedFilesDiv || !charCount || !messagesContainer) {
        console.error('Required DOM elements not found');
        return;
    }

    // Load messages when page loads
    async function loadMessages() {
        try {
            const response = await fetch(`/api/messages/projects/${projectId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                renderMessages(data.messages.data);
                if (window.messagingService) {
                    await window.messagingService.updateUnreadCount();
                }
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            messagesContainer.innerHTML = `
                <div class="rounded-xl bg-error-50 border border-error-200 p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-error-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-error-800">Failed to load messages</h3>
                            <p class="text-sm text-error-600 mt-1">Please refresh the page to try again.</p>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    // Render messages
    function renderMessages(messages) {
        if (messages.length === 0) {
            messagesContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="w-14 h-14 bg-neutral-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <p class="text-neutral-700 font-medium">No messages yet</p>
                    <p class="text-neutral-500 text-sm mt-1">Start the conversation with our team!</p>
                </div>
            `;
            return;
        }

        messagesContainer.innerHTML = messages.map(msg => createMessageElement(msg)).join('');
        scrollToBottom();
    }

    // Create message HTML element with Tailwind
    function createMessageElement(message) {
        const isSender = message.sender_id === {{ auth()->id() }};
        
        let attachmentsHtml = '';
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = message.attachments.map(att => `
                <a href="/storage/${att.path}" 
                   download="${att.name}" 
                   class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors ${isSender ? 'bg-primary-100 text-primary-800 hover:bg-primary-200' : 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700'}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    ${att.name}
                </a>
            `).join('');
        }

        return `
            <div class="flex ${isSender ? 'justify-end' : 'justify-start'} mb-4 animate-fade-in">
                <div class="max-w-[70%]">
                    ${!isSender ? `
                        <div class="flex items-center gap-2 mb-1.5 px-1">
                            <div class="w-6 h-6 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-neutral-700">${escapeHtml(message.sender.fullName)}</span>
                        </div>
                    ` : ''}
                    <div class="rounded-2xl px-4 py-3 ${isSender ? 'bg-primary-600 text-white rounded-br-md' : 'bg-white border border-neutral-100 text-neutral-800 rounded-bl-md shadow-sm'}">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap wrap-break-word">${escapeHtml(message.message)}</p>
                        ${attachmentsHtml}
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5 px-1 text-xs text-neutral-400 ${isSender ? 'justify-end' : 'justify-start'}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>${formatTime(message.created_at)}</span>
                        ${message.status === 'read' && isSender ? `
                            <svg class="w-3.5 h-3.5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Helper function to format time
    function formatTime(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined });
    }

    // Scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Handle form submission
    messageForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = messageTextarea.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('message', message);

        // Add attachments
        const files = attachmentsInput.files;
        for (let i = 0; i < files.length; i++) {
            formData.append('attachments[]', files[i]);
        }

        try {
            const response = await fetch(`/api/messages/projects/${projectId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Append new message
                messagesContainer.insertAdjacentHTML('beforeend', createMessageElement(data.message));
                scrollToBottom();
                
                // Reset form
                messageForm.reset();
                selectedFilesDiv.innerHTML = '';
                charCount.textContent = '0';
            } else {
                alert('Failed to send message. Please try again.');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
        }
    });

    // Character count
    messageTextarea.addEventListener('input', (e) => {
        charCount.textContent = e.target.value.length;
    });

    // Handle file selection
    attachmentsInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        selectedFilesDiv.innerHTML = files.map(file => `
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                </svg>
                ${file.name}
            </span>
        `).join('');
    });

    // Enable Enter to send (Shift+Enter for new line)
    messageTextarea.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            messageForm.dispatchEvent(new Event('submit'));
        }
    });

    // Approve rescheduled meeting
    async function approveReschedule(meetingId) {
        try {
            const response = await fetch(`/api/meetings/${meetingId}/approve-reschedule`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                // Show success message
                alert('Meeting time approved! A Zoom link has been created.');
                // Reload meetings to update the display
                loadMeetings();
            } else {
                alert('Failed to approve meeting time. Please try again.');
            }
        } catch (error) {
            console.error('Error approving reschedule:', error);
            alert('Failed to approve meeting time. Please try again.');
        }
    }

    // Reject rescheduled meeting
    async function rejectReschedule(meetingId) {
        const reason = prompt('Please provide a reason for rejecting this time (optional):');
        if (reason === null) return; // User cancelled
        
        try {
            const response = await fetch(`/api/meetings/${meetingId}/reject-reschedule`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({
                    reason: reason || 'Time not suitable'
                })
            });

            const data = await response.json();
            
            if (data.success) {
                alert('Meeting time declined. The admin will be notified to propose a new time.');
                // Reload meetings to update the display
                loadMeetings();
            } else {
                alert('Failed to decline meeting time. Please try again.');
            }
        } catch (error) {
            console.error('Error rejecting reschedule:', error);
            alert('Failed to decline meeting time. Please try again.');
        }
    }

    // Make functions globally available
    window.approveReschedule = approveReschedule;
    window.rejectReschedule = rejectReschedule;

    // Start polling for new messages every 5 seconds
    setInterval(loadMessages, 5000);

    // Initial load
    loadMessages();
});
</script>
