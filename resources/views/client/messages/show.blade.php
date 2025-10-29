@extends('client.layout')

@section('title', 'Messages - ' . $project->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a href="{{ route('client.dashboard') }}" class="text-gray-600 hover:text-primary-600 transition-colors">
                    Dashboard
                </a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="{{ route('client.messages.index') }}" class="text-gray-600 hover:text-primary-600 transition-colors">
                    Messages
                </a>
            </li>
            <li>
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900 font-medium truncate">{{ $project->title }}</li>
        </ol>
    </nav>

    <!-- Chat Container -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-primary-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                        </svg>
                        <h1 class="text-xl font-bold text-white truncate">{{ $project->title }}</h1>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-white/90">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                            </svg>
                            Project #{{ $project->id }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.projects.show', $project->id) }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-white text-primary-600 rounded-lg hover:bg-gray-50 transition-colors text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    View Project
                </a>
            </div>
        </div>

        <!-- Meeting Actions Bar -->
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <button type="button" 
                    onclick="openScheduleMeetingModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Schedule a Meeting
            </button>
        </div>

        <!-- Pinned Meeting Card (if exists) -->
        <div id="pinned-meeting-container"></div>

        <!-- Messages Container -->
        <div id="messages-container" 
             class="h-[600px] overflow-y-auto bg-gray-50 p-6 scroll-smooth">
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary-100 mb-3">
                        <svg class="w-6 h-6 text-primary-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Loading messages...</p>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="border-t border-gray-200 bg-white p-6">
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none text-sm placeholder-gray-400"></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                <span id="char-count" class="font-medium">0</span>/5000 characters
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <label for="attachments" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                Attach Files
                            </label>
                            <input 
                                type="file" 
                                id="attachments" 
                                name="attachments[]" 
                                multiple 
                                class="hidden"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                            <span class="text-xs text-gray-500">Max 10MB per file</span>
                        </div>
                        
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-semibold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                            </svg>
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
<div id="scheduleMeetingModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-xl border border-gray-200 max-w-md w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-primary-600 px-6 py-4 rounded-t-xl flex items-center justify-between">
            <h3 class="text-lg font-bold text-white">Schedule a Meeting</h3>
            <button type="button" onclick="closeScheduleMeetingModal()" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="schedule-meeting-form" class="p-6 space-y-4">
            <!-- Meeting Title -->
            <div>
                <label for="meeting-title" class="block text-sm font-medium text-gray-700 mb-1">
                    Meeting Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="meeting-title" 
                       required
                       maxlength="255"
                       placeholder="e.g., Project Review Meeting"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Preferred Date -->
            <div>
                <label for="meeting-date" class="block text-sm font-medium text-gray-700 mb-1">
                    Preferred Date <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       id="meeting-date" 
                       required
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Preferred Time -->
            <div>
                <label for="meeting-time" class="block text-sm font-medium text-gray-700 mb-1">
                    Preferred Time <span class="text-red-500">*</span>
                </label>
                <input type="time" 
                       id="meeting-time" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            </div>

            <!-- Meeting Agenda/Description -->
            <div>
                <label for="meeting-description" class="block text-sm font-medium text-gray-700 mb-1">
                    Meeting Agenda (Optional)
                </label>
                <textarea id="meeting-description" 
                          rows="3" 
                          maxlength="1000"
                          placeholder="What would you like to discuss?"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"></textarea>
                <p class="text-xs text-gray-500 mt-1">Max 1000 characters</p>
            </div>

            <!-- Info Box -->
            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-primary-800">
                        <p class="font-semibold">Meeting Request Process</p>
                        <ul class="mt-2 space-y-1 text-xs">
                            <li>• Your request will be sent to the admin for review</li>
                            <li>• Admin can approve, reschedule, or decline</li>
                            <li>• You'll receive a notification with the status</li>
                            <li>• Zoom link will be generated upon approval</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-4">
                <button type="button" 
                        onclick="closeScheduleMeetingModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold">
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
        const headerClass = isRescheduled ? 'text-amber-700' : 'text-primary-700';
        const bgClass = isRescheduled ? 'from-amber-50 to-orange-50 border-amber-200' : 'from-primary-50 to-accent-50 border-primary-200';
        
        container.innerHTML = `
            <div class="bg-gradient-to-r ${bgClass} border-2 rounded-xl p-6 shadow-lg mb-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 ${isRescheduled ? 'text-amber-600' : 'text-primary-600'}" fill="currentColor" viewBox="0 0 20 20">
                                ${isRescheduled ? 
                                    '<path d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"></path>' :
                                    '<path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>'
                                }
                            </svg>
                            <span class="text-xs font-semibold ${headerClass} uppercase tracking-wide">${headerText}</span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">${escapeHtml(meeting.title)}</h4>
                        <div class="flex flex-col gap-2 text-sm text-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">${formatDateTime(scheduledDateTime)}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="meeting-countdown-${meeting.id}" class="${isRescheduled ? 'text-amber-600' : 'text-accent-600'} font-semibold"></span>
                            </div>
                        </div>
                        ${meeting.description ? `<p class="mt-3 text-sm text-gray-600 line-clamp-2">${escapeHtml(meeting.description)}</p>` : ''}
                    </div>
                    <div class="flex flex-col gap-2">
                        ${isRescheduled ? `
                            <div class="flex flex-col gap-2">
                                <button onclick="approveReschedule(${meeting.id})" 
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition-all text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Approve Time
                                </button>
                                <button onclick="rejectReschedule(${meeting.id})" 
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition-all text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Decline
                                </button>
                            </div>
                        ` : `
                            <a href="${meeting.zoom_join_url}" 
                               target="_blank"
                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                </svg>
                                Join Meeting
                            </a>
                            ${meeting.zoom_password ? `
                                <div class="text-xs text-center">
                                    <span class="text-gray-600">Password:</span>
                                    <code class="ml-1 px-2 py-1 bg-white rounded font-mono">${meeting.zoom_password}</code>
                                </div>
                            ` : ''}
                        `}
                    </div>
                </div>
                ${meeting.admin_notes ? `
                    <div class="mt-4 p-3 bg-white/60 rounded-lg border ${isRescheduled ? 'border-amber-200' : 'border-primary-200'}">
                        <p class="text-xs font-semibold text-gray-700 mb-1">${isRescheduled ? 'Reschedule Reason:' : 'Admin Note:'}</p>
                        <p class="text-sm text-gray-600">${escapeHtml(meeting.admin_notes)}</p>
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
                <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Failed to load messages</h3>
                            <p class="text-sm text-red-600 mt-1">Please refresh the page to try again.</p>
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
                    <svg class="w-16 h-16 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-neutral-600 font-medium">No messages yet</p>
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
                   class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors ${isSender ? 'bg-primary-100 text-primary-800 hover:bg-primary-200' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
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
                            <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-700">${escapeHtml(message.sender.fullName)}</span>
                        </div>
                    ` : ''}
                    <div class="rounded-2xl px-4 py-3 ${isSender ? 'bg-primary-600 text-white rounded-br-md' : 'bg-white border border-gray-200 text-gray-900 rounded-bl-md'}">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap wrap-break-word">${escapeHtml(message.message)}</p>
                        ${attachmentsHtml}
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5 px-1 text-xs text-gray-500 ${isSender ? 'justify-end' : 'justify-start'}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span>${formatTime(message.created_at)}</span>
                        ${message.status === 'read' && isSender ? `
                            <svg class="w-3.5 h-3.5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
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
