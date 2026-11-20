@props([
    'label' => null,
    'error' => null,
    'hint' => null,
    'disabled' => false,
    'required' => false,
    'id' => null,
    'name' => null,
    'value' => null,
    'options' => [],
    'placeholder' => 'Select an option',
    'class' => '',
    'multiple' => false,
])

<?php
$id = $id ?? $name ?? 'select-' . uniqid();
$baseClasses = 'block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 transition-all duration-200 appearance-none';
$focusClasses = 'focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent';
$disabledClasses = 'disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-50';
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
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            {{ $attributes->merge(['class' => $baseClasses . ' ' . $focusClasses . ' ' . $disabledClasses . ' ' . $class . ' pr-10']) }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $multiple ? 'multiple' : '' }}
        >
            @if (! $multiple)
                <option value="" disabled {{ is_null($value) && ! old($name) ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif

            @foreach ($options as $key => $option)
                <?php
                $optionValue = is_array($option) ? $key : $option;
                $optionLabel = is_array($option) ? $option : ucfirst($option);
                $isSelected = (old($name, $value) == $optionValue);
                ?>
                <option value="{{ $optionValue }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>

        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
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