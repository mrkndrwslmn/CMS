@props(['credentials' => null, 'loginRoute' => null])

@if($credentials)
<div class="mb-8">
    <x-ui.card>
        <div class="flex gap-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-success-50 flex items-center justify-center">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-medium text-neutral-800 mb-2">Request Submitted Successfully!</h3>
                <p class="text-sm text-neutral-600 mb-4">Your account has been created. Here are your credentials:</p>
                <div class="bg-neutral-50 p-4 rounded-lg border border-neutral-200">
                    <p class="text-sm text-neutral-700"><span class="font-medium">Email:</span> {{ $credentials['email'] ?? '' }}</p>
                    <p class="text-sm text-neutral-700 mt-1"><span class="font-medium">Password:</span> <code class="bg-neutral-100 px-2 py-0.5 rounded text-neutral-800">{{ $credentials['password'] ?? '' }}</code></p>
                </div>
                @if(session('email_failed'))
                    <div class="flex items-center gap-2 mt-4 text-sm text-warning-600">
                        <x-lucide-alert-triangle class="w-4 h-4" />
                        <span>{{ session('warning', 'We couldn\'t send the email. Please save these credentials now!') }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 mt-4 text-sm text-success-600">
                        <x-lucide-mail class="w-4 h-4" />
                        <span>These credentials have also been sent to your email.</span>
                    </div>
                @endif
                @if($loginRoute)
                <div class="mt-4">
                    <x-ui.button href="{{ $loginRoute }}" variant="primary" size="sm">
                        <x-lucide-log-in class="w-4 h-4" />
                        Login Now
                    </x-ui.button>
                </div>
                @endif
            </div>
        </div>
    </x-ui.card>
</div>
@endif