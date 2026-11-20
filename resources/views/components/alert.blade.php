@props([
    'variant' => 'info',
    'dismissible' => false,
    'icon' => true,
    'title' => null,
    'class' => '',
])

<?php
$variants = [
    'success' => [
        'bg' => 'bg-emerald-50',
        'border' => 'border-emerald-200',
        'text' => 'text-emerald-800',
        'iconBg' => 'bg-emerald-100',
        'iconColor' => 'text-emerald-600',
        'iconSvg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
    ],
    'error' => [
        'bg' => 'bg-red-50',
        'border' => 'border-red-200',
        'text' => 'text-red-800',
        'iconBg' => 'bg-red-100',
        'iconColor' => 'text-red-600',
        'iconSvg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
    ],
    'warning' => [
        'bg' => 'bg-amber-50',
        'border' => 'border-amber-200',
        'text' => 'text-amber-800',
        'iconBg' => 'bg-amber-100',
        'iconColor' => 'text-amber-600',
        'iconSvg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
    ],
    'info' => [
        'bg' => 'bg-sky-50',
        'border' => 'border-sky-200',
        'text' => 'text-sky-800',
        'iconBg' => 'bg-sky-100',
        'iconColor' => 'text-sky-600',
        'iconSvg' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
    ],
];

$variant = $variants[$variant] ?? $variants['info'];
?>

<div class="rounded-lg border {{ $variant['bg'] }} {{ $variant['border'] }} {{ $class }}" {{ $attributes }} role="alert">
    <div class="flex gap-3 p-4">
        @if ($icon)
            <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ $variant['iconBg'] }} flex items-center justify-center {{ $variant['iconColor'] }}">
                {!! $variant['iconSvg'] !!}
            </div>
        @endif

        <div class="flex-1 min-w-0">
            @if ($title)
                <h4 class="text-sm font-semibold {{ $variant['text'] }}">{{ $title }}</h4>
            @endif

            <div class="{{ $title ? 'mt-1' : '' }} text-sm {{ $variant['text'] }}">
                {{ $slot }}
            </div>
        </div>

        @if ($dismissible)
            <button
                type="button"
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
                onclick="this.closest('[role=alert]').remove()"
                aria-label="Dismiss"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        @endif
    </div>
</div>