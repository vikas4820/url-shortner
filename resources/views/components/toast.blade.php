@props([
    'message' => '',
    'type' => 'success', // success, error, warning, info
])

@php
$types = [
    'success' => 'bg-emerald-600',
    'error' => 'bg-red-600',
    'warning' => 'bg-amber-600',
    'info' => 'bg-indigo-600',
];
$bgColor = $types[$type] ?? $types['success'];
@endphp

<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 5000)"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform opacity-0 translate-y-2"
    x-transition:enter-end="transform opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="transform opacity-100 translate-y-0"
    x-transition:leave-end="transform opacity-0 translate-y-2"
    class="fixed bottom-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white {{ $bgColor }}"
    role="alert"
    aria-live="polite"
>
    <span class="flex-1 text-sm font-medium">{{ $message }}</span>
    <button
        @click="show = false"
        class="flex-shrink-0 text-white/80 hover:text-white transition-opacity"
        aria-label="Dismiss"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>