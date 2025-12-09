@extends('admin.layouts.app')

@section('title', 'Archived Adiutors')
@section('page-title', 'Adiutor Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Adiutors', 'route' => 'admin.adiutors.index', 'icon' => 'hard-hat'],
        ['label' => 'Archived', 'icon' => 'archive'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Archived Adiutors" 
            description="View and restore archived adiutor accounts"
        />
        <a href="{{ route('admin.adiutors.index') }}">
            <x-ui.button variant="secondary" icon="arrow-left">
                Back to Active Adiutors
            </x-ui.button>
        </a>
    </div>

    <!-- Archived Adiutors Table -->
    <x-ui.card>
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex items-center gap-2">
                <x-lucide-archive class="w-5 h-5 text-neutral-400" />
                <h3 class="text-lg font-medium text-neutral-700">Archived Adiutors List</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Adiutor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Contact</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Earnings Balance</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Archived Date</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($adiutors as $adiutor)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-neutral-200 rounded-full flex items-center justify-center">
                                    <x-lucide-hard-hat class="w-5 h-5 text-neutral-500" />
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-800">{{ $adiutor->fullName }}</div>
                                    <div class="text-xs text-neutral-500">ID: {{ str_pad($adiutor->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-neutral-700">{{ $adiutor->email }}</div>
                            <div class="text-xs text-neutral-500">{{ $adiutor->phoneNumber }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-800">₱{{ number_format($adiutor->work_earnings_balance ?? 0, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-700">{{ $adiutor->updated_at->format('M d, Y') }}</div>
                            <div class="text-xs text-neutral-500">{{ $adiutor->updated_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <form action="{{ route('admin.adiutors.restore', $adiutor->id) }}" method="POST" class="inline">
                                @csrf
                                <x-ui.button type="submit" size="sm" icon="rotate-ccw">
                                    Restore
                                </x-ui.button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12">
                            <x-ui.empty-state 
                                icon="archive"
                                title="No archived adiutors"
                                description="There are no archived adiutors at this time."
                            >
                                <a href="{{ route('admin.adiutors.index') }}">
                                    <x-ui.button variant="secondary" icon="arrow-left">
                                        Back to Active Adiutors
                                    </x-ui.button>
                                </a>
                            </x-ui.empty-state>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(is_object($adiutors) && method_exists($adiutors, 'hasPages') && $adiutors->hasPages())
        <div class="px-6 py-4 border-t border-neutral-100">
            <x-ui.pagination :paginator="$adiutors" />
        </div>
        @endif
    </x-ui.card>
</div>
@endsection
