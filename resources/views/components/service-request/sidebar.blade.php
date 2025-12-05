@props(['showContactLink' => true])

<div class="sticky top-24 space-y-6">
    <!-- How It Works -->
    <x-ui.card>
        <h3 class="text-base font-medium text-neutral-800 mb-4">How It Works</h3>
        <div class="space-y-4">
            <div class="flex gap-3">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-primary-600">1</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-700">Submit Your Request</p>
                    <p class="text-xs text-neutral-500 mt-0.5">Tell us about your project needs</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-primary-600">2</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-700">Get Matched</p>
                    <p class="text-xs text-neutral-500 mt-0.5">We'll find the perfect Adiutor for you</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="flex-shrink-0 w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-primary-600">3</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-700">Start Collaborating</p>
                    <p class="text-xs text-neutral-500 mt-0.5">Work together to bring your project to life</p>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- What to Expect -->
    <x-ui.card>
        <h3 class="text-base font-medium text-neutral-800 mb-4">What to Expect</h3>
        <ul class="space-y-3">
            <li class="flex items-start gap-2">
                <x-lucide-clock class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                <span class="text-sm text-neutral-600">Response within 24 hours</span>
            </li>
            <li class="flex items-start gap-2">
                <x-lucide-message-circle class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                <span class="text-sm text-neutral-600">Free initial consultation</span>
            </li>
            <li class="flex items-start gap-2">
                <x-lucide-shield-check class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                <span class="text-sm text-neutral-600">Secure & confidential</span>
            </li>
            <li class="flex items-start gap-2">
                <x-lucide-credit-card class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                <span class="text-sm text-neutral-600">Flexible payment options</span>
            </li>
        </ul>
    </x-ui.card>

    <!-- Need Help? -->
    @if($showContactLink)
    <x-ui.card>
        <div class="flex items-start gap-3">
            <div class="p-2 bg-neutral-50 rounded-lg flex-shrink-0">
                <x-lucide-help-circle class="w-5 h-5 text-neutral-500" />
            </div>
            <div>
                <h3 class="text-sm font-medium text-neutral-800">Need Help?</h3>
                <p class="text-xs text-neutral-500 mt-1">Have questions before submitting? We're here to help.</p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 mt-2 transition-colors">
                    Contact Us
                    <x-lucide-arrow-right class="w-3 h-3" />
                </a>
            </div>
        </div>
    </x-ui.card>
    @endif
</div>