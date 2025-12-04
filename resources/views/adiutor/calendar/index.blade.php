@extends('adiutor.layouts.app')

@section('title', 'Calendar Integration')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'adiutor.dashboard', 'icon' => 'home'],
        ['label' => 'Calendar Integration', 'icon' => 'calendar'],
    ]" />

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-neutral-800">Google Calendar Integration</h1>
        <p class="text-sm text-neutral-500 mt-1">Sync your CMS tasks with Google Calendar for better schedule management.</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <x-ui.alert type="success" class="mb-6" dismissible>
            {{ session('success') }}
        </x-ui.alert>
    @endif

    @if(session('error'))
        <x-ui.alert type="error" class="mb-6" dismissible>
            {{ session('error') }}
        </x-ui.alert>
    @endif

    <!-- Connection Status Card -->
    <x-ui.card class="mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center mr-4">
                    <x-lucide-calendar class="w-6 h-6 text-primary-500" />
                </div>
                <div>
                    <h2 class="text-lg font-medium text-neutral-800">Connection Status</h2>
                    <p class="text-sm text-neutral-500">Google Calendar Integration</p>
                </div>
            </div>
            
            @if($integration && $integration->is_connected)
                <x-ui.badge type="success" dot>Connected</x-ui.badge>
            @else
                <x-ui.badge type="default" dot>Not Connected</x-ui.badge>
            @endif
        </div>

        @if($integration && $integration->is_connected)
            <!-- Connected State -->
            <div class="border-t border-neutral-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-neutral-50 rounded-xl p-4">
                        <p class="text-sm text-neutral-500 mb-1">Calendar ID</p>
                        <p class="font-medium text-neutral-800">{{ $integration->calendar_id }}</p>
                    </div>
                    <div class="bg-neutral-50 rounded-xl p-4">
                        <p class="text-sm text-neutral-500 mb-1">Last Synced</p>
                        <p class="font-medium text-neutral-800">
                            @if($integration->last_synced_at)
                                {{ $integration->last_synced_at->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-ui.button variant="primary" onclick="testConnection()">
                        <x-lucide-zap class="w-4 h-4" />
                        Test Connection
                    </x-ui.button>
                    
                    <form action="{{ url('/calendar/disconnect') }}" method="POST" onsubmit="return confirm('Are you sure you want to disconnect your calendar? Scheduled tasks will not sync anymore.');">
                        @csrf
                        <x-ui.button type="submit" variant="danger">
                            <x-lucide-unlink class="w-4 h-4" />
                            Disconnect
                        </x-ui.button>
                    </form>
                </div>
            </div>
        @else
            <!-- Not Connected State -->
            <div class="border-t border-neutral-100 pt-6">
                <div class="mb-6">
                    <h3 class="text-base font-medium text-neutral-700 mb-3">Why Connect Your Calendar?</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                            <span class="text-sm text-neutral-600">Automatically sync CMS tasks to your Google Calendar</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                            <span class="text-sm text-neutral-600">Prevent double-booking with automatic conflict detection</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                            <span class="text-sm text-neutral-600">Better schedule management and time tracking</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500 mt-0.5 shrink-0" />
                            <span class="text-sm text-neutral-600">View all your commitments in one place</span>
                        </li>
                    </ul>
                </div>

                <x-ui.alert type="info" class="mb-6">
                    <strong>Privacy Note:</strong> We only access your calendar to read events for conflict detection and create events for scheduled tasks. We never access your personal data or modify existing calendar events.
                </x-ui.alert>

                <x-ui.button variant="primary" href="/calendar/connect">
                    <x-lucide-calendar-plus class="w-5 h-5" />
                    Connect Google Calendar
                </x-ui.button>
            </div>
        @endif
    </x-ui.card>

    <!-- How It Works Section -->
    <x-ui.card>
        <h3 class="text-base font-medium text-neutral-700 mb-6">How It Works</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-primary-600 font-semibold text-lg">1</span>
                </div>
                <h4 class="font-medium text-neutral-700 mb-2">Connect</h4>
                <p class="text-sm text-neutral-500">Click "Connect Google Calendar" and authorize access to your calendar.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-primary-600 font-semibold text-lg">2</span>
                </div>
                <h4 class="font-medium text-neutral-700 mb-2">Schedule Tasks</h4>
                <p class="text-sm text-neutral-500">When admins schedule tasks for you, they'll appear in your calendar.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <span class="text-primary-600 font-semibold text-lg">3</span>
                </div>
                <h4 class="font-medium text-neutral-700 mb-2">Stay Synced</h4>
                <p class="text-sm text-neutral-500">Your CMS tasks and Google Calendar stay in sync automatically.</p>
            </div>
        </div>
    </x-ui.card>
</div>

<!-- Test Connection Toast -->
<div id="testResultToast" class="hidden fixed bottom-4 right-4 bg-white rounded-2xl shadow-lg p-4 max-w-md border-l-4 transition-all duration-300 z-50">
    <div class="flex items-start">
        <div id="testResultIcon" class="flex-shrink-0 mr-3"></div>
        <div class="flex-1">
            <h4 id="testResultTitle" class="font-medium text-neutral-800 mb-1"></h4>
            <p id="testResultMessage" class="text-sm text-neutral-500"></p>
        </div>
        <button onclick="closeToast()" class="ml-4 text-neutral-400 hover:text-neutral-600 transition-colors">
            <x-lucide-x class="w-5 h-5" />
        </button>
    </div>
</div>

<script>
function testConnection() {
    fetch('{{ url('/calendar/test') }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        showToast(
            data.success,
            data.success ? 'Connection Successful!' : 'Connection Failed',
            data.success ? `Found ${data.events_count} events this week.` : data.error
        );
    })
    .catch(error => {
        showToast(false, 'Error', 'Failed to test connection: ' + error.message);
    });
}

function showToast(success, title, message) {
    const toast = document.getElementById('testResultToast');
    const icon = document.getElementById('testResultIcon');
    const titleEl = document.getElementById('testResultTitle');
    const messageEl = document.getElementById('testResultMessage');
    
    // Set border color
    toast.className = toast.className.replace(/border-l-(success|error)-500/, '');
    toast.classList.add(success ? 'border-l-success-500' : 'border-l-error-500');
    
    // Set icon using inline SVG for Lucide
    icon.innerHTML = success 
        ? '<svg class="w-6 h-6 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        : '<svg class="w-6 h-6 text-error-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    
    titleEl.textContent = title;
    messageEl.textContent = message;
    
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        closeToast();
    }, 5000);
}

function closeToast() {
    document.getElementById('testResultToast').classList.add('hidden');
}
</script>
@endsection
