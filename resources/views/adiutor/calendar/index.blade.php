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
                    
                    <form action="{{ url('/calendar/disconnect') }}" method="POST" onsubmit="return window.Alerts.confirmDeleteForm(event, 'Disconnect Calendar', 'Are you sure you want to disconnect your calendar? Scheduled tasks will not sync anymore.')">
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
        if (data.success) {
            window.toast.success(`Connection Successful! Found ${data.events_count} events this week.`);
        } else {
            window.toast.error(`Connection Failed: ${data.error}`);
        }
    })
    .catch(error => {
        window.toast.error('Failed to test connection: ' + error.message);
    });
}
</script>
@endsection
