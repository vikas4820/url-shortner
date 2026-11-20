@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'fullWidth' => false,
    'href' => null,
    'class' => '',
])

<?php
$variants = [
    'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500 border border-transparent',
    'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-indigo-500',
    'outline' => 'bg-transparent text-indigo-600 border border-indigo-600 hover:bg-indigo-50 focus:ring-indigo-500',
    'ghost' => 'bg-transparent text-gray-600 hover:bg-gray-100 focus:ring-gray-500 border border-transparent',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 border border-transparent',
    'danger-outline' => 'bg-transparent text-red-600 border border-red-600 hover:bg-red-50 focus:ring-red-500',
    'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 border border-transparent',
];

$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs gap-1.5',
    'sm' => 'px-3 py-2 text-sm gap-2',
    'md' => 'px-4 py-2.5 text-sm gap-2',
    'lg' => 'px-6 py-3 text-base gap-2.5',
    'xl' => 'px-8 py-4 text-lg gap-3',
];

$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
$variantClass = $variants[$variant] ?? $variants['primary'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
$widthClass = $fullWidth ? 'w-full' : '';

$isLink = ! is_null($href);
$tag = $isLink ? 'a' : 'button';
$disabledAttr = $disabled || $loading ? 'disabled' : '';
$ariaDisabled = $disabled || $loading ? 'aria-disabled="true"' : '';
?>

<{{ $tag }}
    @if(! $isLink)
        type="{{ $type }}"
    @endif
    @if($isLink)
        href="{{ $href }}"
    @endif
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClass . ' ' . $sizeClass . ' ' . $widthClass . ' ' . $class]) }}
    {{ $disabledAttr }}
    {{ $ariaDisabled }}
>
    @if ($loading)
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif ($icon && $iconPosition === 'left')
        <span class="flex-shrink-0">{!! $icon !!}</span>
    @endif

    <span>{{ $slot }}</span>

    @if ($icon && $iconPosition === 'right')
        <span class="flex-shrink-0">{!! $icon !!}</span>
    @endif
</{{ $tag }}>