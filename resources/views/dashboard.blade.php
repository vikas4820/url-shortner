@extends('layouts.app')

@section('title', ' - Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-gray-600">Welcome back, {{ auth()->user()->name }}! Here's what's happening.</p>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-3">
            @can('manage_users')
                <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'></path></svg>" href="{{ route('users.create') }}">
                    Add User
                </x-button>
                <x-button variant="secondary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'></path></svg>" href="{{ route('invitations.create') }}">
                    Send Invitation
                </x-button>
            @endcan

            @can('create', App\Models\ShortUrl::class)
                <x-button variant="primary" icon="<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>" href="{{ route('short-urls.create') }}">
                    Create Short URL
                </x-button>
            @endcan
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @can('manage_users')
        <x-card variant="default" padding="lg" hover>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_users'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1">
                    View users
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </x-card>

        <x-card variant="default" padding="lg" hover>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending Invitations</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['pending_invitations'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('invitations.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1">
                    View invitations
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </x-card>
        @endcan

        @can('view_all_short_urls')
        <x-card variant="default" padding="lg" hover>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Company Short URLs</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_short_urls'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('short-urls.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1">
                    View short URLs
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </x-card>
        @elsecan('view_short_url_details')
        <x-card variant="default" padding="lg" hover>
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Accessible Short URLs</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['total_short_urls'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('short-urls.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1">
                    View short URLs
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </x-card>
        @endcan
    </div>

    <!-- Quick Stats / Recent Activity -->
    @if (auth()->user()->can('manage_users'))
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <x-card variant="default" padding="none">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                    <a href="{{ route('users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">View all</a>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @php
                    $recentUsers = auth()->user()->company->users()->latest()->take(5)->get();
                    
                    $roleBadgeMap = [
                        'super_admin' => 'purple',
                        'admin' => 'primary',
                        'manager' => 'success',
                        'sales' => 'warning',
                        'member' => 'default',
                    ];
                @endphp
                @forelse ($recentUsers as $user)
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                        <x-avatar :name="$user->name" size="sm" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        @php
                            $userRole = $user->getRoleNames()->first() ?? 'none';
                            $badgeVariant = $roleBadgeMap[$userRole] ?? 'default';
                        @endphp
                        <x-badge :variant="$badgeVariant" size="sm">
                            {{ ucfirst($userRole) }}
                        </x-badge>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-gray-500">No users yet</p>
                    </div>
                @endforelse
            </div>
        </x-card>

        <!-- Recent Invitations -->
        <x-card variant="default" padding="none">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Invitations</h3>
                    <a href="{{ route('invitations.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">View all</a>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @php
                    $recentInvitations = \App\Models\Invitation::where('company_id', auth()->user()->company_id)
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                @forelse ($recentInvitations as $invitation)
                    <div class="p-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $invitation->email }}</p>
                            <p class="text-xs text-gray-500">{{ $invitation->role }}</p>
                        </div>
                        @php
                            $invitationStatus = 'pending';
                            $invitationVariant = 'warning';
                            if ($invitation->isAccepted()) {
                                $invitationStatus = 'Accepted';
                                $invitationVariant = 'success';
                            } elseif ($invitation->isExpired()) {
                                $invitationStatus = 'Expired';
                                $invitationVariant = 'danger';
                            } else {
                                $invitationStatus = 'Pending';
                                $invitationVariant = 'warning';
                            }
                        @endphp
                        <x-badge :variant="$invitationVariant" size="sm" :dot="true">
                            {{ $invitationStatus }}
                        </x-badge>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-gray-500">No invitations yet</p>
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>
    @endif

    <!-- Help/Info for users without manage_users permission -->
    @unless (auth()->user()->can('manage_users'))
    <x-card variant="outlined" padding="lg">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-lg bg-sky-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Welcome to your dashboard</h3>
                <p class="mt-1 text-gray-600">
                    @if (auth()->user()->can('create_short_url'))
                        Use the quick actions above to create short URLs, or browse existing ones.
                    @elseif (auth()->user()->can('view_short_url_details'))
                        Browse short URLs you have access to using the navigation menu.
                    @else
                        Contact your administrator for access to features.
                    @endif
                </p>
            </div>
        </div>
    </x-card>
    @endunless
</div>
@endsection