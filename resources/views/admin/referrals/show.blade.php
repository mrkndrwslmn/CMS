@extends('admin.layouts.app')

@section('title', 'Referral Details - Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Referrals', 'url' => route('admin.referrals.index'), 'icon' => 'users'],
        ['label' => 'All Referrals', 'url' => route('admin.referrals.list'), 'icon' => 'list'],
        ['label' => 'Details #' . $referral->id, 'icon' => 'file-text']
    ]" class="mb-4" />

    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <x-ui.page-header
            title="Referral Details #{{ $referral->id }}"
            subtitle="Complete information about this referral"
        />
        <div class="flex gap-3">
            @if($referral->status === 'pending')
            <form method="POST" action="{{ route('admin.referrals.process', $referral->id) }}" class="inline">
                @csrf
                <x-ui.button 
                    type="submit" 
                    variant="primary"
                    onclick="return window.Alerts.confirmForm(event, 'Process Referral', 'Process this referral manually? This will award rewards to the referrer immediately.')"
                    class="inline-flex items-center gap-2"
                >
                    <x-lucide-check-circle class="w-4 h-4" />
                    Process Manually
                </x-ui.button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <x-ui.card>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-info class="w-5 h-5 text-primary-600" />
                        <h3 class="text-lg font-semibold text-neutral-800">Status Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-primary-50 rounded-xl">
                            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                @if($referral->status === 'pending')
                                <x-lucide-clock class="w-6 h-6 text-warning-600" />
                                @elseif($referral->status === 'completed')
                                <x-lucide-check-circle class="w-6 h-6 text-info-600" />
                                @elseif($referral->status === 'rewarded')
                                <x-lucide-gift class="w-6 h-6 text-success-600" />
                                @endif
                            </div>
                            <p class="text-sm font-medium text-neutral-600">Current Status</p>
                            <p class="text-lg font-semibold text-primary-700 uppercase">{{ $referral->status }}</p>
                        </div>

                        <div class="text-center p-4 bg-success-50 rounded-xl">
                            <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <x-lucide-coins class="w-6 h-6 text-success-600" />
                            </div>
                            <p class="text-sm font-medium text-neutral-600">Points Awarded</p>
                            <p class="text-lg font-semibold text-success-700">{{ number_format($referral->earned_points) }}</p>
                        </div>

                        <div class="text-center p-4 bg-purple-50 rounded-xl">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                                <x-lucide-ticket class="w-6 h-6 text-purple-600" />
                            </div>
                            <p class="text-sm font-medium text-neutral-600">Coupon Generated</p>
                            <p class="text-lg font-semibold text-purple-700">
                                @if($referral->referrerCoupon)
                                {{ $referral->referrerCoupon->discount_value }}% OFF
                                @else
                                —
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Referrer & Referred Users -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Referrer -->
                <x-ui.card>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <x-lucide-user-check class="w-5 h-5 text-primary-600" />
                            <h3 class="text-lg font-semibold text-neutral-800">Referrer</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">Full Name</label>
                                <p class="text-neutral-800 font-medium">{{ $referral->referrer->fullName }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">Email</label>
                                <p class="text-neutral-800">{{ $referral->referrer->email }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">User ID</label>
                                <p class="text-neutral-800">#{{ $referral->referrer->id }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">Total Referrals</label>
                                <p class="text-neutral-800 font-medium">{{ $referral->referrer->total_referrals }}</p>
                            </div>
                            <div class="pt-3 border-t border-neutral-200">
                                <a href="{{ route('admin.users.show', $referral->referrer->id) }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    View Full Profile 
                                    <x-lucide-arrow-right class="w-4 h-4" />
                                </a>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Referred User -->
                <x-ui.card>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <x-lucide-user-plus class="w-5 h-5 text-success-600" />
                            <h3 class="text-lg font-semibold text-neutral-800">Referred User</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">Full Name</label>
                                <p class="text-neutral-800 font-medium">{{ $referral->referred->fullName }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">Email</label>
                                <p class="text-neutral-800">{{ $referral->referred->email }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">User ID</label>
                                <p class="text-neutral-800">#{{ $referral->referred->id }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-neutral-500 uppercase">Registered At</label>
                                <p class="text-neutral-800">{{ $referral->referred->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="pt-3 border-t border-neutral-200">
                                <a href="{{ route('admin.users.show', $referral->referred->id) }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    View Full Profile 
                                    <x-lucide-arrow-right class="w-4 h-4" />
                                </a>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Rewards Details -->
            <x-ui.card>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-gift class="w-5 h-5 text-primary-600" />
                        <h3 class="text-lg font-semibold text-neutral-800">Rewards Breakdown</h3>
                    </div>
                    <div class="space-y-4">
                        <!-- Referred User (Welcome) -->
                        <div class="p-4 bg-success-50 border border-success-200 rounded-xl">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium text-success-800 mb-2">Referred User (Welcome Bonus)</h4>
                                    <div class="space-y-1 text-sm text-success-700">
                                        <p class="flex items-center gap-2">
                                            <x-lucide-coins class="w-4 h-4" />
                                            <strong>{{ config('referral.rewards.referred.welcome_points') }} points</strong> credited on signup
                                        </p>
                                        @if($referral->referredCoupon)
                                        <p class="flex items-center gap-2">
                                            <x-lucide-ticket class="w-4 h-4" />
                                            <strong>{{ $referral->referredCoupon->discount_value }}% off coupon</strong> (Code: {{ $referral->referredCoupon->code }})
                                        </p>
                                        <p class="flex items-center gap-2 text-xs">
                                            <x-lucide-clock class="w-4 h-4" />
                                            Valid until {{ $referral->referredCoupon->end_date->format('M d, Y') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                    <x-lucide-check class="w-3 h-3 mr-1" />
                                    Awarded
                                </span>
                            </div>
                        </div>

                        <!-- Referrer (Completion) -->
                        <div class="p-4 {{ $referral->status === 'rewarded' ? 'bg-purple-50 border-purple-200' : 'bg-neutral-50 border-neutral-200' }} border rounded-xl">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium {{ $referral->status === 'rewarded' ? 'text-purple-800' : 'text-neutral-800' }} mb-2">Referrer (Completion Bonus)</h4>
                                    <div class="space-y-1 text-sm {{ $referral->status === 'rewarded' ? 'text-purple-700' : 'text-neutral-700' }}">
                                        <p class="flex items-center gap-2">
                                            <x-lucide-coins class="w-4 h-4" />
                                            <strong>{{ config('referral.rewards.referrer.completion_points') }} points</strong> {{ $referral->status === 'rewarded' ? 'credited' : 'pending' }}
                                        </p>
                                        @if($referral->referrerCoupon)
                                        <p class="flex items-center gap-2">
                                            <x-lucide-ticket class="w-4 h-4" />
                                            <strong>{{ $referral->referrerCoupon->discount_value }}% off coupon</strong> (Code: {{ $referral->referrerCoupon->code }})
                                        </p>
                                        <p class="flex items-center gap-2 text-xs">
                                            <x-lucide-clock class="w-4 h-4" />
                                            Valid until {{ $referral->referrerCoupon->end_date->format('M d, Y') }}
                                        </p>
                                        @else
                                        <p class="flex items-center gap-2">
                                            <x-lucide-ticket class="w-4 h-4" />
                                            <strong>{{ config('referral.rewards.referrer.coupon_discount') }}% off coupon</strong> (Pending)
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                @if($referral->status === 'rewarded')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    <x-lucide-gift class="w-3 h-3 mr-1" />
                                    Awarded
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                    <x-lucide-clock class="w-3 h-3 mr-1" />
                                    Pending
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Timeline -->
            <x-ui.card>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-history class="w-5 h-5 text-primary-600" />
                        <h3 class="text-lg font-semibold text-neutral-800">Timeline</h3>
                    </div>
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-neutral-200"></div>

                        <!-- Referral Created -->
                        <div class="relative mb-6">
                            <div class="absolute left-[-2.1rem] w-10 h-10 bg-success-500 rounded-full flex items-center justify-center">
                                <x-lucide-user-plus class="w-5 h-5 text-white" />
                            </div>
                            <div class="bg-success-50 p-4 rounded-xl">
                                <h4 class="font-medium text-success-800">Referral Created</h4>
                                <p class="text-sm text-success-700 mt-1">{{ $referral->referred->fullName }} signed up using code <code class="px-2 py-0.5 bg-success-100 rounded">{{ $referral->referral_code }}</code></p>
                                <p class="text-xs text-success-600 mt-2 flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1">
                                        <x-lucide-calendar class="w-3 h-3" />
                                        {{ $referral->created_at->format('M d, Y h:i A') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <x-lucide-clock class="w-3 h-3" />
                                        {{ $referral->created_at->diffForHumans() }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Completed -->
                        @if($referral->completed_at)
                        <div class="relative mb-6">
                            <div class="absolute left-[-2.1rem] w-10 h-10 bg-info-500 rounded-full flex items-center justify-center">
                                <x-lucide-credit-card class="w-5 h-5 text-white" />
                            </div>
                            <div class="bg-info-50 p-4 rounded-xl">
                                <h4 class="font-medium text-info-800">First Payment Made</h4>
                                <p class="text-sm text-info-700 mt-1">{{ $referral->referred->fullName }} completed their first payment</p>
                                @if($referral->firstPayment)
                                <p class="text-xs text-info-600 mt-1">Payment ID: #{{ $referral->firstPayment->id }} - Amount: P{{ number_format($referral->firstPayment->amount, 2) }}</p>
                                @endif
                                <p class="text-xs text-info-600 mt-2 flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1">
                                        <x-lucide-calendar class="w-3 h-3" />
                                        {{ $referral->completed_at->format('M d, Y h:i A') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <x-lucide-clock class="w-3 h-3" />
                                        {{ $referral->completed_at->diffForHumans() }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="relative mb-6">
                            <div class="absolute left-[-2.1rem] w-10 h-10 bg-neutral-300 rounded-full flex items-center justify-center">
                                <x-lucide-credit-card class="w-5 h-5 text-white" />
                            </div>
                            <div class="bg-neutral-50 p-4 rounded-xl border-2 border-dashed border-neutral-300">
                                <h4 class="font-medium text-neutral-600">Awaiting First Payment</h4>
                                <p class="text-sm text-neutral-500 mt-1">Referral will complete when {{ $referral->referred->fullName }} makes their first payment</p>
                            </div>
                        </div>
                        @endif

                        <!-- Rewarded -->
                        @if($referral->rewarded_at)
                        <div class="relative">
                            <div class="absolute left-[-2.1rem] w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                <x-lucide-gift class="w-5 h-5 text-white" />
                            </div>
                            <div class="bg-purple-50 p-4 rounded-xl">
                                <h4 class="font-medium text-purple-800">Rewards Distributed</h4>
                                <p class="text-sm text-purple-700 mt-1">{{ $referral->referrer->fullName }} received {{ number_format($referral->earned_points) }} points and a {{ $referral->referrerCoupon ? $referral->referrerCoupon->discount_value : config('referral.rewards.referrer.coupon_discount') }}% off coupon</p>
                                <p class="text-xs text-purple-600 mt-2 flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1">
                                        <x-lucide-calendar class="w-3 h-3" />
                                        {{ $referral->rewarded_at->format('M d, Y h:i A') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <x-lucide-clock class="w-3 h-3" />
                                        {{ $referral->rewarded_at->diffForHumans() }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="relative">
                            <div class="absolute left-[-2.1rem] w-10 h-10 bg-neutral-300 rounded-full flex items-center justify-center">
                                <x-lucide-gift class="w-5 h-5 text-white" />
                            </div>
                            <div class="bg-neutral-50 p-4 rounded-xl border-2 border-dashed border-neutral-300">
                                <h4 class="font-medium text-neutral-600">Rewards Pending</h4>
                                <p class="text-sm text-neutral-500 mt-1">Rewards will be distributed after first payment is confirmed</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Referral Code Info -->
            <x-ui.card>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-qr-code class="w-5 h-5 text-primary-600" />
                        <h3 class="text-lg font-semibold text-neutral-800">Referral Code</h3>
                    </div>
                    <div class="text-center p-4 bg-primary-50 rounded-xl mb-4">
                        <code class="text-2xl font-bold font-mono text-primary-800">{{ $referral->referral_code }}</code>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Owner:</span>
                            <span class="font-medium text-neutral-800">{{ $referral->referrer->fullName }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Status:</span>
                            <span class="font-medium {{ $referral->referralCode && $referral->referralCode->is_active ? 'text-success-600' : 'text-error-600' }}">
                                {{ $referral->referralCode && $referral->referralCode->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Total Uses:</span>
                            <span class="font-medium text-neutral-800">{{ $referral->referralCode ? $referral->referralCode->total_referrals : 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-600">Successful:</span>
                            <span class="font-medium text-success-600">{{ $referral->referralCode ? $referral->referralCode->successful_referrals : 0 }}</span>
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Metadata -->
            @if($referral->metadata)
            <x-ui.card>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-map-pin class="w-5 h-5 text-primary-600" />
                        <h3 class="text-lg font-semibold text-neutral-800">Tracking Information</h3>
                    </div>
                    <div class="space-y-3 text-sm">
                        @if(isset($referral->metadata['ip_address']))
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase">IP Address</label>
                            <p class="text-neutral-800 font-mono">{{ $referral->metadata['ip_address'] }}</p>
                        </div>
                        @endif
                        @if(isset($referral->metadata['user_agent']))
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase">User Agent</label>
                            <p class="text-neutral-800 text-xs break-all">{{ $referral->metadata['user_agent'] }}</p>
                        </div>
                        @endif
                        @if(isset($referral->metadata['source']))
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase">Source</label>
                            <p class="text-neutral-800">{{ ucwords(str_replace('_', ' ', $referral->metadata['source'])) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </x-ui.card>
            @endif

            <!-- Quick Actions -->
            <x-ui.card>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <x-lucide-zap class="w-5 h-5 text-primary-600" />
                        <h3 class="text-lg font-semibold text-neutral-800">Quick Actions</h3>
                    </div>
                    <div class="space-y-3">
                        @if($referral->firstPayment)
                        <x-ui.button 
                            href="{{ route('admin.payments.show', $referral->firstPayment->id) }}" 
                            variant="secondary"
                            class="w-full inline-flex items-center justify-center gap-2"
                        >
                            <x-lucide-receipt class="w-4 h-4" />
                            View Payment
                        </x-ui.button>
                        @endif
                        <x-ui.button 
                            href="{{ route('admin.users.show', $referral->referrer->id) }}" 
                            variant="secondary"
                            class="w-full inline-flex items-center justify-center gap-2"
                        >
                            <x-lucide-user class="w-4 h-4" />
                            View Referrer Profile
                        </x-ui.button>
                        <x-ui.button 
                            href="{{ route('admin.users.show', $referral->referred->id) }}" 
                            variant="secondary"
                            class="w-full inline-flex items-center justify-center gap-2"
                        >
                            <x-lucide-user-plus class="w-4 h-4" />
                            View Referred User
                        </x-ui.button>
                        @if($referral->referrerCoupon)
                        <x-ui.button 
                            href="{{ route('admin.coupons.show', $referral->referrerCoupon->id) }}" 
                            variant="secondary"
                            class="w-full inline-flex items-center justify-center gap-2"
                        >
                            <x-lucide-ticket class="w-4 h-4" />
                            View Coupon
                        </x-ui.button>
                        @endif
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection
