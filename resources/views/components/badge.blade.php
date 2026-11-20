@props([
    'variant' => 'default',
    'size' => 'md',
    'dot' => false,
    'icon' => null,
    'class' => '',
])

<?php
$variants = [
    'default' => 'bg-gray-100 text-gray-700 border border-gray-200',
    'primary' => 'bg-indigo-50 text-indigo-700 border border-indigo-100',
    'success' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
    'warning' => 'bg-amber-50 text-amber-700 border border-amber-100',
    'danger' => 'bg-red-50 text-red-700 border border-red-100',
    'info' => 'bg-sky-50 text-sky-700 border border-sky-100',
    'purple' => 'bg-purple-50 text-purple-700 border border-purple-100',
];

$sizes = [
    'xs' => 'px-2 py-0.5 text-xs gap-1',
    'sm' => 'px-2.5 py-0.5 text-xs gap-1',
    'md' => 'px-3 py-1 text-sm gap-1.5',
    'lg' => 'px-4 py-1.5 text-base gap-2',
];

$variantClass = $variants[$variant] ?? $variants['default'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
?>

<span class="inline-flex items-center font-medium rounded-full border {{ $variantClass }} {{ $sizeClass }} {{ $class }}" {{ $attributes }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
    @endif

@if ($icon)
    <span class="flex-shrink-0">{!! $icon !!}</span>
@endif

    {{ $slot }}
</span>