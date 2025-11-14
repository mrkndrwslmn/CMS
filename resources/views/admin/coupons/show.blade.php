@extends('admin.layouts.app')

@section('title', 'Coupon Details - ' . $coupon->code)

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-800 mb-2">{{ $coupon->code }}</h1>
            <p class="text-neutral-600">{{ $coupon->description ?: 'Discount Coupon' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.coupons.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Coupon Card -->
            <div class="glass-card p-8 bg-gradient-to-br from-primary-50 via-white to-success-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500 opacity-5 rounded-full -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold
                                {{ $coupon->status === 'active' ? 'bg-success-100 text-success-700' : 'bg-neutral-200 text-neutral-600' }}">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                {{ $coupon->status === 'active' ? 'Active' : 'Inactive' }}
                            </div>
                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold ml-2
                                {{ $coupon->coupon_type === 'public' ? 'bg-info-100 text-info-700' : 'bg-warning-100 text-warning-700' }}">
                                <i class="fas fa-{{ $coupon->coupon_type === 'public' ? 'globe' : 'user' }} mr-2"></i>
                                {{ ucfirst(str_replace('_', ' ', $coupon->coupon_type)) }}
                            </div>
                        </div>

                        <!-- QR Code Button -->
                        <button onclick="showQRCode()" class="btn-secondary">
                            <i class="fas fa-qrcode mr-2"></i>QR Code
                        </button>
                    </div>

                    <div class="text-center py-8">
                        <div class="inline-block px-8 py-4 bg-white rounded-2xl shadow-lg border-2 border-dashed border-primary-300 mb-4">
                            <p class="text-5xl font-bold text-primary-600 tracking-wider font-mono">{{ $coupon->code }}</p>
                        </div>

                        <div class="flex items-center justify-center gap-8 mt-6">
                            <div class="text-center">
                                <p class="text-sm text-neutral-600 mb-1">Discount</p>
                                <p class="text-3xl font-bold text-success-600">
                                    @if($coupon->discount_type === 'percentage')
                                        {{ $coupon->discount_value }}%
                                    @else
                                        ₱{{ number_format($coupon->discount_value, 2) }}
                                    @endif
                                </p>
                            </div>

                            @if($coupon->min_order_amount > 0)
                            <div class="text-center border-l border-neutral-200 pl-8">
                                <p class="text-sm text-neutral-600 mb-1">Minimum Order</p>
                                <p class="text-2xl font-bold text-neutral-800">₱{{ number_format($coupon->min_order_amount, 2) }}</p>
                            </div>
                            @endif

                            @if($coupon->max_discount_cap)
                            <div class="text-center border-l border-neutral-200 pl-8">
                                <p class="text-sm text-neutral-600 mb-1">Max Discount</p>
                                <p class="text-2xl font-bold text-neutral-800">₱{{ number_format($coupon->max_discount_cap, 2) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-4 text-sm text-neutral-600">
                        <span><i class="far fa-calendar mr-2"></i>{{ $coupon->valid_from->format('M d, Y') }}</span>
                        <span>→</span>
                        <span><i class="far fa-calendar-times mr-2"></i>{{ $coupon->valid_until->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-chart-bar text-info-600 mr-2"></i>Usage Statistics
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="text-center">
                        <p class="text-sm text-neutral-600 mb-2">Total Uses</p>
                        <p class="text-3xl font-bold text-primary-600">{{ $coupon->total_uses }}</p>
                        @if($coupon->total_uses_allowed)
                        <p class="text-xs text-neutral-500 mt-1">of {{ $coupon->total_uses_allowed }}</p>
                        @endif
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-neutral-600 mb-2">Pending</p>
                        <p class="text-3xl font-bold text-warning-600">{{ $stats['pending_uses'] }}</p>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-neutral-600 mb-2">Completed</p>
                        <p class="text-3xl font-bold text-success-600">{{ $stats['completed_uses'] }}</p>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-neutral-600 mb-2">Total Discount</p>
                        <p class="text-3xl font-bold text-success-600">₱{{ number_format($stats['total_discount'], 2) }}</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                @if($coupon->total_uses_allowed)
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-neutral-600 mb-2">
                        <span>Usage Limit Progress</span>
                        <span>{{ number_format(($coupon->total_uses / $coupon->total_uses_allowed) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-primary-500 to-success-500 h-3 rounded-full transition-all"
                             style="width: {{ min(100, ($coupon->total_uses / $coupon->total_uses_allowed) * 100) }}%">
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Recent Usage -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-neutral-800">
                        <i class="fas fa-history text-primary-600 mr-2"></i>Recent Usage
                    </h3>
                    <a href="{{ route('admin.coupons.usage', $coupon) }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                @if($recentUsages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Service Request</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsages as $usage)
                            <tr>
                                <td>
                                    <div>
                                        <p class="font-semibold text-neutral-800">{{ $usage->user->name }}</p>
                                        <p class="text-xs text-neutral-500">{{ $usage->user->email }}</p>
                                    </div>
                                </td>
                                <td>
                                    @if($usage->service_request_id)
                                    <a href="{{ route('admin.requests.show', $usage->service_request_id) }}" 
                                       class="text-primary-600 hover:underline">
                                        #{{ $usage->service_request_id }}
                                    </a>
                                    @else
                                    <span class="text-neutral-400">—</span>
                                    @endif
                                </td>
                                <td>₱{{ number_format($usage->order_amount, 2) }}</td>
                                <td class="font-semibold text-success-600">₱{{ number_format($usage->discount_amount, 2) }}</td>
                                <td>
                                    <span class="status-badge
                                        {{ $usage->payment_status === 'completed' ? 'status-completed' : '' }}
                                        {{ $usage->payment_status === 'pending' ? 'status-pending' : '' }}
                                        {{ $usage->payment_status === 'cancelled' ? 'status-cancelled' : '' }}">
                                        {{ ucfirst($usage->payment_status) }}
                                    </span>
                                </td>
                                <td class="text-sm text-neutral-600">{{ $usage->created_at->format('M d, Y g:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-neutral-500 py-8">No usage records yet</p>
                @endif
            </div>
        </div>

        <!-- Right Column - Details & Settings -->
        <div class="space-y-8">
            <!-- Details Card -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i>Details
                </h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Type</p>
                        <p class="text-sm font-semibold text-neutral-800">{{ ucfirst(str_replace('_', ' ', $coupon->coupon_type)) }}</p>
                    </div>

                    @if($coupon->user_id)
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Assigned To</p>
                        <p class="text-sm font-semibold text-neutral-800">{{ $coupon->user->name }}</p>
                        <p class="text-xs text-neutral-500">{{ $coupon->user->email }}</p>
                    </div>
                    @endif

                    @if($coupon->service_request_id)
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Service Request</p>
                        <a href="{{ route('admin.requests.show', $coupon->service_request_id) }}" 
                           class="text-sm font-semibold text-primary-600 hover:underline">
                            #{{ $coupon->service_request_id }}
                        </a>
                    </div>
                    @endif

                    <div class="border-t border-neutral-200 pt-4">
                        <p class="text-xs text-neutral-500 mb-1">Created</p>
                        <p class="text-sm text-neutral-700">{{ $coupon->created_at->format('M d, Y g:i A') }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Last Updated</p>
                        <p class="text-sm text-neutral-700">{{ $coupon->updated_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-cog text-neutral-600 mr-2"></i>Settings
                </h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-neutral-100">
                        <span class="text-sm text-neutral-700">Combine with Others</span>
                        <span class="inline-flex items-center">
                            @if($coupon->can_combine_with_others)
                                <i class="fas fa-check-circle text-success-500 mr-1"></i>
                                <span class="text-sm text-success-600 font-semibold">Yes</span>
                            @else
                                <i class="fas fa-times-circle text-error-500 mr-1"></i>
                                <span class="text-sm text-error-600 font-semibold">No</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-neutral-100">
                        <span class="text-sm text-neutral-700">Combine with Loyalty</span>
                        <span class="inline-flex items-center">
                            @if($coupon->can_combine_with_loyalty)
                                <i class="fas fa-check-circle text-success-500 mr-1"></i>
                                <span class="text-sm text-success-600 font-semibold">Yes</span>
                            @else
                                <i class="fas fa-times-circle text-error-500 mr-1"></i>
                                <span class="text-sm text-error-600 font-semibold">No</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-neutral-700">Uses Per User</span>
                        <span class="text-sm font-semibold text-neutral-800">
                            {{ $coupon->uses_per_user ?: 'Unlimited' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-bolt text-warning-600 mr-2"></i>Quick Actions
                </h3>

                <div class="space-y-3">
                    <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full btn-secondary justify-center">
                            <i class="fas fa-power-off mr-2"></i>
                            {{ $coupon->status === 'active' ? 'Deactivate' : 'Activate' }} Coupon
                        </button>
                    </form>

                    <button onclick="copyCouponCode()" class="w-full btn-secondary justify-center">
                        <i class="fas fa-copy mr-2"></i>Copy Code
                    </button>

                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this coupon? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-error justify-center">
                            <i class="fas fa-trash mr-2"></i>Delete Coupon
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-neutral-800">QR Code</h3>
            <button onclick="closeQRModal()" class="text-neutral-400 hover:text-neutral-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div class="text-center mb-6">
            <div id="qrcode" class="inline-block p-4 bg-white border-4 border-primary-100 rounded-xl"></div>
        </div>

        <div class="text-center">
            <p class="text-2xl font-bold text-primary-600 tracking-wider font-mono mb-2">{{ $coupon->code }}</p>
            <p class="text-sm text-neutral-600">Scan to apply this coupon</p>
        </div>

        <button onclick="downloadQRCode()" class="w-full btn-primary mt-6">
            <i class="fas fa-download mr-2"></i>Download QR Code
        </button>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    let qrcode = null;

    function showQRCode() {
        const modal = document.getElementById('qrModal');
        modal.classList.remove('hidden');

        if (!qrcode) {
            const qrcodeContainer = document.getElementById('qrcode');
            qrcodeContainer.innerHTML = ''; // Clear previous QR code if any
            
            qrcode = new QRCode(qrcodeContainer, {
                text: '{{ $coupon->code }}',
                width: 256,
                height: 256,
                colorDark: '#3b82f6',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    }

    function closeQRModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    function downloadQRCode() {
        const canvas = document.querySelector('#qrcode canvas');
        if (canvas) {
            const url = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = 'coupon-{{ $coupon->code }}.png';
            link.href = url;
            link.click();
        }
    }

    function copyCouponCode() {
        const code = '{{ $coupon->code }}';
        navigator.clipboard.writeText(code).then(() => {
            alert('Coupon code copied to clipboard!');
        });
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQRModal();
        }
    });

    // Close modal on outside click
    document.getElementById('qrModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeQRModal();
        }
    });
</script>
@endpush
@endsection
