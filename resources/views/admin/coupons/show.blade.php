@extends('admin.layouts.app')

@section('title', 'Coupon Details - ' . $coupon->code)

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Coupons', 'route' => 'admin.coupons.index', 'icon' => 'ticket'],
        ['label' => $coupon->code, 'icon' => 'tag'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start justify-between gap-4 mb-6">
        <x-ui.page-header 
            :title="$coupon->code" 
            :description="$coupon->description ?: 'Discount Coupon'"
        />
        <div class="flex gap-3">
            <a href="{{ route('admin.coupons.index') }}">
                <x-ui.button variant="ghost" icon="arrow-left">
                    Back
                </x-ui.button>
            </a>
            <a href="{{ route('admin.coupons.edit', $coupon) }}">
                <x-ui.button variant="primary" icon="pencil">
                    Edit
                </x-ui.button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Coupon Card -->
            <x-ui.card class="bg-gradient-to-br from-primary-50 via-white to-success-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500 opacity-5 rounded-full -mr-32 -mt-32"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                        <div class="flex flex-wrap gap-2">
                            @php
                                $statusVariant = $coupon->status === 'active' ? 'success' : 'neutral';
                                $typeVariant = $coupon->coupon_type === 'public' ? 'info' : 'warning';
                            @endphp
                            <x-ui.badge :variant="$statusVariant">
                                <x-lucide-circle class="w-3 h-3 mr-1 {{ $coupon->status === 'active' ? 'fill-current' : '' }}" />
                                {{ $coupon->status === 'active' ? 'Active' : 'Inactive' }}
                            </x-ui.badge>
                            <x-ui.badge :variant="$typeVariant">
                                @if($coupon->coupon_type === 'public')
                                    <x-lucide-globe class="w-3 h-3 mr-1" />
                                @else
                                    <x-lucide-user class="w-3 h-3 mr-1" />
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $coupon->coupon_type)) }}
                            </x-ui.badge>
                        </div>

                        <!-- QR Code Button -->
                        <x-ui.button variant="ghost" onclick="showQRCode()">
                            <x-lucide-qr-code class="w-4 h-4 mr-2" />
                            QR Code
                        </x-ui.button>
                    </div>

                    <div class="text-center py-8">
                        <div class="inline-block px-8 py-4 bg-white rounded-2xl shadow-lg border-2 border-dashed border-primary-300 mb-4">
                            <p class="text-5xl font-bold text-primary-600 tracking-wider font-mono">{{ $coupon->code }}</p>
                        </div>

                        <div class="flex flex-wrap items-center justify-center gap-8 mt-6">
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
                        <span class="flex items-center">
                            <x-lucide-calendar class="w-4 h-4 mr-2" />
                            {{ $coupon->valid_from ? $coupon->valid_from->format('M d, Y') : 'No start date' }}
                        </span>
                        <span>→</span>
                        <span class="flex items-center">
                            <x-lucide-calendar-x class="w-4 h-4 mr-2" />
                            {{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'No end date' }}
                        </span>
                    </div>
                </div>
            </x-ui.card>

            <!-- Usage Statistics -->
            <x-ui.card>
                <h3 class="text-lg font-bold text-neutral-800 mb-6 flex items-center">
                    <x-lucide-bar-chart-3 class="w-5 h-5 text-info-600 mr-2" />
                    Usage Statistics
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                    <div class="text-center p-4 bg-primary-50 rounded-xl">
                        <p class="text-sm text-neutral-600 mb-2">Total Uses</p>
                        <p class="text-3xl font-bold text-primary-600">{{ $coupon->total_uses }}</p>
                        @if($coupon->total_uses_allowed)
                        <p class="text-xs text-neutral-500 mt-1">of {{ $coupon->total_uses_allowed }}</p>
                        @endif
                    </div>

                    <div class="text-center p-4 bg-warning-50 rounded-xl">
                        <p class="text-sm text-neutral-600 mb-2">Pending</p>
                        <p class="text-3xl font-bold text-warning-600">{{ $stats['pending_uses'] }}</p>
                    </div>

                    <div class="text-center p-4 bg-success-50 rounded-xl">
                        <p class="text-sm text-neutral-600 mb-2">Completed</p>
                        <p class="text-3xl font-bold text-success-600">{{ $stats['completed_uses'] }}</p>
                    </div>

                    <div class="text-center p-4 bg-success-50 rounded-xl">
                        <p class="text-sm text-neutral-600 mb-2">Total Discount</p>
                        <p class="text-3xl font-bold text-success-600">₱{{ number_format($stats['total_discount'], 2) }}</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                @if($coupon->total_uses_allowed)
                <div>
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
            </x-ui.card>

            <!-- Recent Usage -->
            <x-ui.card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-neutral-800 flex items-center">
                        <x-lucide-history class="w-5 h-5 text-primary-600 mr-2" />
                        Recent Usage
                    </h3>
                    <a href="{{ route('admin.coupons.usage', $coupon) }}" class="text-primary-600 hover:text-primary-700 text-sm font-semibold flex items-center">
                        View All <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                    </a>
                </div>

                @if($recentUsages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-neutral-50 border-b border-neutral-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Service Request</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Discount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200">
                            @foreach($recentUsages as $usage)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-semibold text-neutral-800">{{ $usage->user->name }}</p>
                                        <p class="text-xs text-neutral-500">{{ $usage->user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($usage->service_request_id)
                                    <a href="{{ route('admin.requests.show', $usage->service_request_id) }}" 
                                       class="text-primary-600 hover:underline">
                                        #{{ $usage->service_request_id }}
                                    </a>
                                    @else
                                    <span class="text-neutral-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">₱{{ number_format($usage->order_amount, 2) }}</td>
                                <td class="px-4 py-3 font-semibold text-success-600">₱{{ number_format($usage->discount_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $usageVariant = match($usage->payment_status) {
                                            'completed' => 'success',
                                            'pending' => 'warning',
                                            'cancelled' => 'error',
                                            default => 'neutral'
                                        };
                                    @endphp
                                    <x-ui.badge :variant="$usageVariant" size="sm">
                                        {{ ucfirst($usage->payment_status) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-4 py-3 text-sm text-neutral-600">{{ $usage->created_at->format('M d, Y g:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <x-ui.empty-state
                    icon="receipt"
                    title="No usage records yet"
                    description="This coupon hasn't been used yet"
                    compact
                />
                @endif
            </x-ui.card>
        </div>

        <!-- Right Column - Details & Settings -->
        <div class="space-y-6">
            <!-- Details Card -->
            <x-ui.card>
                <h3 class="text-lg font-bold text-neutral-800 mb-6 flex items-center">
                    <x-lucide-info class="w-5 h-5 text-primary-600 mr-2" />
                    Details
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
            </x-ui.card>

            <!-- Settings Card -->
            <x-ui.card>
                <h3 class="text-lg font-bold text-neutral-800 mb-6 flex items-center">
                    <x-lucide-settings class="w-5 h-5 text-neutral-600 mr-2" />
                    Settings
                </h3>

                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-neutral-100">
                        <span class="text-sm text-neutral-700">Combine with Others</span>
                        <span class="inline-flex items-center">
                            @if($coupon->can_combine_with_others)
                                <x-lucide-check-circle class="w-4 h-4 text-success-500 mr-1" />
                                <span class="text-sm text-success-600 font-semibold">Yes</span>
                            @else
                                <x-lucide-x-circle class="w-4 h-4 text-error-500 mr-1" />
                                <span class="text-sm text-error-600 font-semibold">No</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-2 border-b border-neutral-100">
                        <span class="text-sm text-neutral-700">Combine with Loyalty</span>
                        <span class="inline-flex items-center">
                            @if($coupon->can_combine_with_loyalty)
                                <x-lucide-check-circle class="w-4 h-4 text-success-500 mr-1" />
                                <span class="text-sm text-success-600 font-semibold">Yes</span>
                            @else
                                <x-lucide-x-circle class="w-4 h-4 text-error-500 mr-1" />
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
            </x-ui.card>

            <!-- Actions Card -->
            <x-ui.card>
                <h3 class="text-lg font-bold text-neutral-800 mb-6 flex items-center">
                    <x-lucide-zap class="w-5 h-5 text-warning-600 mr-2" />
                    Quick Actions
                </h3>

                <div class="space-y-3">
                    <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST">
                        @csrf
                        <x-ui.button type="submit" variant="ghost" class="w-full justify-center">
                            <x-lucide-power class="w-4 h-4 mr-2" />
                            {{ $coupon->status === 'active' ? 'Deactivate' : 'Activate' }} Coupon
                        </x-ui.button>
                    </form>

                    <x-ui.button variant="ghost" class="w-full justify-center" onclick="copyCouponCode()">
                        <x-lucide-copy class="w-4 h-4 mr-2" />
                        Copy Code
                    </x-ui.button>

                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" 
                          onsubmit="return window.Alerts.confirmDeleteForm(event, 'Delete Coupon', 'Are you sure you want to delete this coupon? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <x-ui.button type="submit" variant="danger" class="w-full justify-center">
                            <x-lucide-trash-2 class="w-4 h-4 mr-2" />
                            Delete Coupon
                        </x-ui.button>
                    </form>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50" x-data>
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-neutral-800">QR Code</h3>
            <button onclick="closeQRModal()" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                <x-lucide-x class="w-6 h-6" />
            </button>
        </div>

        <div class="text-center mb-6">
            <div id="qrcode" class="inline-block p-4 bg-white border-4 border-primary-100 rounded-xl"></div>
        </div>

        <div class="text-center">
            <p class="text-2xl font-bold text-primary-600 tracking-wider font-mono mb-2">{{ $coupon->code }}</p>
            <p class="text-sm text-neutral-600">Scan to apply this coupon</p>
        </div>

        <x-ui.button onclick="downloadQRCode()" class="w-full mt-6" icon="download">
            Download QR Code
        </x-ui.button>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    let qrcode = null;

    function showQRCode() {
        const modal = document.getElementById('qrModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

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
        const modal = document.getElementById('qrModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
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
            window.toast.success('Coupon code copied to clipboard!');
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
