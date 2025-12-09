@extends('adiutor.layouts.app')

@section('title', 'Share Referral Code')
@section('page-title', 'Share Referral Code')

@section('content')
<div class="max-w-8xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Share & Earn</h1>
                <p class="text-neutral-500 mt-2">Invite friends and both of you get rewarded!</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-8">
        <div class="border-b border-neutral-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('adiutor.referrals.dashboard') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-layout-dashboard class="w-4 h-4" />
                    Dashboard
                </a>
                <a href="{{ route('adiutor.referrals.credits') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-circle-dollar-sign class="w-4 h-4" />
                    Credits
                </a>
                <a href="{{ route('adiutor.referrals.history') }}" 
                   class="border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors inline-flex items-center gap-2">
                    <x-lucide-clock class="w-4 h-4" />
                    History
                </a>
                <a href="{{ route('adiutor.referrals.share') }}" 
                   class="border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm inline-flex items-center gap-2">
                    <x-lucide-share-2 class="w-4 h-4" />
                    Share
                </a>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Referral Link Card -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <h2 class="text-lg font-semibold text-neutral-800 mb-6">Your Unique Referral Link</h2>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-600 mb-2">Referral Code</label>
                    <div class="flex gap-2">
                        <input type="text" class="flex-1 px-4 py-3 text-lg font-mono font-medium text-primary-600 bg-primary-50 border border-primary-100 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="referralCode" value="{{ $referralCode->code }}" readonly>
                        <button class="px-5 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors" type="button" onclick="copyCode()">
                            <x-lucide-copy class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-neutral-600 mb-2">Referral URL</label>
                    <div class="flex gap-2">
                        <input type="text" class="flex-1 px-4 py-2.5 bg-neutral-50 border border-neutral-200 rounded-lg text-neutral-700 focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="referralUrl" value="{{ $referralUrl }}" readonly>
                        <button class="px-5 py-2.5 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-colors" type="button" onclick="copyUrl()">
                            <x-lucide-link class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                <!-- Social Sharing Buttons -->
                <div>
                    <label class="block text-sm font-medium text-neutral-600 mb-3">Share on Social Media</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Email -->
                        <button type="button" class="flex items-center justify-start gap-3 px-4 py-3 bg-white border border-neutral-200 rounded-lg text-neutral-700 hover:bg-error-50 hover:border-error-200 transition-colors" onclick="shareViaEmail()">
                            <x-lucide-mail class="w-5 h-5 text-error-600" />
                            <span class="font-medium">Share via Email</span>
                        </button>
                        
                        <!-- Facebook -->
                        <button type="button" class="flex items-center justify-start gap-3 px-4 py-3 bg-white border border-neutral-200 rounded-lg text-neutral-700 hover:bg-primary-50 hover:border-primary-200 transition-colors" onclick="shareViaFacebook()">
                            <x-lucide-facebook class="w-5 h-5 text-primary-600" />
                            <span class="font-medium">Share on Facebook</span>
                        </button>
                        
                        <!-- Twitter/X -->
                        <button type="button" class="flex items-center justify-start gap-3 px-4 py-3 bg-white border border-neutral-200 rounded-lg text-neutral-700 hover:bg-neutral-100 hover:border-neutral-300 transition-colors" onclick="shareViaTwitter()">
                            <x-lucide-twitter class="w-5 h-5 text-neutral-800" />
                            <span class="font-medium">Share on Twitter</span>
                        </button>
                        
                        <!-- WhatsApp -->
                        <button type="button" class="flex items-center justify-start gap-3 px-4 py-3 bg-white border border-neutral-200 rounded-lg text-neutral-700 hover:bg-success-50 hover:border-success-200 transition-colors" onclick="shareViaWhatsApp()">
                            <x-lucide-message-circle class="w-5 h-5 text-success-600" />
                            <span class="font-medium">Share on WhatsApp</span>
                        </button>
                        
                        <!-- Messenger -->
                        <button type="button" class="flex items-center justify-start gap-3 px-4 py-3 bg-white border border-neutral-200 rounded-lg text-neutral-700 hover:bg-primary-50 hover:border-primary-200 transition-colors" onclick="shareViaMessenger()">
                            <x-lucide-message-square class="w-5 h-5 text-primary-600" />
                            <span class="font-medium">Share on Messenger</span>
                        </button>
                        
                        <!-- SMS -->
                        <button type="button" class="flex items-center justify-start gap-3 px-4 py-3 bg-white border border-neutral-200 rounded-lg text-neutral-700 hover:bg-warning-50 hover:border-warning-200 transition-colors" onclick="shareViaSMS()">
                            <x-lucide-smartphone class="w-5 h-5 text-warning-600" />
                            <span class="font-medium">Share via SMS</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Email Invitation Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <h2 class="text-lg font-semibold text-neutral-800 mb-6">Send Direct Invitation</h2>
                <form id="invitationForm">
                    @csrf
                    <div class="mb-4">
                        <label for="inviteEmail" class="block text-sm font-medium text-neutral-600 mb-2">Friend's Email Address</label>
                        <input type="email" class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="inviteEmail" name="email" placeholder="friend@example.com" required>
                    </div>
                    <div class="mb-6">
                        <label for="inviteMessage" class="block text-sm font-medium text-neutral-600 mb-2">Personal Message (Optional)</label>
                        <textarea class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="inviteMessage" name="message" rows="3" placeholder="Add a personal touch to your invitation..."></textarea>
                        <p class="text-sm text-neutral-400 mt-1">Max 500 characters</p>
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-success-600 text-white font-medium rounded-lg hover:bg-success-700 transition-colors">
                        <x-lucide-send class="w-5 h-5" />
                        Send Invitation
                    </button>
                </form>
            </div>
        </div>

        <!-- Rewards Info Card -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-success-50 rounded-2xl shadow-sm border border-success-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-success-600 rounded-xl flex items-center justify-center text-white">
                        <x-lucide-gift class="w-5 h-5" />
                    </div>
                    <h2 class="text-lg font-semibold text-success-900">Referral Rewards</h2>
                </div>
                
                <div class="mb-6">
                    <h3 class="font-medium text-primary-700 mb-3">For Your Friend (Referred):</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2 text-success-800">
                            <x-lucide-check-circle class="w-5 h-5 text-success-600 mt-0.5 flex-shrink-0" />
                            <span><strong>500 points</strong> instantly upon signup</span>
                        </li>
                        <li class="flex items-start gap-2 text-success-800">
                            <x-lucide-check-circle class="w-5 h-5 text-success-600 mt-0.5 flex-shrink-0" />
                            <span><strong>15% discount coupon</strong> for first project</span>
                        </li>
                        <li class="flex items-start gap-2 text-success-800">
                            <x-lucide-check-circle class="w-5 h-5 text-success-600 mt-0.5 flex-shrink-0" />
                            <span><strong>5-10% coupon</strong> after completing payment (tier-based)</span>
                        </li>
                    </ul>
                </div>

                <div class="mb-6">
                    <h3 class="font-medium text-primary-700 mb-3">For You (Referrer):</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2 text-success-800">
                            <x-lucide-star class="w-5 h-5 text-warning-500 mt-0.5 flex-shrink-0" />
                            <span><strong>1,000 points</strong> after their first payment</span>
                        </li>
                        <li class="flex items-start gap-2 text-success-800">
                            <x-lucide-star class="w-5 h-5 text-warning-500 mt-0.5 flex-shrink-0" />
                            <span><strong>20% discount coupon</strong> for your next project</span>
                        </li>
                        <li class="flex items-start gap-2 text-success-800">
                            <x-lucide-wallet class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" />
                            <span><strong>1-3% withdrawable credits</strong> based on payment tier</span>
                        </li>
                        <li class="flex items-start gap-2 text-success-800 bg-primary-50 rounded-lg p-2 -mx-2">
                            <x-lucide-info class="w-5 h-5 text-primary-600 mt-0.5 flex-shrink-0" />
                            <div>
                                <strong>Tiered Credits System:</strong>
                                <ul class="text-xs mt-1 ml-4 space-y-0.5">
                                    <li>₱100K-200K: <strong>3%</strong> credits</li>
                                    <li>₱200K-500K: <strong>2%</strong> credits</li>
                                    <li>₱500K-1M: <strong>1.5%</strong> credits</li>
                                    <li>₱1M+: <strong>1%</strong> credits</li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="bg-white bg-opacity-70 rounded-xl p-4 border border-success-200">
                    <div class="flex items-start gap-2">
                        <x-lucide-lightbulb class="w-5 h-5 text-warning-600 flex-shrink-0 mt-0.5" />
                        <p class="text-sm text-success-800">
                            <strong>Pro Tip:</strong> Higher project payments mean bigger credits! Refer clients with larger budgets to maximize your withdrawable earnings. No limits on referrals!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Share Tips Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-warning-50 rounded-xl flex items-center justify-center">
                        <x-lucide-lightbulb class="w-5 h-5 text-warning-600" />
                    </div>
                    <h2 class="text-lg font-semibold text-neutral-800">Sharing Tips</h2>
                </div>
                <ul class="space-y-2 text-sm text-neutral-600">
                    <li class="flex items-start gap-2">
                        <x-lucide-chevron-right class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" />
                        <span>Share with friends who need our services</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-lucide-chevron-right class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" />
                        <span>Post on your social media profiles</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-lucide-chevron-right class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" />
                        <span>Add to your email signature</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-lucide-chevron-right class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" />
                        <span>Share in relevant online communities</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-lucide-chevron-right class="w-4 h-4 text-primary-500 mt-0.5 flex-shrink-0" />
                        <span>Tell them about your positive experience!</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const referralCode = "{{ $referralCode->code }}";
