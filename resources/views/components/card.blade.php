@props([
    'variant' => 'default',
    'padding' => 'md',
    'hover' => false,
    'class' => '',
])

<?php
$variants = [
    'default' => 'bg-white border border-gray-200 shadow-sm',
    'outlined' => 'bg-white border border-gray-200',
    'elevated' => 'bg-white border border-gray-100 shadow-lg',
    'ghost' => 'bg-transparent border border-transparent',
];

$paddings = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
];

$variantClass = $variants[$variant] ?? $variants['default'];
$paddingClass = $paddings[$padding] ?? $paddings['md'];
$hoverClass = $hover ? 'hover:shadow-md hover:border-gray-300 transition-shadow duration-200 cursor-pointer' : '';
?>

<div class="rounded-xl {{ $variantClass }} {{ $paddingClass }} {{ $hoverClass }} {{ $class }}" {{ $attributes }}>
    {{ $slot }}
</div>