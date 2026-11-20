@props([
    'items' => [],
    'class' => '',
])

<nav class="flex flex-col gap-1 {{ $class }}" {{ $attributes }} aria-label="Main navigation">
    @foreach ($items as $item)
        @if (isset($item['children']))
            {{-- Parent item with children --}}
            <div class="relative">
                <button
                    type="button"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors group"
                    aria-expanded="false"
                    aria-controls="submenu-{{ $loop->index }}"
                >
<span class="flex items-center gap-3">
    @if (isset($item['icon']))
        <span class="flex-shrink-0 w-5 h-5">{!! $item['icon'] !!}</span>
    @endif
    {{ $item['label'] }}
</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-transform duration-200 group-data-[open=true]:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div
                    id="submenu-{{ $loop->index }}"
                    class="hidden absolute left-full top-0 mt-2 w-56 rounded-lg bg-white shadow-lg border border-gray-200 divide-y divide-gray-100 ring-1 ring-black ring-opacity-5 group-focus-within:block group-hover:block z-50"
                    role="menu"
                >
                    @foreach ($item['children'] as $child)
                        @if (isset($child['can']) && ! auth()->user()?->can($child['can']))
                            @continue
                        @endif

                        <a
                            href="{{ $child['url'] }}"
                            class="flex items-center gap-3 px-3 py-2 text-sm text-gray-600 rounded-none hover:bg-gray-50 hover:text-gray-900 transition-colors first:rounded-t-lg last:rounded-b-lg"
                            role="menuitem"
                        >
@if (isset($child['icon']))
    <span class="flex-shrink-0 w-5 h-5">{!! $child['icon'] !!}</span>
@endif
                            {{ $child['label'] }}
                            @if (isset($child['badge']))
                                <x-badge variant="{{ $child['badge']['variant'] ?? 'default' }}" size="xs">{{ $child['badge']['label'] }}</x-badge>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Simple item --}}
            @if (isset($item['can']) && ! auth()->user()?->can($item['can']))
                @continue
            @endif

            <a
                href="{{ $item['url'] }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors {{ request()->routeIs($item['route'] ?? '') ? 'bg-indigo-50 text-indigo-700' : '' }}"
                {{ isset($item['route']) && request()->routeIs($item['route']) ? 'aria-current="page"' : '' }}
            >
@if (isset($item['icon']))
    <span class="flex-shrink-0 w-5 h-5">{!! $item['icon'] !!}</span>
@endif
                {{ $item['label'] }}
                @if (isset($item['badge']))
                    <x-badge variant="{{ $item['badge']['variant'] ?? 'default' }}" size="xs">{{ $item['badge']['label'] }}</x-badge>
                @endif
            </a>
        @endif
    @endforeach
</nav>