const referralUrl = "{{ $referralUrl }}";
const emailText = `{{ $shareTexts['email'] }}`;
const smsText = `{{ $shareTexts['sms'] }}`;
const socialText = `{{ $shareTexts['social'] }}`;

function copyCode() {
    navigator.clipboard.writeText(referralCode).then(() => {
        window.toast.success('Referral code copied to clipboard!');
    });
}

function copyUrl() {
    navigator.clipboard.writeText(referralUrl).then(() => {
        window.toast.success('Referral link copied to clipboard!');
    });
}

function shareViaEmail() {
    const subject = encodeURIComponent('Join me and get rewarded!');
    const body = encodeURIComponent(emailText);
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
}

function shareViaFacebook() {
    const url = encodeURIComponent(referralUrl);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
}

function shareViaTwitter() {
    const text = encodeURIComponent(socialText);
    const url = encodeURIComponent(referralUrl);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank', 'width=600,height=400');
}

function shareViaWhatsApp() {
    const text = encodeURIComponent(`${socialText}\n${referralUrl}`);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareViaMessenger() {
    const url = encodeURIComponent(referralUrl);
    window.open(`fb-messenger://share/?link=${url}`, '_blank');
}

function shareViaSMS() {
    const text = encodeURIComponent(smsText);
    window.location.href = `sms:?body=${text}`;
}

// Handle invitation form submission
document.getElementById('invitationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
    
    try {
        const response = await fetch('{{ route("adiutor.referrals.invite") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.toast.success('Invitation sent successfully!');
            this.reset();
        } else {
            window.toast.error(data.message || 'Failed to send invitation');
        }
    } catch (error) {
        window.toast.error('An error occurred. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>
@endsection
