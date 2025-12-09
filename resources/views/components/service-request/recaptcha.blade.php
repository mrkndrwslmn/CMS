@if(app(\App\Services\RecaptchaService::class)->isEnabled() && !auth()->check())
<x-ui.card>
    <div class="flex items-center gap-3 mb-6">
        <div class="p-2 bg-neutral-50 rounded-lg">
            <x-lucide-shield-check class="w-5 h-5 text-neutral-500" />
        </div>
        <h2 class="text-lg font-medium text-neutral-800">Security Verification</h2>
    </div>
    
    <div class="space-y-4">
        <p class="text-sm text-neutral-600">
            Please complete the security verification below to protect against spam and automated submissions.
        </p>
        
        <div class="flex justify-center">
            {!! app(\App\Services\RecaptchaService::class)->getHtml() !!}
        </div>
        
        @error('g-recaptcha-response')
            <p class="mt-1 text-sm text-error-600 text-center">{{ $message }}</p>
        @enderror
    </div>
</x-ui.card>
@endif