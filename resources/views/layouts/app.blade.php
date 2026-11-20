<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'URL Shortener') }} @yield('title')</title>

    <!-- Preconnect for fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS with custom config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        },
                        surface: {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                            950: '#0a0a0a',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 2px 15px -3px rgba(0, 0, 0, 0.07), 0 10px 20px -2px rgba(0, 0, 0, 0.04)',
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05)',
                        'elevated': '0 10px 40px -10px rgba(0, 0, 0, 0.1), 0 2px 10px -2px rgba(0, 0, 0, 0.05)',
                    },
                    borderRadius: {
                        'xl': '0.75rem',
                        '2xl': '1rem',
                    },
                    transitionDuration: {
                        '200': '200ms',
                        '300': '300ms',
                    },
                },
            },
        }
    </script>

    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* Focus visible for accessibility */
        :focus-visible {
            outline: 2px solid #6366f1;
            outline-offset: 2px;
        }

        /* Smooth transitions */
        * { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }

        /* Sidebar animation */
        .sidebar-enter {
            transform: translateX(-100%);
        }
        .sidebar-enter-active {
            transform: translateX(0);
            transition: transform 300ms ease-out;
        }
        .sidebar-leave {
            transform: translateX(0);
        }
        .sidebar-leave-active {
            transform: translateX(-100%);
            transition: transform 200ms ease-in;
        }
    </style>

    @stack('head')
</head>
<body class="bg-surface-50 min-h-screen antialiased">
    @if (auth()->check())
        <!-- Mobile sidebar overlay -->
        <div
            x-data="{ sidebarOpen: false }"
            @keydown.escape.window="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
            :class="sidebarOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'"
            @click="sidebarOpen = false"
            aria-hidden="true"
        ></div>

        <!-- Sidebar -->
        <aside
            x-data="{ sidebarOpen: false }"
            @keydown.escape.window="sidebarOpen = false"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            aria-label="Main navigation"
        >
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center h-16 px-6 border-b border-gray-100">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2" aria-label="{{ config('app.name', 'URL Shortener') }} Home">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-semibold text-gray-900">{{ config('app.name', 'URL Shortener') }}</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto" aria-label="Main navigation">
                    @php
                        $navigationItems = [
                            [
                                'label' => 'Dashboard',
                                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>',
                                'url' => route('dashboard'),
                                'route' => 'dashboard',
                            ],
                        ];

                        if (auth()->user()->can('manage_users')) {
                            $navigationItems[] = [
                                'label' => 'Users',
                                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                                'url' => route('users.index'),
                                'route' => 'users.index',
                                'can' => 'manage_users',
                            ];
                            $navigationItems[] = [
                                'label' => 'Invitations',
                                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                                'url' => route('invitations.index'),
                                'route' => 'invitations.index',
                                'can' => 'manage_users',
                            ];
                        }

                        if (auth()->user()->can('viewAny', App\Models\ShortUrl::class)) {
                            $navigationItems[] = [
                                'label' => 'Short URLs',
                                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>',
                                'url' => route('short-urls.index'),
                                'route' => 'short-urls.index',
                                'can' => 'viewAny, App\Models\ShortUrl::class',
                            ];
                        }
                    @endphp

                    @foreach ($navigationItems as $item)
                        @if (isset($item['can']) && ! auth()->user()->can($item['can']))
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
                        </a>
                    @endforeach
                </nav>

                <!-- User section -->
                <div class="p-4 border-t border-gray-100">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <x-avatar :name="auth()->user()->name" size="md" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->getRoleNames()->first() ?? 'none' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button
                            type="submit"
                            class="flex items-center gap-3 w-full px-3 py-2.5 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors"
                        >
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile sidebar toggle button -->
        <button
            x-data="{ sidebarOpen: false }"
            @click="sidebarOpen = true"
            @keydown.escape.window="sidebarOpen = false"
            class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-white shadow-lg border border-gray-200"
            aria-label="Open navigation menu"
            aria-expanded="false"
        >
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Main content -->
        <div class="lg:pl-64 min-h-screen">
            <!-- Top header -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-sm border-b border-gray-100 lg:hidden">
                <div class="flex items-center justify-between h-16 px-4">
                    <a href="{{ route('dashboard') }}" class="text-xl font-semibold text-gray-900">{{ config('app.name', 'URL Shortener') }}</a>
                    <div class="flex items-center gap-2">
                        <x-avatar :name="auth()->user()->name" size="sm" />
                    </div>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <x-toast message="{{ session('success') }}" type="success" />
                @endif

                @if (session('error'))
                    <x-toast message="{{ session('error') }}" type="error" />
                @endif

                @if ($errors->any())
                    <x-toast message="Validation error. Please check the form." type="error" />
                @endif

                @yield('content')
            </main>
        </div>
    @else
        <!-- Guest layout -->
        <main class="min-h-screen flex items-center justify-center p-4">
            @yield('content')
        </main>
    @endif

    <!-- Alpine.js for dropdowns and modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>