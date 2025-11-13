@extends('layouts.admin')

@section('title', 'Referral Details - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.referrals.list') }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold mb-2 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Back to All Referrals
            </a>
            <h1 class="heading-serif text-3xl text-primary-700 mb-2">Referral Details #{{ $referral->id }}</h1>
            <p class="text-neutral-600">Complete information about this referral</p>
        </div>
        <div class="flex gap-3">
            @if($referral->status === 'pending')
            <form method="POST" action="{{ route('admin.referrals.process', $referral->id) }}" class="inline">
                @csrf
                <button type="submit" 
                        class="btn-primary"
                        onclick="return confirm('Process this referral manually? This will award rewards to the referrer immediately.')">
                    <i class="fas fa-check-circle mr-2"></i>Process Manually
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4">Status Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg">
                        <div class="text-3xl mb-2">
                            @if($referral->status === 'pending')
                            <i class="fas fa-clock text-yellow-600"></i>
                            @elseif($referral->status === 'completed')
                            <i class="fas fa-check-circle text-blue-600"></i>
                            @elseif($referral->status === 'rewarded')
                            <i class="fas fa-gift text-green-600"></i>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-neutral-700">Current Status</p>
                        <p class="text-lg font-bold text-primary-700 uppercase">{{ $referral->status }}</p>
                    </div>

                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg">
                        <div class="text-3xl mb-2">
                            <i class="fas fa-coins text-green-600"></i>
                        </div>
                        <p class="text-sm font-semibold text-neutral-700">Points Awarded</p>
                        <p class="text-lg font-bold text-green-700">{{ number_format($referral->earned_points) }}</p>
                    </div>

                    <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg">
                        <div class="text-3xl mb-2">
                            <i class="fas fa-ticket-alt text-purple-600"></i>
                        </div>
                        <p class="text-sm font-semibold text-neutral-700">Coupon Generated</p>
                        <p class="text-lg font-bold text-purple-700">
                            @if($referral->referrerCoupon)
                            {{ $referral->referrerCoupon->discount_value }}% OFF
                            @else
                            —
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Referrer & Referred Users -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Referrer -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center">
                        <i class="fas fa-user-tag text-primary-600 mr-2"></i>
                        Referrer
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">Full Name</label>
                            <p class="text-neutral-800 font-semibold">{{ $referral->referrer->fullName }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">Email</label>
                            <p class="text-neutral-800">{{ $referral->referrer->email }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">User ID</label>
                            <p class="text-neutral-800">#{{ $referral->referrer->id }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">Total Referrals</label>
                            <p class="text-neutral-800 font-semibold">{{ $referral->referrer->total_referrals }}</p>
                        </div>
                        <div class="pt-3 border-t border-neutral-200">
                            <a href="{{ route('admin.users.show', $referral->referrer->id) }}" class="text-sm text-primary-600 hover:text-primary-700 font-semibold">
                                View Full Profile <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Referred User -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-bold text-primary-700 mb-4 flex items-center">
                        <i class="fas fa-user-plus text-green-600 mr-2"></i>
                        Referred User
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">Full Name</label>
                            <p class="text-neutral-800 font-semibold">{{ $referral->referred->fullName }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">Email</label>
                            <p class="text-neutral-800">{{ $referral->referred->email }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">User ID</label>
                            <p class="text-neutral-800">#{{ $referral->referred->id }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-neutral-600 uppercase">Registered At</label>
                            <p class="text-neutral-800">{{ $referral->referred->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="pt-3 border-t border-neutral-200">
                            <a href="{{ route('admin.users.show', $referral->referred->id) }}" class="text-sm text-primary-600 hover:text-primary-700 font-semibold">
                                View Full Profile <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rewards Details -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4">Rewards Breakdown</h3>
                <div class="space-y-4">
                    <!-- Referred User (Welcome) -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-semibold text-green-800 mb-2">Referred User (Welcome Bonus)</h4>
                                <div class="space-y-1 text-sm text-green-700">
                                    <p><i class="fas fa-coins w-5"></i> <strong>{{ config('referral.rewards.referred.welcome_points') }} points</strong> credited on signup</p>
                                    @if($referral->referredCoupon)
                                    <p><i class="fas fa-ticket-alt w-5"></i> <strong>{{ $referral->referredCoupon->discount_value }}% off coupon</strong> (Code: {{ $referral->referredCoupon->code }})</p>
                                    <p class="text-xs"><i class="fas fa-clock w-5"></i> Valid until {{ $referral->referredCoupon->end_date->format('M d, Y') }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Awarded
                            </span>
                        </div>
                    </div>

                    <!-- Referrer (Completion) -->
                    <div class="p-4 {{ $referral->status === 'rewarded' ? 'bg-purple-50 border-purple-200' : 'bg-neutral-50 border-neutral-200' }} border rounded-lg">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-semibold {{ $referral->status === 'rewarded' ? 'text-purple-800' : 'text-neutral-800' }} mb-2">Referrer (Completion Bonus)</h4>
                                <div class="space-y-1 text-sm {{ $referral->status === 'rewarded' ? 'text-purple-700' : 'text-neutral-700' }}">
                                    <p><i class="fas fa-coins w-5"></i> <strong>{{ config('referral.rewards.referrer.completion_points') }} points</strong> {{ $referral->status === 'rewarded' ? 'credited' : 'pending' }}</p>
                                    @if($referral->referrerCoupon)
                                    <p><i class="fas fa-ticket-alt w-5"></i> <strong>{{ $referral->referrerCoupon->discount_value }}% off coupon</strong> (Code: {{ $referral->referrerCoupon->code }})</p>
                                    <p class="text-xs"><i class="fas fa-clock w-5"></i> Valid until {{ $referral->referrerCoupon->end_date->format('M d, Y') }}</p>
                                    @else
                                    <p><i class="fas fa-ticket-alt w-5"></i> <strong>{{ config('referral.rewards.referrer.coupon_discount') }}% off coupon</strong> (Pending)</p>
                                    @endif
                                </div>
                            </div>
                            @if($referral->status === 'rewarded')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                <i class="fas fa-gift mr-1"></i>Awarded
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4">Timeline</h3>
                <div class="relative pl-8">
                    <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-neutral-200"></div>

                    <!-- Referral Created -->
                    <div class="relative mb-6">
                        <div class="absolute left-[-2.1rem] w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Referral Created</h4>
                            <p class="text-sm text-green-700 mt-1">{{ $referral->referred->fullName }} signed up using code <code class="px-2 py-0.5 bg-green-100 rounded">{{ $referral->referral_code }}</code></p>
                            <p class="text-xs text-green-600 mt-2">
                                <i class="fas fa-calendar mr-1"></i>{{ $referral->created_at->format('M d, Y h:i A') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock mr-1"></i>{{ $referral->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <!-- Completed -->
                    @if($referral->completed_at)
                    <div class="relative mb-6">
                        <div class="absolute left-[-2.1rem] w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">First Payment Made</h4>
                            <p class="text-sm text-blue-700 mt-1">{{ $referral->referred->fullName }} completed their first payment</p>
                            @if($referral->firstPayment)
                            <p class="text-xs text-blue-600 mt-1">Payment ID: #{{ $referral->firstPayment->id }} • Amount: ₱{{ number_format($referral->firstPayment->amount, 2) }}</p>
                            @endif
                            <p class="text-xs text-blue-600 mt-2">
                                <i class="fas fa-calendar mr-1"></i>{{ $referral->completed_at->format('M d, Y h:i A') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock mr-1"></i>{{ $referral->completed_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="relative mb-6">
                        <div class="absolute left-[-2.1rem] w-10 h-10 bg-neutral-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div class="bg-neutral-50 p-4 rounded-lg border-2 border-dashed border-neutral-300">
                            <h4 class="font-semibold text-neutral-600">Awaiting First Payment</h4>
                            <p class="text-sm text-neutral-500 mt-1">Referral will complete when {{ $referral->referred->fullName }} makes their first payment</p>
                        </div>
                    </div>
                    @endif

                    <!-- Rewarded -->
                    @if($referral->rewarded_at)
                    <div class="relative">
                        <div class="absolute left-[-2.1rem] w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-gift text-white"></i>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800">Rewards Distributed</h4>
                            <p class="text-sm text-purple-700 mt-1">{{ $referral->referrer->fullName }} received {{ number_format($referral->earned_points) }} points and a {{ $referral->referrerCoupon ? $referral->referrerCoupon->discount_value : config('referral.rewards.referrer.coupon_discount') }}% off coupon</p>
                            <p class="text-xs text-purple-600 mt-2">
                                <i class="fas fa-calendar mr-1"></i>{{ $referral->rewarded_at->format('M d, Y h:i A') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock mr-1"></i>{{ $referral->rewarded_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="relative">
                        <div class="absolute left-[-2.1rem] w-10 h-10 bg-neutral-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-gift text-white"></i>
                        </div>
                        <div class="bg-neutral-50 p-4 rounded-lg border-2 border-dashed border-neutral-300">
                            <h4 class="font-semibold text-neutral-600">Rewards Pending</h4>
                            <p class="text-sm text-neutral-500 mt-1">Rewards will be distributed after first payment is confirmed</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Referral Code Info -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4">Referral Code</h3>
                <div class="text-center p-4 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg mb-4">
                    <code class="text-2xl font-bold font-mono text-primary-800">{{ $referral->referral_code }}</code>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Owner:</span>
                        <span class="font-semibold text-neutral-800">{{ $referral->referrer->fullName }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Status:</span>
                        <span class="font-semibold {{ $referral->referralCode && $referral->referralCode->is_active ? 'text-green-600' : 'text-red-600' }}">
                            {{ $referral->referralCode && $referral->referralCode->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Total Uses:</span>
                        <span class="font-semibold text-neutral-800">{{ $referral->referralCode ? $referral->referralCode->total_referrals : 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Successful:</span>
                        <span class="font-semibold text-green-600">{{ $referral->referralCode ? $referral->referralCode->successful_referrals : 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            @if($referral->metadata)
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4">Tracking Information</h3>
                <div class="space-y-3 text-sm">
                    @if(isset($referral->metadata['ip_address']))
                    <div>
                        <label class="text-xs font-semibold text-neutral-600 uppercase">IP Address</label>
                        <p class="text-neutral-800 font-mono">{{ $referral->metadata['ip_address'] }}</p>
                    </div>
                    @endif
                    @if(isset($referral->metadata['user_agent']))
                    <div>
                        <label class="text-xs font-semibold text-neutral-600 uppercase">User Agent</label>
                        <p class="text-neutral-800 text-xs break-all">{{ $referral->metadata['user_agent'] }}</p>
                    </div>
                    @endif
                    @if(isset($referral->metadata['source']))
                    <div>
                        <label class="text-xs font-semibold text-neutral-600 uppercase">Source</label>
                        <p class="text-neutral-800">{{ ucwords(str_replace('_', ' ', $referral->metadata['source'])) }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-primary-700 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($referral->firstPayment)
                    <a href="{{ route('admin.payments.show', $referral->firstPayment->id) }}" class="btn-secondary w-full">
                        <i class="fas fa-receipt mr-2"></i>View Payment
                    </a>
                    @endif
                    <a href="{{ route('admin.users.show', $referral->referrer->id) }}" class="btn-secondary w-full">
                        <i class="fas fa-user mr-2"></i>View Referrer Profile
                    </a>
                    <a href="{{ route('admin.users.show', $referral->referred->id) }}" class="btn-secondary w-full">
                        <i class="fas fa-user-plus mr-2"></i>View Referred User
                    </a>
                    @if($referral->referrerCoupon)
                    <a href="{{ route('admin.coupons.show', $referral->referrerCoupon->id) }}" class="btn-secondary w-full">
                        <i class="fas fa-ticket-alt mr-2"></i>View Coupon
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
