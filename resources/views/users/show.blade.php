@extends('layouts.app')

@section('title', ' - {{ $user->name }}')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="mt-1 text-gray-600">User details</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="px-4 py-2.5 text-sm font-medium text-indigo-600 bg-white border border-indigo-300 rounded-lg hover:bg-indigo-50 transition-colors">
                    Edit
                </a>
            @endcan

            <a href="{{ route('users.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Back to Users
            </a>
        </div>
    </div>

    <!-- User Info Card -->
    <x-card variant="elevated" padding="lg">
        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100">
            <x-avatar :name="$user->name" size="2xl" />
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $user->name }}</p>
                <p class="text-gray-500">{{ $user->email }}</p>
                <div class="mt-2 flex items-center gap-2">
                    <x-badge
                        variant="@switch($user->getRoleNames()->first())
                            ['super_admin' => 'purple', 'admin' => 'primary', 'manager' => 'success', 'sales' => 'warning', 'member' => 'default', default => 'default']
                        @endswitch"
                        size="lg"
                    >
                        {{ ucfirst($user->getRoleNames()->first() ?? 'none') }}
                    </x-badge>

                    <x-badge
                        variant="@if($user->email_verified_at) success @else warning @endif"
                        size="sm"
                        :dot="true"
                    >
                        @if($user->email_verified_at) Email Verified @else Email Unverified @endif
                    </x-badge>

                    @if ($user->id === auth()->id())
                        <x-badge variant="info" size="sm" dot>You</x-badge>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500">Company</p>
                <p class="mt-1 text-gray-900">{{ $user->company->name ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">Member Since</p>
                <p class="mt-1 text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">Last Login</p>
                <p class="mt-1 text-gray-900">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
            </div>

            <div>
                <p class="text-sm font-medium text-gray-500">User ID</p>
                <p class="mt-1 text-gray-900 font-mono text-sm">{{ $user->id }}</p>
            </div>
        </div>
    </x-card>

    <!-- Danger Zone -->
    @can('delete', $user)
    <x-card variant="outlined" padding="lg" class="border-red-200 bg-red-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Danger Zone</h3>
                    <p class="text-sm text-gray-600">Once deleted, this user cannot be recovered.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you absolutely sure you want to delete this user? This action cannot be undone.')">
                @csrf @method('DELETE')
                <x-button type="submit" variant="danger" size="md">
                    Delete User
                </x-button>
            </form>
        </div>
    </x-card>
    @endcan
</div>
@endsection