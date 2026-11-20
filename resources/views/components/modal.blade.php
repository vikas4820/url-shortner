@props([
    'open' => false,
    'title' => null,
    'size' => 'md',
    'closeable' => true,
    'class' => '',
])

<?php
$sizes = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    'full' => 'max-w-4xl',
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
?>

@if ($open)
<div
    x-data="{ open: true }"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $title ? 'modal-title' : '' }}"
    {{ $attributes }}
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50"
        @click="open = false"
        aria-hidden="true"
    ></div>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="flex min-h-full items-center justify-center p-4 text-center"
    >
        <div
            class="w-full {{ $sizeClass }} transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-xl transition-all"
        >
            @if ($title || $closeable)
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    @if ($title)
                        <h3 id="modal-title" class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    @endif

                    @if ($closeable)
                        <button
                            type="button"
                            @click="open = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="Close modal"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
@endif