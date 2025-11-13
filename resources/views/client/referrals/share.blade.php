@extends('layouts.client')

@section('title', 'Share Referral Code')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">🚀 Share & Earn</h1>
                    <p class="text-muted">Invite friends and both of you get rewarded!</p>
                </div>
                <a href="{{ route('client.referrals.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Referral Link Card -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Unique Referral Link</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Referral Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" id="referralCode" value="{{ $referralCode->code }}" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" onclick="copyCode()">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Referral URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="referralUrl" value="{{ $referralUrl }}" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button" onclick="copyUrl()">
                                    <i class="fas fa-link"></i> Copy Link
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Social Sharing Buttons -->
                    <div class="mb-3">
                        <label class="form-label font-weight-bold mb-3">Share on Social Media</label>
                        <div class="btn-group-vertical btn-group-lg btn-block" role="group">
                            <!-- Email -->
                            <button type="button" class="btn btn-outline-secondary text-left mb-2" onclick="shareViaEmail()">
                                <i class="fas fa-envelope fa-fw text-danger"></i> Share via Email
                            </button>
                            
                            <!-- Facebook -->
                            <button type="button" class="btn btn-outline-secondary text-left mb-2" onclick="shareViaFacebook()">
                                <i class="fab fa-facebook fa-fw text-primary"></i> Share on Facebook
                            </button>
                            
                            <!-- Twitter/X -->
                            <button type="button" class="btn btn-outline-secondary text-left mb-2" onclick="shareViaTwitter()">
                                <i class="fab fa-twitter fa-fw text-info"></i> Share on Twitter
                            </button>
                            
                            <!-- WhatsApp -->
                            <button type="button" class="btn btn-outline-secondary text-left mb-2" onclick="shareViaWhatsApp()">
                                <i class="fab fa-whatsapp fa-fw text-success"></i> Share on WhatsApp
                            </button>
                            
                            <!-- Messenger -->
                            <button type="button" class="btn btn-outline-secondary text-left mb-2" onclick="shareViaMessenger()">
                                <i class="fab fa-facebook-messenger fa-fw text-primary"></i> Share on Messenger
                            </button>
                            
                            <!-- SMS -->
                            <button type="button" class="btn btn-outline-secondary text-left" onclick="shareViaSMS()">
                                <i class="fas fa-sms fa-fw text-warning"></i> Share via SMS
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Invitation Card -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Send Direct Invitation</h6>
                </div>
                <div class="card-body">
                    <form id="invitationForm">
                        @csrf
                        <div class="form-group">
                            <label for="inviteEmail">Friend's Email Address</label>
                            <input type="email" class="form-control" id="inviteEmail" name="email" placeholder="friend@example.com" required>
                        </div>
                        <div class="form-group">
                            <label for="inviteMessage">Personal Message (Optional)</label>
                            <textarea class="form-control" id="inviteMessage" name="message" rows="3" placeholder="Add a personal touch to your invitation..."></textarea>
                            <small class="form-text text-muted">Max 500 characters</small>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-paper-plane"></i> Send Invitation
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Rewards Info Card -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow border-left-success">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">🎁 Referral Rewards</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-primary">For Your Friend:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>500 points</strong> instantly
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success"></i>
                                <strong>15% discount coupon</strong>
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success"></i>
                                Valid for <strong>30 days</strong>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold text-info">For You:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <strong>1,000 points</strong> after their first payment
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-star text-warning"></i>
                                <strong>20% discount coupon</strong>
                            </li>
                            <li>
                                <i class="fas fa-star text-warning"></i>
                                Valid for <strong>60 days</strong>
                            </li>
                        </ul>
                    </div>

                    <div class="alert alert-info mb-0">
                        <small>
                            <i class="fas fa-info-circle"></i>
                            <strong>Tip:</strong> The more friends you refer, the more rewards you earn. There's no limit!
                        </small>
                    </div>
                </div>
            </div>

            <!-- Share Tips Card -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">💡 Sharing Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Share with friends who need our services</li>
                        <li class="mb-2">Post on your social media profiles</li>
                        <li class="mb-2">Add to your email signature</li>
                        <li class="mb-2">Share in relevant online communities</li>
                        <li>Tell them about your positive experience!</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const referralCode = "{{ $referralCode->code }}";
const referralUrl = "{{ $referralUrl }}";
const emailText = `{{ $shareTexts['email'] }}`;
const smsText = `{{ $shareTexts['sms'] }}`;
const socialText = `{{ $shareTexts['social'] }}`;

function copyCode() {
    navigator.clipboard.writeText(referralCode).then(() => {
        showToast('Referral code copied to clipboard!', 'success');
    });
}

function copyUrl() {
    navigator.clipboard.writeText(referralUrl).then(() => {
        showToast('Referral link copied to clipboard!', 'success');
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

function showToast(message, type = 'success') {
    const bgColor = type === 'success' ? 'bg-success' : 'bg-danger';
    const toast = document.createElement('div');
    toast.className = `alert ${bgColor} text-white position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Handle invitation form submission
document.getElementById('invitationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    
    try {
        const response = await fetch('{{ route("client.referrals.invite") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('Invitation sent successfully!', 'success');
            this.reset();
        } else {
            showToast(data.message || 'Failed to send invitation', 'error');
        }
    } catch (error) {
        showToast('An error occurred. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});
</script>
@endpush
@endsection
