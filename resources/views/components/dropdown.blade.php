@props([
    'align' => 'right',
    'width' => '48',
    'class' => '',
])

<div
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="relative inline-block text-left"
    {{ $attributes }}
>
    <button
        @click="open = !open"
        @keydown.space.prevent="open = !open"
        @keydown.enter.prevent="open = !open"
        @keydown.arrow-down.prevent="open = true; $refs.menu?.focus()"
        type="button"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
        aria-expanded="false"
        aria-haspopup="true"
        aria-label="User menu"
    >
        {{ $trigger }}
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 min-w-[{{ $width }}rem] origin-top-right rounded-lg bg-white shadow-lg border border-gray-200 divide-y divide-gray-100 ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu"
        aria-orientation="vertical"
        aria-label="User menu"
        ref="menu"
        tabindex="-1"
    >
        {{ $slot }}
    </div>
</div>