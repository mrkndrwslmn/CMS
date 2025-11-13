@extends('layouts.client')

@section('title', 'Referral Program')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">🎁 Referral Program</h1>
                    <p class="text-muted">Invite friends and earn rewards together!</p>
                </div>
                <a href="{{ route('client.referrals.share') }}" class="btn btn-primary">
                    <i class="fas fa-share-alt"></i> Share Your Code
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Total Referrals -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Referrals
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_referrals'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Successful Referrals -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Successful
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['successful_referrals'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Referrals -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_referrals'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lifetime Earnings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Lifetime Earnings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['lifetime_earnings']) }} pts</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Code Card -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Your Referral Code</h6>
                    @if($stats['conversion_rate'] > 0)
                        <span class="badge badge-success">{{ number_format($stats['conversion_rate'], 1) }}% conversion rate</span>
                    @endif
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <h2 class="display-4 font-weight-bold text-primary" id="referralCode">{{ $referralCode->code }}</h2>
                        <p class="text-muted">Share this code with friends to earn rewards</p>
                    </div>
                    
                    <div class="btn-group mb-3" role="group">
                        <button type="button" class="btn btn-outline-primary" onclick="copyReferralCode()">
                            <i class="fas fa-copy"></i> Copy Code
                        </button>
                        <a href="{{ route('client.referrals.share') }}" class="btn btn-primary">
                            <i class="fas fa-share-alt"></i> Share Link
                        </a>
                    </div>

                    <div class="alert alert-info mb-0">
                        <strong>How it works:</strong>
                        <ul class="text-left mb-0 mt-2">
                            <li>Friend signs up using your code → They get <strong>500 points + 15% coupon</strong></li>
                            <li>They complete first payment → You get <strong>1,000 points + 20% coupon</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Potential Earnings Card -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Pending Rewards</h6>
                </div>
                <div class="card-body">
                    @if($pendingReferrals->count() > 0)
                        <div class="text-center mb-3">
                            <h3 class="text-warning">{{ number_format($potentialEarnings) }} points</h3>
                            <p class="text-muted">Potential earnings from {{ $pendingReferrals->count() }} pending referral(s)</p>
                        </div>

                        <div class="list-group">
                            @foreach($pendingReferrals as $referral)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $referral->referred->fullName }}</h6>
                                            <small class="text-muted">Signed up {{ $referral->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge badge-warning">Pending Payment</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No pending referrals</p>
                            <a href="{{ route('client.referrals.share') }}" class="btn btn-sm btn-primary">Share Your Code</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Referrals -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Referrals</h6>
                    <a href="{{ route('client.referrals.history') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($completedReferrals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Friend</th>
                                        <th>Status</th>
                                        <th>Points Earned</th>
                                        <th>Coupon</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedReferrals as $referral)
                                        <tr>
                                            <td>
                                                <strong>{{ $referral->referred->fullName }}</strong><br>
                                                <small class="text-muted">{{ $referral->referred->email }}</small>
                                            </td>
                                            <td>
                                                @if($referral->status === 'rewarded')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Completed
                                                    </span>
                                                @elseif($referral->status === 'completed')
                                                    <span class="badge badge-info">
                                                        <i class="fas fa-sync"></i> Processing
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="text-success">+{{ number_format($referral->referrer_points_earned) }}</strong> points
                                            </td>
                                            <td>
                                                @if($referral->referrerCoupon)
                                                    <code>{{ $referral->referrerCoupon->code }}</code>
                                                    <small class="text-muted d-block">{{ $referral->referrerCoupon->discount_value }}% off</small>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $referral->completed_at ? $referral->completed_at->format('M d, Y') : 'N/A' }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-user-friends fa-3x mb-3"></i>
                            <p class="mb-2">No successful referrals yet</p>
                            <p class="small">Share your code to start earning rewards!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.innerHTML = '<i class="fas fa-check-circle"></i> Referral code copied!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}
</script>
@endpush
@endsection
