@props([
    'src' => null,
    'alt' => '',
    'name' => null,
    'size' => 'md',
    'shape' => 'circle',
    'class' => '',
])

<?php
$sizes = [
    'xs' => 'w-6 h-6 text-xs',
    'sm' => 'w-8 h-8 text-sm',
    'md' => 'w-10 h-10 text-base',
    'lg' => 'w-12 h-12 text-lg',
    'xl' => 'w-16 h-16 text-xl',
    '2xl' => 'w-24 h-24 text-2xl',
];

$shapeClass = $shape === 'square' ? 'rounded-lg' : 'rounded-full';
$sizeClass = $sizes[$size] ?? $sizes['md'];

$initials = '';
$colorIndex = 0;
if ($name) {
    $parts = explode(' ', $name);
    $initials = strtoupper(($parts[0][0] ?? '') . ($parts[1][0] ?? ''));
    // Generate consistent color from name
    $colorIndex = abs(crc32($name)) % 10;
}

$colors = [
    'bg-indigo-100 text-indigo-700',
    'bg-purple-100 text-purple-700',
    'bg-pink-100 text-pink-700',
    'bg-red-100 text-red-700',
    'bg-orange-100 text-orange-700',
    'bg-amber-100 text-amber-700',
    'bg-yellow-100 text-yellow-700',
    'bg-lime-100 text-lime-700',
    'bg-green-100 text-green-700',
    'bg-emerald-100 text-emerald-700',
    'bg-teal-100 text-teal-700',
    'bg-cyan-100 text-cyan-700',
    'bg-sky-100 text-sky-700',
    'bg-blue-100 text-blue-700',
];

$colorClass = $colors[$colorIndex % count($colors)] ?? $colors[0];
?>

@if ($src)
    <img
        src="{{ $src }}"
        alt="{{ $alt ?: $name }}"
        class="{{ $sizeClass }} {{ $shapeClass }} object-cover {{ $class }}"
        {{ $attributes }}
    />
@else
    <div
        class="{{ $sizeClass }} {{ $shapeClass }} {{ $colorClass }} flex items-center justify-center font-semibold {{ $class }}"
        {{ $attributes }}
    >
        {{ $initials }}
    </div>
@endif