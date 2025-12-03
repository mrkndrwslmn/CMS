@props([
    'headers' => [],
    'striped' => false,
])

{{--
    Table Component
    
    Usage:
    <x-ui.table :headers="['Name', 'Email', 'Role', 'Actions']">
        @foreach($users as $user)
            <tr>
                <x-ui.table-cell>{{ $user->name }}</x-ui.table-cell>
                <x-ui.table-cell>{{ $user->email }}</x-ui.table-cell>
                <x-ui.table-cell>{{ $user->role }}</x-ui.table-cell>
                <x-ui.table-cell>...</x-ui.table-cell>
            </tr>
        @endforeach
    </x-ui.table>
--}}

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-neutral-100 shadow-sm']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-100">
            @if(count($headers) > 0)
                <thead class="bg-neutral-50">
                    <tr>
                        @foreach($headers as $header)
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-neutral-100 bg-white {{ $striped ? '[&>tr:nth-child(even)]:bg-neutral-50' : '' }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
