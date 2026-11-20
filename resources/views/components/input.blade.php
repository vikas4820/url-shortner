@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'hint' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'id' => null,
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'autocomplete' => 'off',
    'class' => '',
])

<?php
$id = $id ?? $name ?? 'input-' . uniqid();
$baseClasses = 'block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 placeholder:text-gray-400 transition-all duration-200';
$focusClasses = 'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent';
$disabledClasses = 'disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-50';
$readonlyClasses = 'readonly:bg-gray-50';
$iconPadding = $icon ? ($iconPosition === 'left' ? 'pl-11' : 'pr-11') : '';
?>

<div class="w-full">
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
@if ($icon && $iconPosition === 'left')
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
        {!! $icon !!}
    </div>
@endif

        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder }}"
            autocomplete="{{ $autocomplete }}"
            {{ $attributes->merge(['class' => $baseClasses . ' ' . $focusClasses . ' ' . $disabledClasses . ' ' . $readonlyClasses . ' ' . $iconPadding . ' ' . $class]) }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $required ? 'required' : '' }}
        />

@if ($icon && $iconPosition === 'right')
    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
        {!! $icon !!}
    </div>
@endif
    </div>

    @if ($error)
        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ $error }}
        </p>
    @elseif ($hint)
        <p class="mt-1.5 text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>